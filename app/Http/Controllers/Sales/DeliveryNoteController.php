<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryNoteController extends Controller
{
    /**
     * Display a listing of delivery notes
     */
    public function index(Request $request)
    {
        $query = SalesOrder::with(['customer'])
            ->whereIn('status', ['Approved', 'Processing', 'Partial', 'Completed']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('so_no', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date filter
        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }

        $deliveryNotes = $query->orderBy('date', 'desc')->paginate(20);

        return view('sales.delivery-notes.index', compact('deliveryNotes'));
    }

    /**
     * Display the specified delivery note
     */
    public function show($id)
    {
        $deliveryNote = SalesOrder::with(['customer', 'details.item'])->findOrFail($id);
        return view('sales.delivery-notes.show', compact('deliveryNote'));
    }

    /**
     * Show the form for creating a new delivery note
     */
    public function create()
    {
        $salesOrders = SalesOrder::where('status', 'Approved')->with('customer')->orderBy('so_no')->get();
        
        // Generate delivery note number
        $lastNote = DB::table('sales_orders')->latest('id')->first();
        $noteNo = 'DN-' . date('Y') . '-' . str_pad(($lastNote ? $lastNote->id + 1 : 1), 5, '0', STR_PAD_LEFT);
        
        return view('sales.delivery-notes.create', compact('salesOrders', 'noteNo'));
    }

    /**
     * Store a newly created delivery note
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'delivery_note_no' => 'required|string',
            'date' => 'required|date',
            'sales_order_id' => 'required|exists:sales_orders,id',
            'delivery_date' => 'required|date',
            'vehicle_no' => 'nullable|string',
            'driver_name' => 'nullable|string',
            'driver_phone' => 'nullable|string',
            'delivery_address' => 'required|string',
            'status' => 'required|in:Ready,In Transit,Partial,Delivered',
            'notes' => 'nullable|string',
        ]);

        // Update the sales order with delivery information
        $salesOrder = SalesOrder::findOrFail($validated['sales_order_id']);
        $salesOrder->update([
            'delivery_date' => $validated['delivery_date'],
            'notes' => $validated['notes'] ?? $salesOrder->notes,
        ]);

        return redirect()->route('sales.delivery-notes.index')
            ->with('success', 'Delivery note created successfully');
    }

    /**
     * Show the form for editing delivery note
     */
    public function edit($id)
    {
        $deliveryNote = SalesOrder::findOrFail($id);
        $salesOrders = SalesOrder::where('status', 'Approved')->with('customer')->orderBy('so_no')->get();
        
        return view('sales.delivery-notes.edit', compact('deliveryNote', 'salesOrders'));
    }

    /**
     * Update the specified delivery note
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'delivery_date' => 'required|date',
            'status' => 'required|in:Ready,In Transit,Partial,Delivered',
            'notes' => 'nullable|string',
        ]);

        $salesOrder = SalesOrder::findOrFail($id);
        $salesOrder->update([
            'delivery_date' => $validated['delivery_date'],
            'status' => $validated['status'] == 'Delivered' ? 'Completed' : 'Processing',
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('sales.delivery-notes.index')
            ->with('success', 'Delivery note updated successfully');
    }

    /**
     * Remove the specified delivery note
     */
    public function destroy($id)
    {
        $salesOrder = SalesOrder::findOrFail($id);
        
        if (!in_array($salesOrder->status, ['Approved', 'Processing'])) {
            return back()->with('error', 'Cannot delete delivery note for this order');
        }

        return redirect()->route('sales.delivery-notes.index')
            ->with('error', 'Delivery notes cannot be deleted. Update status instead.');
    }
}
