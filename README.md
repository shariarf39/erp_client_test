# SENA.ERP - Enterprise Resource Planning System

![Laravel](https://img.shields.io/badge/Laravel-10.50.0-FF2D20?style=flat&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3.0-7952B3?style=flat&logo=bootstrap&logoColor=white)

A comprehensive Enterprise Resource Planning system built with Laravel 10, covering 10 major business modules.

## 🚀 Features

### Core Modules
- **👥 Human Resources** - Employee management, departments, designations
- **💰 Payroll Management** - Salary structures, payroll processing, payments
- **⏰ Attendance Tracking** - Check-in/out system, shift management, reports
- **📦 Inventory Management** - Items, stock tracking, warehouses
- **🛒 Purchase Management** - Vendors, PO, GRN, requisitions
- **💼 Sales Management** - Customers, quotations, sales orders, invoices
- **📊 Accounting & Finance** - COA, vouchers, ledgers, financial reports
- **🚢 Import/Export** - Import indents, shipments, cost tracking
- **🏦 LC Management** - Letter of credit applications, amendments
- **📈 Reports & Analytics** - Comprehensive reporting across all modules

## 🔑 Default Login

**Email:** admin@senaerp.com  
**Password:** password  
**Role:** Super Admin

⚠️ **Important:** Change the default password after first login!

## 🛠️ Quick Start

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Setup Environment
```bash
copy .env.example .env
php artisan key:generate
```

### 3. Configure Database (.env)
```env
DB_DATABASE=sena_erp
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Import Database
```powershell
Get-Content "database\complete_schema.sql" | c:\xampp\mysql\bin\mysql -u root sena_erp
Get-Content "database\schema_part2_leave_payroll_attendance.sql" | c:\xampp\mysql\bin\mysql -u root sena_erp
Get-Content "database\schema_part3_inventory_purchase_sales.sql" | c:\xampp\mysql\bin\mysql -u root sena_erp
Get-Content "database\schema_part4_accounting_import_lc.sql" | c:\xampp\mysql\bin\mysql -u root sena_erp
```

### 5. Run Application
```bash
php artisan storage:link
npm run build
php artisan serve
```

Visit: http://localhost:8000

## 📊 Development Status

**Current Version:** v0.5.0  
**Progress:** 45% Complete

### ✅ Completed
- Authentication system
- Dashboard with statistics
- HR Module (Full CRUD)
- Attendance Check-in/out
- 20+ Models
- Complete routing

### 🔄 In Progress
- Inventory, Purchase, Sales modules

See [PROJECT_STATUS.md](PROJECT_STATUS.md) for detailed progress.

## 📝 Usage

### Add Employee
HR > Employees > Add New Employee

### Record Attendance
Attendance > Check-In/Out > Check In/Out buttons

### Create Purchase Order
Purchase > Purchase Orders > Create New PO

## 🎨 Technology Stack

- **Backend:** Laravel 10.50.0, PHP 8.1+
- **Database:** MySQL 8.0
- **Frontend:** Blade, Bootstrap 5.3.0, jQuery 3.7.0
- **Icons:** Font Awesome 6.4.0

## 📄 License

MIT License

---

**Built with ❤️ using Laravel**
