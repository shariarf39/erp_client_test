<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\Customer;
use App\Models\Item;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Quotation::with(['customer', 'createdBy']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('quotation_no', 'like', "%{$search}%")
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

        $quotations = $query->orderBy('date', 'desc')->paginate(20);

        return view('sales.quotations.index', compact('quotations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::where('status', 'Active')->orderBy('name')->get();
        $items = Item::where('is_active', 1)->orderBy('name')->get();
        
        // Generate quotation number
        $lastQuotation = Quotation::latest('id')->first();
        $quotationNo = 'QT-' . date('Y') . '-' . str_pad(($lastQuotation ? $lastQuotation->id + 1 : 1), 5, '0', STR_PAD_LEFT);
        
        return view('sales.quotations.create', compact('customers', 'items', 'quotationNo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'quotation_no' => 'required|unique:quotations',
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'validity_date' => 'nullable|date',
            'payment_terms' => 'nullable|string',
            'delivery_terms' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:Draft,Sent,Accepted,Rejected,Expired',
        ]);

        $validated['created_by'] = auth()->id();
        $quotation = Quotation::create($validated);

        return redirect()->route('sales.quotations.index')
            ->with('success', 'Quotation created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $quotation = Quotation::with(['customer', 'details.item', 'createdBy'])->findOrFail($id);
        return view('sales.quotations.show', compact('quotation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $quotation = Quotation::findOrFail($id);
        $customers = Customer::where('status', 'Active')->orderBy('name')->get();
        $items = Item::where('is_active', 1)->orderBy('name')->get();
        
        return view('sales.quotations.edit', compact('quotation', 'customers', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $quotation = Quotation::findOrFail($id);
        
        $validated = $request->validate([
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'validity_date' => 'nullable|date',
            'payment_terms' => 'nullable|string',
            'delivery_terms' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:Draft,Sent,Accepted,Rejected,Expired',
        ]);

        $quotation->update($validated);

        return redirect()->route('sales.quotations.index')
            ->with('success', 'Quotation updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $quotation = Quotation::findOrFail($id);
        
        if ($quotation->status != 'Draft') {
            return redirect()->route('sales.quotations.index')
                ->with('error', 'Only draft quotations can be deleted');
        }
        
        $quotation->delete();
        
        return redirect()->route('sales.quotations.index')
            ->with('success', 'Quotation deleted successfully');
    }
}
