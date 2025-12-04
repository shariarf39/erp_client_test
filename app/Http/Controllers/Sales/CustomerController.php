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
    public function show(Customer $customer)
    {
        $customer->load(['salesOrders' => function($query) {
            $query->latest()->limit(10);
        }]);
        return view('sales.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('sales.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'customer_code' => 'required|unique:customers,customer_code,' . $customer->id,
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
            'tax_number' => 'nullable|max:50',
            'is_active' => 'boolean',
        ]);

        try {
            $customer->update($validated);
            return redirect()->route('sales.customers.show', $customer)
                ->with('success', 'Customer updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error updating customer: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        if ($customer->salesOrders()->count() > 0) {
            return redirect()->route('sales.customers.index')
                ->with('error', 'Cannot delete customer with existing sales orders.');
        }

        try {
            $customer->delete();
            return redirect()->route('sales.customers.index')
                ->with('success', 'Customer deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('sales.customers.index')
                ->with('error', 'Error deleting customer: ' . $e->getMessage());
        }
    }
}
