<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::with(['category', 'unit'])->paginate(15);
        return view('inventory.items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', 1)->get();
        $brands = Brand::where('is_active', 1)->get();
        $units = Unit::where('is_active', 1)->get();
        
        return view('inventory.items.create', compact('categories', 'brands', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_code' => 'required|unique:items',
            'item_name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'required|exists:units,id',
            'description' => 'nullable',
            'barcode' => 'nullable|max:100',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'minimum_stock' => 'nullable|numeric|min:0',
            'maximum_stock' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        $validated['created_by'] = auth()->id();

        Item::create($validated);

        return redirect()->route('inventory.items.index')
            ->with('success', 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $item->load(['category', 'brand', 'unit', 'stock.warehouse']);
        return view('inventory.items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $categories = Category::where('is_active', 1)->get();
        $brands = Brand::where('is_active', 1)->get();
        $units = Unit::where('is_active', 1)->get();
        
        return view('inventory.items.edit', compact('item', 'categories', 'brands', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'item_code' => 'required|unique:items,item_code,' . $item->id,
            'item_name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'required|exists:units,id',
            'description' => 'nullable',
            'specifications' => 'nullable',
            'purchase_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'barcode' => 'nullable|max:100',
            'sku' => 'nullable|max:100',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($item->image && \Storage::disk('public')->exists($item->image)) {
                    \Storage::disk('public')->delete($item->image);
                }
                $validated['image'] = $request->file('image')->store('items', 'public');
            }

            $item->update($validated);

            return redirect()->route('inventory.items.show', $item)
                ->with('success', 'Item updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating item: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        try {
            // Check if item has stock records
            if ($item->stock()->exists()) {
                return redirect()->route('inventory.items.index')
                    ->with('error', 'Cannot delete item with existing stock records. Please remove stock first.');
            }

            // Delete image if exists
            if ($item->image && \Storage::disk('public')->exists($item->image)) {
                \Storage::disk('public')->delete($item->image);
            }

            $item->delete();

            return redirect()->route('inventory.items.index')
                ->with('success', 'Item deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->route('inventory.items.index')
                ->with('error', 'Error deleting item: ' . $e->getMessage());
        }
    }
}
