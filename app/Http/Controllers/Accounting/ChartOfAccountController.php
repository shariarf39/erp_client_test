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
