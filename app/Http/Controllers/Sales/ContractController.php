<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    /**
     * Display a listing of sales contracts
     */
    public function index(Request $request)
    {
        $query = DB::table('sales_orders as so')
            ->join('customers as c', 'so.customer_id', '=', 'c.id')
            ->select(
                'so.id',
                'so.so_no as contract_no',
                'so.date as contract_date',
                'c.name as customer_name',
                'c.email as customer_email',
                'so.delivery_date as end_date',
                'so.total_amount',
                'so.status',
                'so.payment_terms'
            )
            ->whereIn('so.status', ['Approved', 'Processing', 'Completed']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('so.so_no', 'like', "%{$search}%")
                  ->orWhere('c.name', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('so.status', $request->status);
        }

        $contracts = $query->orderBy('so.date', 'desc')->paginate(20);

        return view('sales.contracts.index', compact('contracts'));
    }

    /**
     * Display the specified contract
     */
    public function show($id)
    {
        $contract = DB::table('sales_orders as so')
            ->join('customers as c', 'so.customer_id', '=', 'c.id')
            ->select('so.*', 'c.name as customer_name', 'c.email', 'c.phone', 'c.address')
            ->where('so.id', $id)
            ->first();

        return view('sales.contracts.show', compact('contract'));
    }

    /**
     * Show the form for creating a new contract
     */
    public function create()
    {
        $customers = Customer::where('status', 'Active')->orderBy('name')->get();
        
        return view('sales.contracts.create', compact('customers'));
    }

    /**
     * Store a newly created contract
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_no' => 'required|unique:sales_orders,so_no',
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'contract_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:Draft,Active,Completed,Terminated',
            'payment_terms' => 'required|string',
            'delivery_terms' => 'nullable|string',
            'terms_conditions' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Create as a sales order representing a contract
        DB::table('sales_orders')->insert([
            'so_no' => $validated['contract_no'],
            'date' => $validated['date'],
            'customer_id' => $validated['customer_id'],
            'total_amount' => $validated['contract_value'],
            'delivery_date' => $validated['end_date'],
            'payment_terms' => $validated['payment_terms'],
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('sales.contracts.index')
            ->with('success', 'Contract created successfully');
    }

    /**
     * Show the form for editing contract
     */
    public function edit($id)
    {
        $contract = DB::table('sales_orders')->where('id', $id)->first();
        $customers = Customer::where('status', 'Active')->orderBy('name')->get();
        
        return view('sales.contracts.edit', compact('contract', 'customers'));
    }

    /**
     * Update the specified contract
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'contract_value' => 'required|numeric|min:0',
            'end_date' => 'required|date',
            'status' => 'required|in:Draft,Active,Completed,Terminated',
            'payment_terms' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        DB::table('sales_orders')->where('id', $id)->update([
            'date' => $validated['date'],
            'customer_id' => $validated['customer_id'],
            'total_amount' => $validated['contract_value'],
            'delivery_date' => $validated['end_date'],
            'payment_terms' => $validated['payment_terms'],
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'],
            'updated_at' => now(),
        ]);

        return redirect()->route('sales.contracts.index')
            ->with('success', 'Contract updated successfully');
    }

    /**
     * Remove the specified contract
     */
    public function destroy($id)
    {
        $contract = DB::table('sales_orders')->where('id', $id)->first();
        
        if ($contract && $contract->status != 'Draft') {
            return back()->with('error', 'Only draft contracts can be deleted');
        }
        
        DB::table('sales_orders')->where('id', $id)->delete();

        return redirect()->route('sales.contracts.index')
            ->with('success', 'Contract deleted successfully');
    }
}
