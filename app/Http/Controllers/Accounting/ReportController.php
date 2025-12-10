<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use App\Models\Ledger;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display Trial Balance Report
     */
    public function trialBalance(Request $request)
    {
        $fromDate = $request->input('from_date', now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));

        // Get all accounts with their balances
        $accounts = ChartOfAccount::where('is_active', 1)
            ->orderBy('account_code')
            ->get()
            ->map(function($account) use ($fromDate, $toDate) {
                $debit = Ledger::where('account_id', $account->id)
                    ->whereBetween('transaction_date', [$fromDate, $toDate])
                    ->sum('debit');
                
                $credit = Ledger::where('account_id', $account->id)
                    ->whereBetween('transaction_date', [$fromDate, $toDate])
                    ->sum('credit');
                
                $account->debit_total = $debit;
                $account->credit_total = $credit;
                $account->balance = $debit - $credit;
                
                return $account;
            })
            ->filter(function($account) {
                return $account->debit_total > 0 || $account->credit_total > 0;
            });

        $totalDebit = $accounts->sum('debit_total');
        $totalCredit = $accounts->sum('credit_total');

        return view('accounting.reports.trial-balance', compact('accounts', 'totalDebit', 'totalCredit', 'fromDate', 'toDate'));
    }

    /**
     * Display Profit & Loss Statement
     */
    public function profitLoss(Request $request)
    {
        $fromDate = $request->input('from_date', now()->startOfYear()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));

        // Get Revenue accounts
        $revenueAccounts = ChartOfAccount::whereHas('accountType', function($q) {
            $q->where('name', 'like', '%Revenue%')->orWhere('name', 'like', '%Income%');
        })->with(['ledgers' => function($q) use ($fromDate, $toDate) {
            $q->whereBetween('transaction_date', [$fromDate, $toDate]);
        }])->get();

        // Get Expense accounts
        $expenseAccounts = ChartOfAccount::whereHas('accountType', function($q) {
            $q->where('name', 'like', '%Expense%')->orWhere('name', 'like', '%Cost%');
        })->with(['ledgers' => function($q) use ($fromDate, $toDate) {
            $q->whereBetween('transaction_date', [$fromDate, $toDate]);
        }])->get();

        $totalRevenue = $revenueAccounts->sum(function($account) {
            return $account->ledgers->sum('credit') - $account->ledgers->sum('debit');
        });

        $totalExpense = $expenseAccounts->sum(function($account) {
            return $account->ledgers->sum('debit') - $account->ledgers->sum('credit');
        });

        $netProfit = $totalRevenue - $totalExpense;

        return view('accounting.reports.profit-loss', compact('revenueAccounts', 'expenseAccounts', 'totalRevenue', 'totalExpense', 'netProfit', 'fromDate', 'toDate'));
    }

    /**
     * Display Balance Sheet
     */
    public function balanceSheet(Request $request)
    {
        $asOfDate = $request->input('as_of_date', now()->format('Y-m-d'));

        // Assets
        $assetAccounts = ChartOfAccount::whereHas('accountType', function($q) {
            $q->where('name', 'like', '%Asset%');
        })->with(['ledgers' => function($q) use ($asOfDate) {
            $q->where('transaction_date', '<=', $asOfDate);
        }])->get();

        // Liabilities
        $liabilityAccounts = ChartOfAccount::whereHas('accountType', function($q) {
            $q->where('name', 'like', '%Liability%');
        })->with(['ledgers' => function($q) use ($asOfDate) {
            $q->where('transaction_date', '<=', $asOfDate);
        }])->get();

        // Equity
        $equityAccounts = ChartOfAccount::whereHas('accountType', function($q) {
            $q->where('name', 'like', '%Equity%')->orWhere('name', 'like', '%Capital%');
        })->with(['ledgers' => function($q) use ($asOfDate) {
            $q->where('transaction_date', '<=', $asOfDate);
        }])->get();

        $totalAssets = $assetAccounts->sum(function($account) {
            return $account->ledgers->sum('debit') - $account->ledgers->sum('credit');
        });

        $totalLiabilities = $liabilityAccounts->sum(function($account) {
            return $account->ledgers->sum('credit') - $account->ledgers->sum('debit');
        });

        $totalEquity = $equityAccounts->sum(function($account) {
            return $account->ledgers->sum('credit') - $account->ledgers->sum('debit');
        });

        return view('accounting.reports.balance-sheet', compact('assetAccounts', 'liabilityAccounts', 'equityAccounts', 'totalAssets', 'totalLiabilities', 'totalEquity', 'asOfDate'));
    }
}
