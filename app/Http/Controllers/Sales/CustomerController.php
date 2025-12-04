<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_code', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('sales.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Generate customer code
        $lastCustomer = Customer::orderBy('id', 'desc')->first();
        $nextNumber = $lastCustomer ? ((int) substr($lastCustomer->customer_code, 4)) + 1 : 1;
        $customerCode = 'CUST' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        return view('sales.customers.create', compact('customerCode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_code' => 'required|unique:customers',
            'customer_name' => 'required|max:255',
            'company_name' => 'nullable|max:255',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|max:20',
            'address' => 'nullable',
            'city' => 'nullable|max:100',
            'country' => 'nullable|max:100',
            'contact_person' => 'nullable|max:100',
            'credit_limit' => 'nullable|numeric|min:0',
            'credit_days' => 'nullable|integer|min:0',
            'opening_balance' => 'nullable|numeric',
            'tax_number' => 'nullable|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['current_balance'] = $validated['opening_balance'] ?? 0;
        $validated['created_by'] = auth()->id();

        Customer::create($validated);

        return redirect()->route('sales.customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
