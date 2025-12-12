@extends('layouts.app')

@section('title', 'Settings - SENA.ERP')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-cog me-2"></i>Settings</h2>
            <p class="text-muted">Manage your application preferences and security settings</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <!-- Settings Tabs -->
            <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="notifications-tab" data-bs-toggle="tab" 
                            data-bs-target="#notifications" type="button" role="tab">
                        <i class="fas fa-bell me-2"></i>Notifications
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="security-tab" data-bs-toggle="tab" 
                            data-bs-target="#security" type="button" role="tab">
                        <i class="fas fa-shield-alt me-2"></i>Security
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="preferences-tab" data-bs-toggle="tab" 
                            data-bs-target="#preferences" type="button" role="tab">
                        <i class="fas fa-paint-brush me-2"></i>Preferences
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="settingsTabContent">
                <!-- Notifications Settings -->
                <div class="tab-pane fade show active" id="notifications" role="tabpanel">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Notification Preferences</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.notifications') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-4">
                                    <h6 class="mb-3">Email Notifications</h6>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="email_notifications" 
                                               name="email_notifications" value="1" {{ $settings['email_notifications'] == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email_notifications">
                                            Receive email notifications for important updates
                                        </label>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="email_leave_requests" 
                                               name="email_leave_requests" value="1" {{ $settings['email_leave_requests'] == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email_leave_requests">
                                            Leave request notifications
                                        </label>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="email_payroll" 
                                               name="email_payroll" value="1" {{ $settings['email_payroll'] == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email_payroll">
                                            Payroll processing notifications
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <h6 class="mb-3">SMS Notifications</h6>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="sms_notifications" 
                                               name="sms_notifications" value="1" {{ $settings['sms_notifications'] == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sms_notifications">
                                            Enable SMS notifications
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <h6 class="mb-3">Push Notifications</h6>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="push_notifications" 
                                               name="push_notifications" value="1" {{ $settings['push_notifications'] == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="push_notifications">
                                            Enable browser push notifications
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Save Notification Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="tab-pane fade" id="security" role="tabpanel">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Security Settings</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.security') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-4">
                                    <h6 class="mb-3">Two-Factor Authentication</h6>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="two_factor_enabled" 
                                               name="two_factor_enabled" value="1" {{ $settings['two_factor_enabled'] == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="two_factor_enabled">
                                            Enable two-factor authentication (2FA)
                                        </label>
                                    </div>
                                    <p class="text-muted small">Add an extra layer of security to your account by requiring a verification code in addition to your password.</p>
                                </div>
                                
                                <div class="mb-4">
                                    <h6 class="mb-3">Session Management</h6>
                                    <div class="mb-3">
                                        <label for="session_timeout" class="form-label">Session Timeout (minutes)</label>
                                        <select class="form-select" id="session_timeout" name="session_timeout">
                                            <option value="15" {{ $settings['session_timeout'] == '15' ? 'selected' : '' }}>15 minutes</option>
                                            <option value="30" {{ $settings['session_timeout'] == '30' ? 'selected' : '' }}>30 minutes</option>
                                            <option value="60" {{ $settings['session_timeout'] == '60' ? 'selected' : '' }}>1 hour</option>
                                            <option value="120" {{ $settings['session_timeout'] == '120' ? 'selected' : '' }}>2 hours</option>
                                            <option value="240" {{ $settings['session_timeout'] == '240' ? 'selected' : '' }}>4 hours</option>
                                            <option value="480" {{ $settings['session_timeout'] == '480' ? 'selected' : '' }}>8 hours</option>
                                        </select>
                                        <small class="text-muted">Automatically log out after period of inactivity</small>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <h6 class="mb-3">Active Sessions</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Device</th>
                                                    <th>Location</th>
                                                    <th>Last Active</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><i class="fas fa-desktop me-2"></i>Current Device</td>
                                                    <td>Dhaka, Bangladesh</td>
                                                    <td>Just now</td>
                                                    <td><span class="badge bg-success">Active</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Save Security Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Preferences Settings -->
                <div class="tab-pane fade" id="preferences" role="tabpanel">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0"><i class="fas fa-paint-brush me-2"></i>Display Preferences</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.preferences') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="theme" class="form-label">Theme</label>
                                        <select class="form-select" id="theme" name="theme">
                                            <option value="light" {{ $settings['theme'] == 'light' ? 'selected' : '' }}>Light</option>
                                            <option value="dark" {{ $settings['theme'] == 'dark' ? 'selected' : '' }}>Dark</option>
                                            <option value="auto" {{ $settings['theme'] == 'auto' ? 'selected' : '' }}>Auto (System)</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-4">
                                        <label for="language" class="form-label">Language</label>
                                        <select class="form-select" id="language" name="language">
                                            <option value="en" {{ $settings['language'] == 'en' ? 'selected' : '' }}>English</option>
                                            <option value="bn" {{ $settings['language'] == 'bn' ? 'selected' : '' }}>বাংলা (Bangla)</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="timezone" class="form-label">Timezone</label>
                                        <select class="form-select" id="timezone" name="timezone">
                                            <option value="Asia/Dhaka" {{ $settings['timezone'] == 'Asia/Dhaka' ? 'selected' : '' }}>Asia/Dhaka (GMT+6)</option>
                                            <option value="Asia/Kolkata" {{ $settings['timezone'] == 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata (GMT+5:30)</option>
                                            <option value="UTC" {{ $settings['timezone'] == 'UTC' ? 'selected' : '' }}>UTC (GMT+0)</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-4">
                                        <label for="date_format" class="form-label">Date Format</label>
                                        <select class="form-select" id="date_format" name="date_format">
                                            <option value="Y-m-d" {{ $settings['date_format'] == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                            <option value="d/m/Y" {{ $settings['date_format'] == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                            <option value="m/d/Y" {{ $settings['date_format'] == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                            <option value="d-M-Y" {{ $settings['date_format'] == 'd-M-Y' ? 'selected' : '' }}>DD-MMM-YYYY</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="items_per_page" class="form-label">Items Per Page</label>
                                    <select class="form-select" id="items_per_page" name="items_per_page" style="max-width: 200px;">
                                        <option value="10" {{ $settings['items_per_page'] == '10' ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ $settings['items_per_page'] == '25' ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ $settings['items_per_page'] == '50' ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ $settings['items_per_page'] == '100' ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>
                                
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Save Preferences
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
