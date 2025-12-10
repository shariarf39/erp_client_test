<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use App\Models\SalesInvoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display sales analytics dashboard
     */
    public function index(Request $request)
    {
        $fromDate = $request->input('from_date', now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));

        // Total Sales
        $totalSales = SalesOrder::whereBetween('date', [$fromDate, $toDate])
            ->whereIn('status', ['Approved', 'Processing', 'Completed'])
            ->sum('total_amount');

        // Total Revenue (Paid Invoices)
        $totalRevenue = SalesInvoice::whereBetween('date', [$fromDate, $toDate])
            ->where('status', 'Paid')
            ->sum('total_amount');

        // Pending Amount
        $pendingAmount = SalesInvoice::whereBetween('date', [$fromDate, $toDate])
            ->whereIn('status', ['Pending', 'Partial'])
            ->sum('due_amount');

        // Total Orders
        $totalOrders = SalesOrder::whereBetween('date', [$fromDate, $toDate])->count();

        // Top Customers
        $topCustomers = SalesOrder::select('customer_id', DB::raw('SUM(total_amount) as total'))
            ->whereBetween('date', [$fromDate, $toDate])
            ->whereIn('status', ['Approved', 'Processing', 'Completed'])
            ->groupBy('customer_id')
            ->orderByDesc('total')
            ->limit(5)
            ->with('customer')
            ->get();

        // Sales by Month (Last 6 months)
        $salesByMonth = SalesOrder::select(
                DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('date', '>=', now()->subMonths(6))
            ->whereIn('status', ['Approved', 'Processing', 'Completed'])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Order Status Distribution
        $orderStatusDistribution = SalesOrder::select('status', DB::raw('COUNT(*) as count'))
            ->whereBetween('date', [$fromDate, $toDate])
            ->groupBy('status')
            ->get();

        return view('sales.analytics.index', compact(
            'totalSales',
            'totalRevenue',
            'pendingAmount',
            'totalOrders',
            'topCustomers',
            'salesByMonth',
            'orderStatusDistribution',
            'fromDate',
            'toDate'
        ));
    }
}
