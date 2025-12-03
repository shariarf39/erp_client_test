<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Models\Item;
use App\Models\PurchaseRequisition;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['vendor'])->orderBy('date', 'desc')->paginate(15);
        return view('purchase.orders.index', compact('purchaseOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = Vendor::where('is_active', 1)->get();
        $items = Item::where('is_active', 1)->with(['category', 'unit'])->get();
        $requisitions = PurchaseRequisition::where('status', 'Approved')->get();
        
        // Generate PO number
        $lastPO = PurchaseOrder::latest('id')->first();
        $poNumber = 'PO-' . date('Y') . '-' . str_pad(($lastPO ? $lastPO->id + 1 : 1), 5, '0', STR_PAD_LEFT);
        
        return view('purchase.orders.create', compact('vendors', 'items', 'requisitions', 'poNumber'));
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
