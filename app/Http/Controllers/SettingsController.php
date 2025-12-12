<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSetting;

class SettingsController extends Controller
{
    /**
     * Display the application settings.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Load user settings
        $settings = [
            'email_notifications' => UserSetting::get($user->id, 'email_notifications', '1'),
            'email_leave_requests' => UserSetting::get($user->id, 'email_leave_requests', '1'),
            'email_payroll' => UserSetting::get($user->id, 'email_payroll', '1'),
            'sms_notifications' => UserSetting::get($user->id, 'sms_notifications', '0'),
            'push_notifications' => UserSetting::get($user->id, 'push_notifications', '1'),
            'two_factor_enabled' => UserSetting::get($user->id, 'two_factor_enabled', '0'),
            'session_timeout' => UserSetting::get($user->id, 'session_timeout', '60'),
            'theme' => UserSetting::get($user->id, 'theme', 'light'),
            'language' => UserSetting::get($user->id, 'language', 'en'),
            'timezone' => UserSetting::get($user->id, 'timezone', 'Asia/Dhaka'),
            'date_format' => UserSetting::get($user->id, 'date_format', 'Y-m-d'),
            'items_per_page' => UserSetting::get($user->id, 'items_per_page', '25'),
        ];
        
        return view('settings.index', compact('user', 'settings'));
    }

    /**
     * Update notification settings.
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        
        // Checkboxes send '1' when checked, nothing when unchecked
        $settings = [
            'email_notifications' => $request->has('email_notifications') ? '1' : '0',
            'email_leave_requests' => $request->has('email_leave_requests') ? '1' : '0',
            'email_payroll' => $request->has('email_payroll') ? '1' : '0',
            'sms_notifications' => $request->has('sms_notifications') ? '1' : '0',
            'push_notifications' => $request->has('push_notifications') ? '1' : '0',
        ];

        foreach ($settings as $key => $value) {
            UserSetting::set($user->id, $key, $value);
        }
        
        return redirect()->route('settings.index')
            ->with('success', 'Notification settings updated successfully!');
    }

    /**
     * Update security settings.
     */
    public function updateSecurity(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'session_timeout' => ['required', 'integer', 'min:5', 'max:1440'],
        ]);

        UserSetting::set($user->id, 'two_factor_enabled', $request->has('two_factor_enabled') ? '1' : '0');
        UserSetting::set($user->id, 'session_timeout', $validated['session_timeout']);
        
        return redirect()->route('settings.index')
            ->with('success', 'Security settings updated successfully!');
    }

    /**
     * Update display preferences.
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'theme' => ['required', 'in:light,dark,auto'],
            'language' => ['required', 'string', 'max:10'],
            'timezone' => ['required', 'string', 'max:50'],
            'date_format' => ['required', 'string', 'max:20'],
            'items_per_page' => ['required', 'integer', 'in:10,25,50,100'],
        ]);

        foreach ($validated as $key => $value) {
            UserSetting::set($user->id, $key, $value);
        }
        
        return redirect()->route('settings.index')
            ->with('success', 'Preferences updated successfully!');
    }
}
