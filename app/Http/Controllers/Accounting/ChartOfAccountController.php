<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;

class ChartOfAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ChartOfAccount::with('parent');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('account_name', 'like', "%{$search}%")
                  ->orWhere('account_code', 'like', "%{$search}%");
            });
        }

        // Account type filter (using account_type_id)
        if ($request->filled('account_type_id')) {
            $query->where('account_type_id', $request->account_type_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $accounts = $query->orderBy('account_code')->paginate(20);

        return view('accounting.chart-of-accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get parent accounts for hierarchy
        $parentAccounts = ChartOfAccount::where('is_active', 1)
            ->orderBy('account_code')
            ->get();
        
        // Get account types
        $accountTypes = \App\Models\AccountType::where('is_active', 1)
            ->orderBy('code')
            ->get();

        return view('accounting.chart-of-accounts.create', compact('parentAccounts', 'accountTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_code' => 'required|unique:chart_of_accounts',
            'account_name' => 'required|max:255',
            'account_type_id' => 'required|exists:account_types,id',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'description' => 'nullable',
            'opening_balance' => 'nullable|numeric',
            'is_active' => 'boolean',
        ]);

        $validated['current_balance'] = $validated['opening_balance'] ?? 0;

        ChartOfAccount::create($validated);

        return redirect()->route('accounting.chart-of-accounts.index')
            ->with('success', 'Account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ChartOfAccount $chartOfAccount)
    {
        $chartOfAccount->load(['parent', 'children', 'accountType', 'voucherDetails' => function($query) {
            $query->with('voucher')->latest()->limit(20);
        }]);
        return view('accounting.chart-of-accounts.show', compact('chartOfAccount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChartOfAccount $chartOfAccount)
    {
        // Get parent accounts for hierarchy (excluding self and children to prevent circular reference)
        $parentAccounts = ChartOfAccount::where('is_active', 1)
            ->where('id', '!=', $chartOfAccount->id)
            ->orderBy('account_code')
            ->get();
        
        // Get account types
        $accountTypes = \App\Models\AccountType::where('is_active', 1)
            ->orderBy('code')
            ->get();

        return view('accounting.chart-of-accounts.edit', compact('chartOfAccount', 'parentAccounts', 'accountTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChartOfAccount $chartOfAccount)
    {
        $validated = $request->validate([
            'account_code' => 'required|unique:chart_of_accounts,account_code,' . $chartOfAccount->id,
            'account_name' => 'required|max:255',
            'account_type_id' => 'required|exists:account_types,id',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'description' => 'nullable',
            'is_active' => 'boolean',
        ]);

        // Prevent setting self as parent
        if (isset($validated['parent_id']) && $validated['parent_id'] == $chartOfAccount->id) {
            return back()->withInput()->with('error', 'Account cannot be its own parent.');
        }

        try {
            $chartOfAccount->update($validated);
            return redirect()->route('accounting.chart-of-accounts.show', $chartOfAccount)
                ->with('success', 'Account updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error updating account: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChartOfAccount $chartOfAccount)
    {
        // Check if account has child accounts
        if ($chartOfAccount->children()->count() > 0) {
            return redirect()->route('accounting.chart-of-accounts.index')
                ->with('error', 'Cannot delete account with sub-accounts. Delete or reassign sub-accounts first.');
        }

        // Check if account has transactions
        if ($chartOfAccount->voucherDetails()->count() > 0) {
            return redirect()->route('accounting.chart-of-accounts.index')
                ->with('error', 'Cannot delete account with existing transactions.');
        }

        try {
            $chartOfAccount->delete();
            return redirect()->route('accounting.chart-of-accounts.index')
                ->with('success', 'Account deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('accounting.chart-of-accounts.index')
                ->with('error', 'Error deleting account: ' . $e->getMessage());
        }
    }
}
