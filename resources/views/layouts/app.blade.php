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
            overflow-x: hidden;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(180deg, var(--primary-color) 0%, #34495e 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1000;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.5);
        }
        
        .main-content {
            margin-left: 250px;
            padding: 0;
            min-height: 100vh;
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
        
        /* Dropdown Menu Styles */
        .nav-dropdown {
            position: relative;
        }
        
        .nav-dropdown > .nav-link {
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .nav-dropdown > .nav-link .dropdown-arrow {
            transition: transform 0.3s;
        }
        
        .nav-dropdown.show > .nav-link .dropdown-arrow {
            transform: rotate(180deg);
        }
        
        .nav-dropdown-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            padding-left: 15px;
        }
        
        .nav-dropdown.show .nav-dropdown-menu {
            max-height: 1000px;
            transition: max-height 0.5s ease-in;
        }
        
        .nav-dropdown-menu .nav-link {
            padding: 8px 20px;
            font-size: 0.9rem;
        }
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-menu-toggle {
                display: block !important;
                position: fixed;
                top: 15px;
                left: 15px;
                z-index: 1001;
                background: var(--primary-color);
                color: white;
                border: none;
                padding: 10px 15px;
                border-radius: 8px;
                cursor: pointer;
            }
        }
        
        .mobile-menu-toggle {
            display: none;
        }
        
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        
        @media (max-width: 768px) {
            .sidebar-overlay.show {
                display: block;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="p-3 text-center">
            <h4 class="text-white mb-0"><i class="fas fa-building"></i> SENA.ERP</h4>
            <small class="text-white-50">v1.0.0</small>
        </div>
        <hr class="text-white">
        <nav class="nav flex-column px-2">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
                    
                    <!-- HR Management Dropdown -->
                    <div class="nav-dropdown {{ request()->routeIs('hr.*') ? 'show' : '' }}">
                        <a class="nav-link {{ request()->routeIs('hr.*') ? 'active' : '' }}">
                            <span><i class="fas fa-users-cog"></i> HR Management</span>
                            <i class="fas fa-chevron-down dropdown-arrow"></i>
                        </a>
                        <div class="nav-dropdown-menu">
                            <!-- Basic HR -->
                            <div class="text-white-50 small px-3 mt-2 mb-1" style="font-size: 0.75rem;">BASIC</div>
                            <a class="nav-link {{ request()->routeIs('hr.employees.*') ? 'active' : '' }}" href="{{ route('hr.employees.index') }}">
                                <i class="fas fa-users"></i> Employees
                            </a>
                            <a class="nav-link {{ request()->routeIs('hr.departments.*') ? 'active' : '' }}" href="{{ route('hr.departments.index') }}">
                                <i class="fas fa-sitemap"></i> Departments
                            </a>
                            <a class="nav-link {{ request()->routeIs('hr.branches.*') ? 'active' : '' }}" href="{{ route('hr.branches.index') }}">
                                <i class="fas fa-code-branch"></i> Branches
                            </a>
                            <a class="nav-link {{ request()->routeIs('hr.designations.*') ? 'active' : '' }}" href="{{ route('hr.designations.index') }}">
                                <i class="fas fa-id-badge"></i> Designations
                            </a>
                            <a class="nav-link {{ request()->routeIs('hr.leaves.*') ? 'active' : '' }}" href="{{ route('hr.leaves.index') }}">
                                <i class="fas fa-calendar-alt"></i> Leave Management
                            </a>
                            
                            <!-- Recruitment -->
                            <div class="text-white-50 small px-3 mt-3 mb-1" style="font-size: 0.75rem;">RECRUITMENT</div>
                            <a class="nav-link {{ request()->routeIs('hr.recruitment.jobs.*') ? 'active' : '' }}" href="{{ route('hr.recruitment.jobs.index') }}">
                                <i class="fas fa-briefcase"></i> Job Postings
                            </a>
                            <a class="nav-link {{ request()->routeIs('hr.recruitment.applicants.*') ? 'active' : '' }}" href="{{ route('hr.recruitment.applicants.index') }}">
                                <i class="fas fa-user-plus"></i> Applicants
                            </a>
                            <a class="nav-link {{ request()->routeIs('hr.recruitment.interviews.*') ? 'active' : '' }}" href="{{ route('hr.recruitment.interviews.index') }}">
                                <i class="fas fa-comments"></i> Interviews
                            </a>
                            
                            <!-- Onboarding -->
                            <div class="text-white-50 small px-3 mt-3 mb-1" style="font-size: 0.75rem;">ONBOARDING</div>
                            <a class="nav-link {{ request()->routeIs('hr.onboarding.checklists.*') ? 'active' : '' }}" href="{{ route('hr.onboarding.checklists.index') }}">
                                <i class="fas fa-list-check"></i> Checklists
                            </a>
                            <a class="nav-link {{ request()->routeIs('hr.onboarding.employee-onboarding.*') ? 'active' : '' }}" href="{{ route('hr.onboarding.employee-onboarding.index') }}">
                                <i class="fas fa-user-check"></i> Employee Onboarding
                            </a>
                            
                            <!-- Shift Management -->
                            <div class="text-white-50 small px-3 mt-3 mb-1" style="font-size: 0.75rem;">SHIFT MANAGEMENT</div>
                            <a class="nav-link {{ request()->routeIs('hr.shifts.*') ? 'active' : '' }}" href="{{ route('hr.shifts.index') }}">
                                <i class="fas fa-business-time"></i> Shifts
                            </a>
                            <a class="nav-link {{ request()->routeIs('hr.shift-schedules.*') ? 'active' : '' }}" href="{{ route('hr.shift-schedules.index') }}">
                                <i class="fas fa-calendar-week"></i> Shift Schedules
                            </a>
                            
                            <!-- Performance -->
                            <div class="text-white-50 small px-3 mt-3 mb-1" style="font-size: 0.75rem;">PERFORMANCE</div>
                            <a class="nav-link {{ request()->routeIs('hr.performance.kpis.*') ? 'active' : '' }}" href="{{ route('hr.performance.kpis.index') }}">
                                <i class="fas fa-chart-line"></i> KPIs
                            </a>
                            <a class="nav-link {{ request()->routeIs('hr.performance.reviews.*') ? 'active' : '' }}" href="{{ route('hr.performance.reviews.index') }}">
                                <i class="fas fa-star"></i> Reviews
                            </a>
                            
                            <!-- Training -->
                            <div class="text-white-50 small px-3 mt-3 mb-1" style="font-size: 0.75rem;">TRAINING</div>
                            <a class="nav-link {{ request()->routeIs('hr.training.programs.*') ? 'active' : '' }}" href="{{ route('hr.training.programs.index') }}">
                                <i class="fas fa-graduation-cap"></i> Programs
                            </a>
                            <a class="nav-link {{ request()->routeIs('hr.training.enrollments.*') ? 'active' : '' }}" href="{{ route('hr.training.enrollments.index') }}">
                                <i class="fas fa-user-graduate"></i> Enrollments
                            </a>
                            <a class="nav-link {{ request()->routeIs('hr.training.skills.*') ? 'active' : '' }}" href="{{ route('hr.training.skills.index') }}">
                                <i class="fas fa-certificate"></i> Skills
                            </a>
                        </div>
                    </div>
                    
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
                    
                    <!-- Sales Manager Dropdown -->
                    <div class="nav-dropdown {{ request()->routeIs('sales.quotations.*') || request()->routeIs('sales.orders.*') || request()->routeIs('sales.pricing.*') || request()->routeIs('sales.contracts.*') || request()->routeIs('sales.delivery-notes.*') || request()->routeIs('sales.invoices.*') || request()->routeIs('sales.analytics.*') ? 'show' : '' }}">
                        <a class="nav-link {{ request()->routeIs('sales.quotations.*') || request()->routeIs('sales.orders.*') || request()->routeIs('sales.pricing.*') || request()->routeIs('sales.contracts.*') || request()->routeIs('sales.delivery-notes.*') || request()->routeIs('sales.invoices.*') || request()->routeIs('sales.analytics.*') ? 'active' : '' }}">
                            <span><i class="fas fa-shopping-cart"></i> Sales & Order Management</span>
                            <i class="fas fa-chevron-down dropdown-arrow"></i>
                        </a>
                        <div class="nav-dropdown-menu">
                            <a class="nav-link {{ request()->routeIs('sales.quotations.*') ? 'active' : '' }}" href="{{ route('sales.quotations.index') }}">
                                <i class="fas fa-file-alt"></i> Quotation Management
                            </a>
                            <a class="nav-link {{ request()->routeIs('sales.orders.*') ? 'active' : '' }}" href="{{ route('sales.orders.index') }}">
                                <i class="fas fa-shopping-bag"></i> Sales Orders
                            </a>
                            <a class="nav-link {{ request()->routeIs('sales.pricing.*') ? 'active' : '' }}" href="{{ route('sales.pricing.index') }}">
                                <i class="fas fa-tags"></i> Pricing & Discounts
                            </a>
                            <a class="nav-link {{ request()->routeIs('sales.contracts.*') ? 'active' : '' }}" href="{{ route('sales.contracts.index') }}">
                                <i class="fas fa-file-contract"></i> Sales Contracts
                            </a>
                            <a class="nav-link {{ request()->routeIs('sales.delivery-notes.*') ? 'active' : '' }}" href="{{ route('sales.delivery-notes.index') }}">
                                <i class="fas fa-truck"></i> Delivery Notes
                            </a>
                            <a class="nav-link {{ request()->routeIs('sales.invoices.*') ? 'active' : '' }}" href="{{ route('sales.invoices.index') }}">
                                <i class="fas fa-file-invoice-dollar"></i> Sales Invoicing
                            </a>
                            <a class="nav-link {{ request()->routeIs('sales.analytics.*') ? 'active' : '' }}" href="{{ route('sales.analytics.index') }}">
                                <i class="fas fa-chart-bar"></i> Sales Analytics
                            </a>
                        </div>
                    </div>
                    
                    <!-- Operation Manager Dropdown -->
                    <div class="nav-dropdown {{ request()->routeIs('sales.orders.*') || request()->routeIs('sales.delivery-notes.*') || request()->routeIs('sales.analytics.*') ? 'show' : '' }}">
                        <a class="nav-link {{ request()->routeIs('sales.orders.*') || request()->routeIs('sales.delivery-notes.*') || request()->routeIs('sales.analytics.*') ? 'active' : '' }}">
                            <span><i class="fas fa-cogs"></i> Operation Manager</span>
                            <i class="fas fa-chevron-down dropdown-arrow"></i>
                        </a>
                        <div class="nav-dropdown-menu">
                            <!-- Sales Orders Section -->
                            <div class="text-white-50 small px-3 mt-2 mb-1" style="font-size: 0.75rem;">SALES ORDERS</div>
                            <a class="nav-link {{ request()->routeIs('sales.orders.index') ? 'active' : '' }}" href="{{ route('sales.orders.index') }}">
                                <i class="fas fa-list"></i> View All Orders
                            </a>
                            <a class="nav-link {{ request()->routeIs('sales.orders.create') ? 'active' : '' }}" href="{{ route('sales.orders.create') }}">
                                <i class="fas fa-plus-circle"></i> Create Order
                            </a>
                            <a class="nav-link" href="{{ route('sales.orders.index') }}?status=Approved">
                                <i class="fas fa-check-circle"></i> Approved Orders
                            </a>
                            <a class="nav-link" href="{{ route('sales.orders.index') }}?status=Processing">
                                <i class="fas fa-sync"></i> Processing Orders
                            </a>
                            
                            <!-- Delivery Notes Section -->
                            <div class="text-white-50 small px-3 mt-3 mb-1" style="font-size: 0.75rem;">DELIVERY NOTES</div>
                            <a class="nav-link {{ request()->routeIs('sales.delivery-notes.index') ? 'active' : '' }}" href="{{ route('sales.delivery-notes.index') }}">
                                <i class="fas fa-list"></i> View All Deliveries
                            </a>
                            <a class="nav-link {{ request()->routeIs('sales.delivery-notes.create') ? 'active' : '' }}" href="{{ route('sales.delivery-notes.create') }}">
                                <i class="fas fa-plus-circle"></i> Create Delivery Note
                            </a>
                            <a class="nav-link" href="{{ route('sales.delivery-notes.index') }}?status=Ready">
                                <i class="fas fa-box"></i> Ready for Delivery
                            </a>
                            <a class="nav-link" href="{{ route('sales.delivery-notes.index') }}?status=In Transit">
                                <i class="fas fa-truck-moving"></i> In Transit
                            </a>
                            <a class="nav-link" href="{{ route('sales.delivery-notes.index') }}?status=Delivered">
                                <i class="fas fa-check-double"></i> Delivered
                            </a>
                            
                            <!-- Sales Analytics Section -->
                            <div class="text-white-50 small px-3 mt-3 mb-1" style="font-size: 0.75rem;">SALES ANALYTICS</div>
                            <a class="nav-link {{ request()->routeIs('sales.analytics.index') ? 'active' : '' }}" href="{{ route('sales.analytics.index') }}">
                                <i class="fas fa-chart-line"></i> Sales Dashboard
                            </a>
                            <a class="nav-link" href="{{ route('sales.analytics.index') }}?view=revenue">
                                <i class="fas fa-dollar-sign"></i> Revenue Analysis
                            </a>
                            <a class="nav-link" href="{{ route('sales.analytics.index') }}?view=customers">
                                <i class="fas fa-users"></i> Customer Analytics
                            </a>
                            <a class="nav-link" href="{{ route('sales.analytics.index') }}?view=trends">
                                <i class="fas fa-chart-area"></i> Sales Trends
                            </a>
                        </div>
                    </div>
                    
                    <!-- Accounts & Finance Dropdown -->
                    <div class="nav-dropdown {{ request()->routeIs('accounting.*') ? 'show' : '' }}">
                        <a class="nav-link {{ request()->routeIs('accounting.*') ? 'active' : '' }}">
                            <span><i class="fas fa-file-invoice-dollar"></i> Accounts & Finance</span>
                            <i class="fas fa-chevron-down dropdown-arrow"></i>
                        </a>
                        <div class="nav-dropdown-menu">
                            <a class="nav-link {{ request()->routeIs('accounting.chart-of-accounts.*') ? 'active' : '' }}" href="{{ route('accounting.chart-of-accounts.index') }}">
                                <i class="fas fa-list-alt"></i> Chart of Accounts
                            </a>
                            <a class="nav-link {{ request()->routeIs('accounting.vouchers.*') ? 'active' : '' }}" href="{{ route('accounting.vouchers.index') }}">
                                <i class="fas fa-file-invoice"></i> Vouchers
                            </a>
                            
                            <div class="text-white-50 small px-3 mt-3 mb-1" style="font-size: 0.75rem;">FINANCIAL REPORTS</div>
                            <a class="nav-link" href="{{ route('accounting.reports.trial-balance') }}">
                                <i class="fas fa-balance-scale"></i> Trial Balance
                            </a>
                            <a class="nav-link" href="{{ route('accounting.reports.profit-loss') }}">
                                <i class="fas fa-chart-line"></i> Profit & Loss
                            </a>
                            <a class="nav-link" href="{{ route('accounting.reports.balance-sheet') }}">
                                <i class="fas fa-file-invoice-dollar"></i> Balance Sheet
                            </a>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="main-content">
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
                                <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="fas fa-user me-2"></i> Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="fas fa-cog me-2"></i> Settings</a></li>
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
    
    <script>
        // Dropdown menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = document.querySelectorAll('.nav-dropdown > .nav-link');
            
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', function(e) {
                    e.preventDefault();
                    const parent = this.parentElement;
                    parent.classList.toggle('show');
                });
            });
            
            // Mobile menu toggle
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                });
                
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
