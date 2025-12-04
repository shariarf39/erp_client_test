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
        $validated = $request->validate([
            'voucher_no' => 'required|unique:vouchers',
            'voucher_type' => 'required|in:Journal,Payment,Receipt,Contra',
            'date' => 'required|date',
            'reference' => 'nullable|max:100',
            'description' => 'nullable',
            'debit_entries' => 'required|array',
            'debit_entries.*.account_id' => 'required|exists:chart_of_accounts,id',
            'debit_entries.*.amount' => 'required|numeric|min:0.01',
            'debit_entries.*.description' => 'nullable',
            'credit_entries' => 'required|array',
            'credit_entries.*.account_id' => 'required|exists:chart_of_accounts,id',
            'credit_entries.*.amount' => 'required|numeric|min:0.01',
            'credit_entries.*.description' => 'nullable',
        ]);

        // Calculate totals
        $totalDebit = array_sum(array_column($request->debit_entries, 'amount'));
        $totalCredit = array_sum(array_column($request->credit_entries, 'amount'));

        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->withErrors(['error' => 'Debit and Credit amounts must be equal.'])->withInput();
        }

        $voucher = \App\Models\Voucher::create([
            'voucher_no' => $validated['voucher_no'],
            'voucher_type' => $validated['voucher_type'],
            'date' => $validated['date'],
            'reference' => $validated['reference'] ?? null,
            'description' => $validated['description'] ?? null,
            'total_amount' => $totalDebit,
            'status' => 'Posted',
            'created_by' => auth()->id(),
        ]);

        // Create debit entries
        foreach ($request->debit_entries as $entry) {
            \App\Models\VoucherDetail::create([
                'voucher_id' => $voucher->id,
                'account_id' => $entry['account_id'],
                'debit' => $entry['amount'],
                'credit' => 0,
                'description' => $entry['description'] ?? null,
            ]);

            // Update account balance
            $account = ChartOfAccount::find($entry['account_id']);
            $account->current_balance += $entry['amount'];
            $account->save();
        }

        // Create credit entries
        foreach ($request->credit_entries as $entry) {
            \App\Models\VoucherDetail::create([
                'voucher_id' => $voucher->id,
                'account_id' => $entry['account_id'],
                'debit' => 0,
                'credit' => $entry['amount'],
                'description' => $entry['description'] ?? null,
            ]);

            // Update account balance
            $account = ChartOfAccount::find($entry['account_id']);
            $account->current_balance -= $entry['amount'];
            $account->save();
        }

        return redirect()->route('accounting.vouchers.index')
            ->with('success', 'Voucher created and posted successfully.');
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
