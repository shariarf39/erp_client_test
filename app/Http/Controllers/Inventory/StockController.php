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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
