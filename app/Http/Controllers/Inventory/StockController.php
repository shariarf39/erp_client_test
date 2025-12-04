<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Warehouse;
use App\Models\Item;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Stock::with(['item.category', 'item.unit', 'warehouse']);

        // Filter by warehouse
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        // Search by item name or code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('item', function($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('item_code', 'like', "%{$search}%");
            });
        }

        // Filter by low stock (below reorder level)
        if ($request->filled('low_stock')) {
            $query->whereRaw('quantity <= reorder_level');
        }

        $stocks = $query->orderBy('updated_at', 'desc')->paginate(20);
        $warehouses = Warehouse::all();

        // Calculate total stock value
        $totalValue = Stock::with('item')->get()->sum(function($stock) {
            return $stock->quantity * ($stock->item->cost_price ?? 0);
        });

        return view('inventory.stock.index', compact('stocks', 'warehouses', 'totalValue'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Item::where('is_active', 1)->get();
        $warehouses = Warehouse::where('is_active', 1)->get();
        
        return view('inventory.stock.create', compact('items', 'warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|numeric|min:0',
            'reorder_level' => 'nullable|numeric|min:0',
            'max_level' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string|max:500',
        ]);

        try {
            // Check if stock already exists for this item-warehouse combination
            $existingStock = Stock::where('item_id', $validated['item_id'])
                ->where('warehouse_id', $validated['warehouse_id'])
                ->first();

            if ($existingStock) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Stock record already exists for this item in the selected warehouse. Please use edit to update.');
            }

            // Get item unit
            $item = Item::find($validated['item_id']);
            $validated['unit_id'] = $item->unit_id;
            $validated['last_transaction_date'] = now();
            $validated['last_transaction_type'] = 'IN';

            Stock::create($validated);

            return redirect()->route('inventory.stock.index')
                ->with('success', 'Stock record created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating stock: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Stock $stock)
    {
        $stock->load(['item.category', 'item.brand', 'item.unit', 'warehouse']);
        return view('inventory.stock.show', compact('stock'));
    }

    /**
     * Display stock report
     */
    public function report(Request $request)
    {
        $query = Stock::with(['item.category', 'item.brand', 'item.unit', 'warehouse']);

        // Filter by warehouse
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->whereHas('item', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'low':
                    $query->whereRaw('quantity <= reorder_level');
                    break;
                case 'out':
                    $query->where('quantity', '<=', 0);
                    break;
                case 'available':
                    $query->where('quantity', '>', 0);
                    break;
            }
        }

        $stocks = $query->orderBy('quantity', 'asc')->get();
        
        $warehouses = Warehouse::where('is_active', 1)->get();
        $categories = \App\Models\Category::where('is_active', 1)->get();

        // Calculate statistics
        $totalItems = $stocks->count();
        $totalQuantity = $stocks->sum('quantity');
        $lowStockItems = $stocks->filter(function($stock) {
            return $stock->quantity <= $stock->reorder_level;
        })->count();
        $outOfStockItems = $stocks->where('quantity', '<=', 0)->count();
        $totalValue = $stocks->sum(function($stock) {
            return $stock->quantity * ($stock->item->cost_price ?? 0);
        });

        return view('inventory.stock.report', compact(
            'stocks', 
            'warehouses', 
            'categories',
            'totalItems',
            'totalQuantity',
            'lowStockItems',
            'outOfStockItems',
            'totalValue'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stock $stock)
    {
        $stock->load(['item', 'warehouse']);
        return view('inventory.stock.edit', compact('stock'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'adjustment_type' => 'required|in:add,subtract,set',
            'adjustment_quantity' => 'required|numeric|min:0',
            'reorder_level' => 'nullable|numeric|min:0',
            'max_level' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string|max:500',
        ]);

        try {
            $oldQuantity = $stock->quantity;
            
            // Calculate new quantity based on adjustment type
            switch ($validated['adjustment_type']) {
                case 'add':
                    $newQuantity = $oldQuantity + $validated['adjustment_quantity'];
                    $transactionType = 'IN';
                    break;
                case 'subtract':
                    $newQuantity = $oldQuantity - $validated['adjustment_quantity'];
                    if ($newQuantity < 0) {
                        return redirect()->back()
                            ->withInput()
                            ->with('error', 'Cannot subtract more than available stock.');
                    }
                    $transactionType = 'OUT';
                    break;
                case 'set':
                    $newQuantity = $validated['adjustment_quantity'];
                    $transactionType = $newQuantity > $oldQuantity ? 'IN' : 'OUT';
                    break;
            }

            $stock->update([
                'quantity' => $newQuantity,
                'reorder_level' => $validated['reorder_level'] ?? $stock->reorder_level,
                'max_level' => $validated['max_level'] ?? $stock->max_level,
                'remarks' => $validated['remarks'] ?? $stock->remarks,
                'last_transaction_date' => now(),
                'last_transaction_type' => $transactionType,
            ]);

            return redirect()->route('inventory.stock.show', $stock)
                ->with('success', 'Stock adjusted successfully! Old: ' . $oldQuantity . ', New: ' . $newQuantity);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating stock: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock)
    {
        try {
            // Check if stock has quantity
            if ($stock->quantity > 0) {
                return redirect()->route('inventory.stock.index')
                    ->with('error', 'Cannot delete stock with non-zero quantity. Please adjust stock to zero first.');
            }

            $stock->delete();

            return redirect()->route('inventory.stock.index')
                ->with('success', 'Stock record deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->route('inventory.stock.index')
                ->with('error', 'Error deleting stock: ' . $e->getMessage());
        }
    }
}
