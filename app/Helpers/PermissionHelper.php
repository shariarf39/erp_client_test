<?php

use App\Models\User;

if (!function_exists('canAccessModule')) {
    /**
     * Check if user can access a module
     */
    function canAccessModule($module, $action = 'view')
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }
        
        // Super Admin has access to everything
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        return $user->hasPermission($module, $action);
    }
}

if (!function_exists('getUserRole')) {
    /**
     * Get user's role name
     */
    function getUserRole()
    {
        $user = auth()->user();
        return $user && $user->role ? $user->role->name : 'Guest';
    }
}
