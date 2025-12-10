<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\SalesInvoice;
use App\Models\SalesOrder;
use App\Models\Customer;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SalesInvoice::with(['customer', 'salesOrder']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
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
        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        $invoices = $query->orderBy('date', 'desc')->paginate(20);

        return view('sales.invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::where('status', 'Active')->orderBy('name')->get();
        $salesOrders = SalesOrder::where('status', 'Approved')->orderBy('so_no')->get();
        
        // Generate invoice number
        $lastInvoice = SalesInvoice::latest('id')->first();
        $invoiceNo = 'INV-' . date('Y') . '-' . str_pad(($lastInvoice ? $lastInvoice->id + 1 : 1), 5, '0', STR_PAD_LEFT);
        
        return view('sales.invoices.create', compact('customers', 'salesOrders', 'invoiceNo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_no' => 'required|unique:sales_invoices',
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'so_id' => 'nullable|exists:sales_orders,id',
            'due_date' => 'nullable|date',
            'status' => 'required|in:Pending,Partial,Paid',
        ]);

        $invoice = SalesInvoice::create($validated);

        return redirect()->route('sales.invoices.index')
            ->with('success', 'Invoice created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoice = SalesInvoice::with(['customer', 'salesOrder', 'payments'])->findOrFail($id);
        return view('sales.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $invoice = SalesInvoice::findOrFail($id);
        $customers = Customer::where('status', 'Active')->orderBy('name')->get();
        $salesOrders = SalesOrder::where('status', 'Approved')->orderBy('so_no')->get();
        
        return view('sales.invoices.edit', compact('invoice', 'customers', 'salesOrders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $invoice = SalesInvoice::findOrFail($id);
        
        $validated = $request->validate([
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'due_date' => 'nullable|date',
            'status' => 'required|in:Pending,Partial,Paid',
        ]);

        $invoice->update($validated);

        return redirect()->route('sales.invoices.index')
            ->with('success', 'Invoice updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $invoice = SalesInvoice::findOrFail($id);
        
        if ($invoice->status != 'Pending') {
            return back()->with('error', 'Only pending invoices can be deleted');
        }
        
        $invoice->delete();

        return redirect()->route('sales.invoices.index')
            ->with('success', 'Invoice deleted successfully');
    }
}
