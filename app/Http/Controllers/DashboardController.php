<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        
        // Get counts for dashboard widgets
        $data = [
            'total_employees' => Employee::active()->count(),
            'total_customers' => Customer::where('is_active', true)->count(),
            'total_vendors' => Vendor::where('is_active', true)->count(),
            'total_items' => Item::where('is_active', true)->count(),
            
            // Attendance today
            'present_today' => Attendance::whereDate('date', today())
                ->where('status', 'Present')->count(),
            'absent_today' => Attendance::whereDate('date', today())
                ->where('status', 'Absent')->count(),
                
            // Purchase & Sales this month
            'purchase_orders_month' => PurchaseOrder::whereMonth('date', now()->month)
                ->whereYear('date', now()->year)->count(),
            'sales_orders_month' => SalesOrder::whereMonth('date', now()->month)
                ->whereYear('date', now()->year)->count(),
                
            // Pending approvals
            'pending_pos' => PurchaseOrder::where('status', 'Draft')->count(),
            'pending_sos' => SalesOrder::where('status', 'Draft')->count(),
        ];
        
        // Recent activities
        $recent_employees = Employee::latest()->take(5)->get();
        $recent_purchase_orders = PurchaseOrder::with('vendor')->latest()->take(5)->get();
        $recent_sales_orders = SalesOrder::with('customer')->latest()->take(5)->get();
        
        return view('dashboard.index', compact('data', 'user', 'recent_employees', 'recent_purchase_orders', 'recent_sales_orders'));
    }
}
