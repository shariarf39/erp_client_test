# Role-Based Access Control (RBAC) System

## Overview
The ERP system now includes a comprehensive role-based access control system that restricts user access to specific modules based on their assigned role.

## Available Roles

### 1. **Super Admin** 
- **Access**: Full system access to all modules
- **Username**: `superadmin`
- **Description**: Complete control over the entire system

### 2. **HR Manager** 
- **Access**: HR, Payroll, Attendance, Dashboard, Reports
- **Username**: `hrmanager`
- **Description**: Manages all HR-related operations including recruitment, onboarding, performance, training, leaves, payroll, and attendance

### 3. **Accountant** 
- **Access**: Accounts, Dashboard, Reports
- **Username**: `accountant`
- **Description**: Handles all accounting and financial operations

### 4. **Sales Manager** 
- **Access**: Sales, Dashboard, Reports
- **Username**: `salesmanager`
- **Description**: Manages sales operations including quotations, orders, contracts, delivery notes, invoicing, and analytics

### 5. **Operations Manager** 
- **Access**: Accounts, Sales, Inventory, Purchase, Dashboard, Reports
- **Username**: `operations`
- **Description**: Oversees multiple operational areas including accounts, sales, inventory management, and purchasing

## Module Permissions

Each role has specific permissions for different modules:

| Module | Super Admin | HR Manager | Accountant | Sales Manager | Operations Manager |
|--------|------------|------------|------------|---------------|-------------------|
| Dashboard | ✅ | ✅ | ✅ | ✅ | ✅ |
| HR | ✅ | ✅ | ❌ | ❌ | ❌ |
| Payroll | ✅ | ✅ | ❌ | ❌ | ❌ |
| Attendance | ✅ | ✅ | ❌ | ❌ | ❌ |
| Accounts | ✅ | ❌ | ✅ | ❌ | ✅ |
| Sales | ✅ | ❌ | ❌ | ✅ | ✅ |
| Inventory | ✅ | ❌ | ❌ | ❌ | ✅ |
| Purchase | ✅ | ❌ | ❌ | ❌ | ✅ |
| Reports | ✅ | ✅ | ✅ | ✅ | ✅ |
| Settings | ✅ | ❌ | ❌ | ❌ | ❌ |

## Implementation Details

### Database Schema
- **roles** table: Stores role definitions
- **permissions** table: Stores module and action permissions
- **role_permissions** pivot table: Links roles to permissions
- **users** table: Has `role_id` foreign key

### Permission Actions
Each module has 4 permission types:
- `view` - Can view module data
- `create` - Can create new records
- `edit` - Can modify existing records
- `delete` - Can delete records

### Helper Functions
- `canAccessModule($module, $action = 'view')` - Check if user has permission
- `getUserRole()` - Get current user's role name

### Middleware
- `module.permission` - Protect routes by module access

## Setup Instructions

### 1. Run Database Seeder
```bash
php artisan db:seed --class=RolePermissionSeeder
```

### 2. Assign Role to Users
```sql
-- Run this SQL or use the Admin Panel
UPDATE users SET role_id = 1 WHERE email = 'admin@example.com'; -- Super Admin
```

### 3. Access Admin Panel
Navigate to `/admin/users` to manage user roles (Super Admin only)

## Usage in Code

### Check Permission in Blade
```php
@if(canAccessModule('HR'))
    <!-- HR content -->
@endif
```

### Protect Routes with Middleware
```php
Route::middleware(['auth', 'module.permission:HR,view'])->group(function () {
    Route::resource('employees', EmployeeController::class);
});
```

### Check in Controller
```php
if (!auth()->user()->hasPermission('Sales', 'create')) {
    abort(403, 'Unauthorized');
}
```

## Navigation Menu
The sidebar menu automatically shows/hides modules based on user permissions. Users only see menu items for modules they have access to.

## Admin Panel
Super Admins can access the User Role Management panel at `/admin/users` to:
- View all users and their current roles
- Change user roles
- See role descriptions

## Security Notes
- Super Admin role cannot be deleted
- Users without roles default to no access
- All module routes should be protected with permissions
- Role changes take effect immediately (no re-login required)
