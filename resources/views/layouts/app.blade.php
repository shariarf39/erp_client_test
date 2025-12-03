<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SENA.ERP - Enterprise Resource Planning')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --info-color: #16a085;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, #34495e 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 4px 0;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .main-header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 15px 30px;
            margin-bottom: 30px;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .card-header {
            background: white;
            border-bottom: 2px solid #f0f0f0;
            padding: 15px 20px;
            font-weight: 600;
        }
        
        .stat-card {
            border-left: 4px solid var(--primary-color);
        }
        
        .stat-card.success {
            border-left-color: var(--success-color);
        }
        
        .stat-card.danger {
            border-left-color: var(--danger-color);
        }
        
        .stat-card.warning {
            border-left-color: var(--warning-color);
        }
        
        .stat-card.info {
            border-left-color: var(--info-color);
        }
        
        .btn-primary {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-primary:hover {
            background: #2980b9;
            border-color: #2980b9;
        }
        
        .table th {
            background: #f8f9fa;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }
        
        .badge {
            padding: 6px 12px;
            font-weight: 500;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 col-lg-2 px-0 sidebar">
                <div class="p-3 text-center">
                    <h4 class="text-white mb-0"><i class="fas fa-building"></i> SENA.ERP</h4>
                    <small class="text-white-50">v1.0.0</small>
                </div>
                <hr class="text-white">
                <nav class="nav flex-column px-2">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    
                    <div class="text-white-50 small px-3 mt-3 mb-2">HR MANAGEMENT</div>
                    <a class="nav-link {{ request()->routeIs('hr.*') ? 'active' : '' }}" href="{{ route('hr.employees.index') }}">
                        <i class="fas fa-users"></i> Employees
                    </a>
                    <a class="nav-link" href="{{ route('hr.departments.index') }}">
                        <i class="fas fa-sitemap"></i> Departments
                    </a>
                    <a class="nav-link" href="{{ route('hr.leaves.index') }}">
                        <i class="fas fa-calendar-alt"></i> Leave Management
                    </a>
                    
                    <div class="text-white-50 small px-3 mt-3 mb-2">PAYROLL</div>
                    <a class="nav-link {{ request()->routeIs('payroll.*') ? 'active' : '' }}" href="{{ route('payroll.payroll.index') }}">
                        <i class="fas fa-money-check-alt"></i> Payroll
                    </a>
                    <a class="nav-link" href="{{ route('payroll.salary-structures.index') }}">
                        <i class="fas fa-coins"></i> Salary Structure
                    </a>
                    
                    <div class="text-white-50 small px-3 mt-3 mb-2">ATTENDANCE</div>
                    <a class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}" href="{{ route('attendance.attendance.index') }}">
                        <i class="fas fa-clock"></i> Attendance
                    </a>
                    
                    <div class="text-white-50 small px-3 mt-3 mb-2">INVENTORY</div>
                    <a class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}" href="{{ route('inventory.items.index') }}">
                        <i class="fas fa-boxes"></i> Items
                    </a>
                    <a class="nav-link" href="{{ route('inventory.stock.index') }}">
                        <i class="fas fa-warehouse"></i> Stock
                    </a>
                    
                    <div class="text-white-50 small px-3 mt-3 mb-2">PURCHASE</div>
                    <a class="nav-link {{ request()->routeIs('purchase.*') ? 'active' : '' }}" href="{{ route('purchase.orders.index') }}">
                        <i class="fas fa-shopping-cart"></i> Purchase Orders
                    </a>
                    <a class="nav-link" href="{{ route('purchase.vendors.index') }}">
                        <i class="fas fa-truck"></i> Vendors
                    </a>
                    
                    <div class="text-white-50 small px-3 mt-3 mb-2">SALES</div>
                    <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.orders.index') }}">
                        <i class="fas fa-shopping-bag"></i> Sales Orders
                    </a>
                    <a class="nav-link" href="{{ route('sales.customers.index') }}">
                        <i class="fas fa-user-tie"></i> Customers
                    </a>
                    
                    <div class="text-white-50 small px-3 mt-3 mb-2">ACCOUNTING</div>
                    <a class="nav-link {{ request()->routeIs('accounting.*') ? 'active' : '' }}" href="{{ route('accounting.vouchers.index') }}">
                        <i class="fas fa-file-invoice-dollar"></i> Vouchers
                    </a>
                    <a class="nav-link" href="{{ route('accounting.chart-of-accounts.index') }}">
                        <i class="fas fa-book"></i> Chart of Accounts
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 col-lg-10">
                <!-- Header -->
                <div class="main-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">@yield('page_title', 'Dashboard')</h5>
                        <small class="text-muted">@yield('page_description', 'Welcome to SENA.ERP')</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-bell text-muted"></i>
                        </div>
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                                <div class="me-2 text-end">
                                    <div class="fw-bold">{{ auth()->user()->name }}</div>
                                    <small class="text-muted">{{ auth()->user()->role->name ?? 'User' }}</small>
                                </div>
                                <i class="fas fa-user-circle fa-2x text-primary"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-4">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    @stack('scripts')
</body>
</html>
