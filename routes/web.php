<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HR\EmployeeController;
use App\Http\Controllers\Payroll\PayrollController;
use App\Http\Controllers\Inventory\ItemController;
use App\Http\Controllers\Purchase\PurchaseOrderController;
use App\Http\Controllers\Sales\SalesOrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // HR Module
    Route::prefix('hr')->name('hr.')->group(function () {
        Route::resource('employees', EmployeeController::class);
        Route::resource('departments', \App\Http\Controllers\HR\DepartmentController::class);
        Route::resource('designations', \App\Http\Controllers\HR\DesignationController::class);
        Route::resource('leaves', \App\Http\Controllers\HR\LeaveController::class);
        Route::post('leaves/{leave}/approve', [\App\Http\Controllers\HR\LeaveController::class, 'approve'])->name('leaves.approve');
        Route::post('leaves/{leave}/reject', [\App\Http\Controllers\HR\LeaveController::class, 'reject'])->name('leaves.reject');
        Route::post('leaves/{leave}/cancel', [\App\Http\Controllers\HR\LeaveController::class, 'cancel'])->name('leaves.cancel');
        
        // Recruitment (ATS)
        Route::prefix('recruitment')->name('recruitment.')->group(function () {
            Route::resource('jobs', \App\Http\Controllers\HR\JobPostingController::class);
            Route::resource('applicants', \App\Http\Controllers\HR\ApplicantController::class);
            Route::resource('interviews', \App\Http\Controllers\HR\InterviewController::class);
        });
        
        // Onboarding
        Route::prefix('onboarding')->name('onboarding.')->group(function () {
            Route::resource('checklists', \App\Http\Controllers\HR\OnboardingChecklistController::class);
            Route::resource('employee-onboarding', \App\Http\Controllers\HR\EmployeeOnboardingController::class);
        });
        
        // Shift Scheduling
        Route::resource('shifts', \App\Http\Controllers\HR\ShiftController::class);
        Route::resource('shift-schedules', \App\Http\Controllers\HR\ShiftScheduleController::class);
        
        // Performance & KPI
        Route::prefix('performance')->name('performance.')->group(function () {
            Route::resource('kpis', \App\Http\Controllers\HR\PerformanceKpiController::class);
            Route::resource('reviews', \App\Http\Controllers\HR\PerformanceReviewController::class);
        });
        
        // Training & Development
        Route::prefix('training')->name('training.')->group(function () {
            Route::resource('programs', \App\Http\Controllers\HR\TrainingProgramController::class);
            Route::resource('enrollments', \App\Http\Controllers\HR\TrainingEnrollmentController::class);
            Route::resource('skills', \App\Http\Controllers\HR\EmployeeSkillController::class);
        });
    });
    
    // Payroll Module
    Route::prefix('payroll')->name('payroll.')->group(function () {
        Route::resource('payroll', PayrollController::class);
        Route::get('process/{month}/{year}', [PayrollController::class, 'process'])->name('process');
        Route::resource('salary-structures', \App\Http\Controllers\Payroll\SalaryController::class);
    });
    
    // Attendance Module
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('check-in', [\App\Http\Controllers\Attendance\AttendanceController::class, 'checkIn'])->name('check-in');
        Route::post('/', [\App\Http\Controllers\Attendance\AttendanceController::class, 'store'])->name('store');
        Route::resource('attendance', \App\Http\Controllers\Attendance\AttendanceController::class);
        Route::resource('shifts', \App\Http\Controllers\Attendance\ShiftController::class);
    });
    
    // Inventory Module
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::resource('items', ItemController::class);
        Route::resource('categories', \App\Http\Controllers\Inventory\CategoryController::class);
        Route::resource('warehouses', \App\Http\Controllers\Inventory\WarehouseController::class);
        Route::resource('stock', \App\Http\Controllers\Inventory\StockController::class);
        Route::get('stock-report', [\App\Http\Controllers\Inventory\StockController::class, 'report'])->name('stock.report');
    });
    
    // Purchase Module
    Route::prefix('purchase')->name('purchase.')->group(function () {
        Route::resource('vendors', \App\Http\Controllers\Purchase\VendorController::class);
        Route::resource('requisitions', \App\Http\Controllers\Purchase\PurchaseRequisitionController::class);
        Route::resource('orders', PurchaseOrderController::class);
        Route::resource('grn', \App\Http\Controllers\Purchase\GRNController::class);
        Route::post('orders/{id}/approve', [PurchaseOrderController::class, 'approve'])->name('orders.approve');
    });
    
    // Sales Module
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::resource('customers', \App\Http\Controllers\Sales\CustomerController::class);
        Route::resource('quotations', \App\Http\Controllers\Sales\QuotationController::class);
        Route::resource('orders', SalesOrderController::class);
        Route::resource('invoices', \App\Http\Controllers\Sales\InvoiceController::class);
        Route::post('orders/{id}/approve', [SalesOrderController::class, 'approve'])->name('orders.approve');
    });
    
    // Accounting Module
    Route::prefix('accounting')->name('accounting.')->group(function () {
        Route::resource('chart-of-accounts', \App\Http\Controllers\Accounting\ChartOfAccountController::class);
        Route::resource('vouchers', \App\Http\Controllers\Accounting\VoucherController::class);
        Route::get('reports/trial-balance', [\App\Http\Controllers\Accounting\ReportController::class, 'trialBalance'])->name('reports.trial-balance');
        Route::get('reports/profit-loss', [\App\Http\Controllers\Accounting\ReportController::class, 'profitLoss'])->name('reports.profit-loss');
        Route::get('reports/balance-sheet', [\App\Http\Controllers\Accounting\ReportController::class, 'balanceSheet'])->name('reports.balance-sheet');
    });
    
    // Import/Export Module
    Route::prefix('import-export')->name('import-export.')->group(function () {
        Route::resource('imports', \App\Http\Controllers\ImportExport\ImportIndentController::class);
        Route::resource('shipments', \App\Http\Controllers\ImportExport\ShipmentController::class);
    });
    
    // LC Module
    Route::prefix('lc')->name('lc.')->group(function () {
        Route::resource('applications', \App\Http\Controllers\LC\LCApplicationController::class);
    });
});

