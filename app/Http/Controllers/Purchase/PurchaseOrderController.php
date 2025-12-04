<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Models\Item;
use App\Models\PurchaseRequisition;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['vendor'])->orderBy('date', 'desc')->paginate(15);
        return view('purchase.orders.index', compact('purchaseOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = Vendor::where('is_active', 1)->get();
        $items = Item::where('is_active', 1)->with(['category', 'unit'])->get();
        $requisitions = PurchaseRequisition::where('status', 'Approved')->get();
        
        // Generate PO number
        $lastPO = PurchaseOrder::latest('id')->first();
        $poNumber = 'PO-' . date('Y') . '-' . str_pad(($lastPO ? $lastPO->id + 1 : 1), 5, '0', STR_PAD_LEFT);
        
        return view('purchase.orders.create', compact('vendors', 'items', 'requisitions', 'poNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'po_no' => 'required|unique:purchase_orders',
            'vendor_id' => 'required|exists:vendors,id',
            'pr_id' => 'nullable|exists:purchase_requisitions,id',
            'date' => 'required|date',
            'delivery_date' => 'nullable|date',
            'payment_terms' => 'nullable',
            'remarks' => 'nullable',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);

        $totalAmount = 0;
        foreach ($request->items as $item) {
            $subtotal = $item['quantity'] * $item['unit_price'];
            $tax = $subtotal * (($item['tax_rate'] ?? 0) / 100);
            $discount = $item['discount'] ?? 0;
            $totalAmount += $subtotal + $tax - $discount;
        }

        $po = \App\Models\PurchaseOrder::create([
            'po_no' => $validated['po_no'],
            'vendor_id' => $validated['vendor_id'],
            'pr_id' => $validated['pr_id'] ?? null,
            'date' => $validated['date'],
            'delivery_date' => $validated['delivery_date'] ?? null,
            'total_amount' => $totalAmount,
            'payment_terms' => $validated['payment_terms'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
            'status' => 'Draft',
            'created_by' => auth()->id(),
        ]);

        // Create PO items
        foreach ($request->items as $item) {
            $subtotal = $item['quantity'] * $item['unit_price'];
            $tax = $subtotal * (($item['tax_rate'] ?? 0) / 100);
            $discount = $item['discount'] ?? 0;
            
            \App\Models\PurchaseOrderDetail::create([
                'po_id' => $po->id,
                'item_id' => $item['item_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'tax_rate' => $item['tax_rate'] ?? 0,
                'discount' => $discount,
                'total' => $subtotal + $tax - $discount,
            ]);
        }

        return redirect()->route('purchase.orders.index')
            ->with('success', 'Purchase Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseOrder $order)
    {
        $order->load(['vendor', 'details.item.unit', 'details.item.category', 'purchaseRequisition', 'creator', 'approver']);
        return view('purchase.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrder $order)
    {
        if ($order->status != 'Draft') {
            return redirect()->route('purchase.orders.show', $order)
                ->with('error', 'Only Draft purchase orders can be edited.');
        }

        $order->load(['details.item']);
        $vendors = Vendor::where('is_active', 1)->get();
        $items = Item::where('is_active', 1)->with(['category', 'unit'])->get();
        $requisitions = PurchaseRequisition::where('status', 'Approved')->get();
        
        return view('purchase.orders.edit', compact('order', 'vendors', 'items', 'requisitions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseOrder $order)
    {
        if ($order->status != 'Draft') {
            return redirect()->route('purchase.orders.show', $order)
                ->with('error', 'Only Draft purchase orders can be updated.');
        }

        $validated = $request->validate([
            'po_no' => 'required|unique:purchase_orders,po_no,' . $order->id,
            'vendor_id' => 'required|exists:vendors,id',
            'pr_id' => 'nullable|exists:purchase_requisitions,id',
            'date' => 'required|date',
            'delivery_date' => 'nullable|date',
            'payment_terms' => 'nullable',
            'remarks' => 'nullable',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);

        try {
            \DB::beginTransaction();

            $totalAmount = 0;
            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $tax = $subtotal * (($item['tax_rate'] ?? 0) / 100);
                $discount = $item['discount'] ?? 0;
                $totalAmount += $subtotal + $tax - $discount;
            }

            $order->update([
                'po_no' => $validated['po_no'],
                'vendor_id' => $validated['vendor_id'],
                'pr_id' => $validated['pr_id'] ?? null,
                'date' => $validated['date'],
                'delivery_date' => $validated['delivery_date'] ?? null,
                'total_amount' => $totalAmount,
                'payment_terms' => $validated['payment_terms'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
            ]);

            // Delete old details and create new ones
            $order->details()->delete();
            
            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $tax = $subtotal * (($item['tax_rate'] ?? 0) / 100);
                $discount = $item['discount'] ?? 0;
                
                \App\Models\PurchaseOrderDetail::create([
                    'po_id' => $order->id,
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax_rate' => $item['tax_rate'] ?? 0,
                    'discount' => $discount,
                    'total' => $subtotal + $tax - $discount,
                ]);
            }

            \DB::commit();
            return redirect()->route('purchase.orders.show', $order)
                ->with('success', 'Purchase Order updated successfully.');
        } catch (\Exception $e) {
            \DB::rollback();
            return back()->withInput()->with('error', 'Error updating purchase order: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrder $order)
    {
        if ($order->status == 'Approved') {
            return redirect()->route('purchase.orders.index')
                ->with('error', 'Cannot delete approved purchase orders.');
        }

        try {
            \DB::beginTransaction();
            $order->details()->delete();
            $order->delete();
            \DB::commit();

            return redirect()->route('purchase.orders.index')
                ->with('success', 'Purchase Order deleted successfully.');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->route('purchase.orders.index')
                ->with('error', 'Error deleting purchase order: ' . $e->getMessage());
        }
    }
}
