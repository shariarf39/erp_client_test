@echo off
echo ================================================
echo SENA.ERP - Complete Setup Script
echo ================================================

echo.
echo Step 1: Creating Database Schema...
mysql -u root sena_erp < database\complete_schema.sql
mysql -u root sena_erp < database\schema_part2_leave_payroll_attendance.sql
mysql -u root sena_erp < database\schema_part3_inventory_purchase_sales.sql
mysql -u root sena_erp < database\schema_part4_accounting_import_lc.sql

echo.
echo Step 2: Running Laravel Migrations...
php artisan migrate:fresh

echo.
echo Step 3: Creating Controllers...
php artisan make:controller Auth\LoginController
php artisan make:controller Auth\RegisterController
php artisan make:controller DashboardController
php artisan make:controller HR\EmployeeController --resource
php artisan make:controller HR\DepartmentController --resource
php artisan make:controller HR\DesignationController --resource
php artisan make:controller HR\LeaveController --resource
php artisan make:controller Payroll\SalaryController --resource
php artisan make:controller Payroll\PayrollController --resource
php artisan make:controller Attendance\AttendanceController --resource
php artisan make:controller Attendance\ShiftController --resource
php artisan make:controller Inventory\ItemController --resource
php artisan make:controller Inventory\CategoryController --resource
php artisan make:controller Inventory\WarehouseController --resource
php artisan make:controller Inventory\StockController --resource
php artisan make:controller Purchase\VendorController --resource
php artisan make:controller Purchase\PurchaseRequisitionController --resource
php artisan make:controller Purchase\PurchaseOrderController --resource
php artisan make:controller Purchase\GRNController --resource
php artisan make:controller Sales\CustomerController --resource
php artisan make:controller Sales\QuotationController --resource
php artisan make:controller Sales\SalesOrderController --resource
php artisan make:controller Sales\InvoiceController --resource
php artisan make:controller Accounting\ChartOfAccountController --resource
php artisan make:controller Accounting\VoucherController --resource
php artisan make:controller Accounting\ReportController
php artisan make:controller ImportExport\ImportIndentController --resource
php artisan make:controller ImportExport\ShipmentController --resource
php artisan make:controller LC\LCApplicationController --resource

echo.
echo Step 4: Creating Additional Models...
php artisan make:model Warehouse
php artisan make:model Stock
php artisan make:model PurchaseRequisition
php artisan make:model GRN
php artisan make:model Quotation
php artisan make:model SalesInvoice
php artisan make:model ImportIndent
php artisan make:model LCApplication
php artisan make:model Shipment

echo.
echo Step 5: Creating Middleware...
php artisan make:middleware CheckPermission
php artisan make:middleware AuditLog

echo.
echo Step 6: Publishing Vendor Files...
php artisan vendor:publish --tag=laravel-assets

echo.
echo Step 7: Creating Storage Link...
php artisan storage:link

echo.
echo Step 8: Compiling Assets...
call npm install
call npm run build

echo.
echo Step 9: Optimizing Application...
php artisan optimize:clear
php artisan config:cache
php artisan route:cache

echo.
echo ================================================
echo SETUP COMPLETE!
echo ================================================
echo.
echo Access your application at: http://localhost:8000
echo Default Login: admin@senaerp.com / password
echo.
echo To start development server, run: php artisan serve
echo.
pause
