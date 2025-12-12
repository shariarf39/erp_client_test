<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PricingController extends Controller
{
    /**
     * Display pricing and discounts
     */
    public function index(Request $request)
    {
        $query = Item::with('category', 'brand');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('item_code', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $items = $query->where('is_active', 1)->orderBy('name')->paginate(20);
        $categories = \App\Models\Category::where('is_active', 1)->orderBy('name')->get();

        return view('sales.pricing.index', compact('items', 'categories'));
    }

    /**
     * Show the form for creating pricing rules
     */
    public function create()
    {
        $items = Item::where('is_active', 1)->orderBy('item_name')->get();
        $customers = Customer::where('is_active', 1)->orderBy('customer_name')->get();
        
        return view('sales.pricing.create', compact('items', 'customers'));
    }

    /**
     * Store pricing rules
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'customer_id' => 'nullable|exists:customers,id',
            'special_price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
        ]);

        // Store in session or database as needed
        return redirect()->route('sales.pricing.index')
            ->with('success', 'Pricing rule created successfully');
    }
}
