<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = Voucher::orderBy('voucher_date', 'desc')->paginate(15);
        return view('accounting.vouchers.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = ChartOfAccount::where('is_active', 1)
            ->orderBy('account_code')
            ->get();
        
        // Generate voucher number
        $lastVoucher = Voucher::latest('id')->first();
        $voucherNo = 'JV-' . date('Y') . '-' . str_pad(($lastVoucher ? $lastVoucher->id + 1 : 1), 5, '0', STR_PAD_LEFT);
        
        return view('accounting.vouchers.create', compact('accounts', 'voucherNo'));
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
