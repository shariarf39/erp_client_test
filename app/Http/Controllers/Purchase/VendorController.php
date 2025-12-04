<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Vendor::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('vendor_name', 'like', "%{$search}%")
                  ->orWhere('vendor_code', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Type filter
        if ($request->filled('vendor_type')) {
            $query->where('vendor_type', $request->vendor_type);
        }

        $vendors = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('purchase.vendors.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Generate vendor code
        $lastVendor = Vendor::orderBy('id', 'desc')->first();
        $nextNumber = $lastVendor ? ((int) substr($lastVendor->vendor_code, 3)) + 1 : 1;
        $vendorCode = 'VEN' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        return view('purchase.vendors.create', compact('vendorCode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_code' => 'required|unique:vendors',
            'vendor_name' => 'required|max:255',
            'company_name' => 'nullable|max:255',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|max:20',
            'address' => 'nullable',
            'city' => 'nullable|max:100',
            'country' => 'nullable|max:100',
            'contact_person' => 'nullable|max:100',
            'credit_limit' => 'nullable|numeric|min:0',
            'credit_days' => 'nullable|integer|min:0',
            'payment_terms' => 'nullable',
            'tax_number' => 'nullable|max:50',
            'bank_name' => 'nullable|max:100',
            'bank_account' => 'nullable|max:50',
            'rating' => 'nullable|numeric|min:0|max:5',
            'is_active' => 'boolean',
        ]);

        $validated['created_by'] = auth()->id();

        Vendor::create($validated);

        return redirect()->route('purchase.vendors.index')
            ->with('success', 'Vendor created successfully.');
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
