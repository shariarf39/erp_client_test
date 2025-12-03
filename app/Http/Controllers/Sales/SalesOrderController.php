<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Quotation;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salesOrders = SalesOrder::with(['customer'])->orderBy('date', 'desc')->paginate(15);
        return view('sales.orders.index', compact('salesOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::where('is_active', 1)->get();
        $items = Item::where('is_active', 1)->with(['category', 'unit'])->get();
        $quotations = Quotation::where('status', 'Approved')->get();
        
        // Generate SO number
        $lastSO = SalesOrder::latest('id')->first();
        $soNumber = 'SO-' . date('Y') . '-' . str_pad(($lastSO ? $lastSO->id + 1 : 1), 5, '0', STR_PAD_LEFT);
        
        return view('sales.orders.create', compact('customers', 'items', 'quotations', 'soNumber'));
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
