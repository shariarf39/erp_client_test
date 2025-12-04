<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Quotation;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salesOrders = SalesOrder::with(['customer'])->orderBy('date', 'desc')->paginate(15);
        return view('sales.orders.index', compact('salesOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::where('is_active', 1)->get();
        $items = Item::where('is_active', 1)->with(['category', 'unit'])->get();
        $quotations = Quotation::where('status', 'Approved')->get();
        
        // Generate SO number
        $lastSO = SalesOrder::latest('id')->first();
        $soNumber = 'SO-' . date('Y') . '-' . str_pad(($lastSO ? $lastSO->id + 1 : 1), 5, '0', STR_PAD_LEFT);
        
        return view('sales.orders.create', compact('customers', 'items', 'quotations', 'soNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'so_no' => 'required|unique:sales_orders',
            'customer_id' => 'required|exists:customers,id',
            'quotation_id' => 'nullable|exists:quotations,id',
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

        $so = \App\Models\SalesOrder::create([
            'so_no' => $validated['so_no'],
            'customer_id' => $validated['customer_id'],
            'quotation_id' => $validated['quotation_id'] ?? null,
            'date' => $validated['date'],
            'delivery_date' => $validated['delivery_date'] ?? null,
            'total_amount' => $totalAmount,
            'payment_terms' => $validated['payment_terms'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
            'status' => 'Draft',
            'created_by' => auth()->id(),
        ]);

        // Create SO items
        foreach ($request->items as $item) {
            $subtotal = $item['quantity'] * $item['unit_price'];
            $tax = $subtotal * (($item['tax_rate'] ?? 0) / 100);
            $discount = $item['discount'] ?? 0;
            
            \App\Models\SalesOrderDetail::create([
                'so_id' => $so->id,
                'item_id' => $item['item_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'tax_rate' => $item['tax_rate'] ?? 0,
                'discount' => $discount,
                'total' => $subtotal + $tax - $discount,
            ]);
        }

        return redirect()->route('sales.orders.index')
            ->with('success', 'Sales Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesOrder $order)
    {
        $order->load(['customer', 'details.item.unit', 'details.item.category', 'quotation', 'creator', 'approver']);
        return view('sales.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesOrder $order)
    {
        if ($order->status != 'Draft') {
            return redirect()->route('sales.orders.show', $order)
                ->with('error', 'Only Draft sales orders can be edited.');
        }

        $order->load(['details.item']);
        $customers = Customer::where('is_active', 1)->get();
        $items = Item::where('is_active', 1)->with(['category', 'unit'])->get();
        $quotations = Quotation::where('status', 'Approved')->get();
        
        return view('sales.orders.edit', compact('order', 'customers', 'items', 'quotations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesOrder $order)
    {
        if ($order->status != 'Draft') {
            return redirect()->route('sales.orders.show', $order)
                ->with('error', 'Only Draft sales orders can be updated.');
        }

        $validated = $request->validate([
            'so_no' => 'required|unique:sales_orders,so_no,' . $order->id,
            'customer_id' => 'required|exists:customers,id',
            'quotation_id' => 'nullable|exists:quotations,id',
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
                'so_no' => $validated['so_no'],
                'customer_id' => $validated['customer_id'],
                'quotation_id' => $validated['quotation_id'] ?? null,
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
                
                \App\Models\SalesOrderDetail::create([
                    'so_id' => $order->id,
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax_rate' => $item['tax_rate'] ?? 0,
                    'discount' => $discount,
                    'total' => $subtotal + $tax - $discount,
                ]);
            }

            \DB::commit();
            return redirect()->route('sales.orders.show', $order)
                ->with('success', 'Sales Order updated successfully.');
        } catch (\Exception $e) {
            \DB::rollback();
            return back()->withInput()->with('error', 'Error updating sales order: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesOrder $order)
    {
        if ($order->status == 'Confirmed') {
            return redirect()->route('sales.orders.index')
                ->with('error', 'Cannot delete confirmed sales orders.');
        }

        try {
            \DB::beginTransaction();
            $order->details()->delete();
            $order->delete();
            \DB::commit();

            return redirect()->route('sales.orders.index')
                ->with('success', 'Sales Order deleted successfully.');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->route('sales.orders.index')
                ->with('error', 'Error deleting sales order: ' . $e->getMessage());
        }
    }
}
