@extends('layouts.app')

@section('title', 'LBDAIRY: SuperAdmin - System Settings')

@section('content')
<div class="container-fluid">
    <br><br><br><br>
    
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1>
            <i class="fas fa-cogs"></i>
            System Settings
        </h1>
        <p>Configure system parameters, security settings, and application preferences</p>
    </div>

    <!-- Settings Tabs -->
    <div class="row fade-in">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">
                                <i class="fas fa-cog"></i> General Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="security-tab" data-toggle="tab" href="#security" role="tab" aria-controls="security" aria-selected="false">
                                <i class="fas fa-shield-alt"></i> Security
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="notifications-tab" data-toggle="tab" href="#notifications" role="tab" aria-controls="notifications" aria-selected="false">
                                <i class="fas fa-bell"></i> Notifications
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="backup-tab" data-toggle="tab" href="#backup" role="tab" aria-controls="backup" aria-selected="false">
                                <i class="fas fa-database"></i> Backup & Maintenance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="logs-tab" data-toggle="tab" href="#logs" role="tab" aria-controls="logs" aria-selected="false">
                                <i class="fas fa-file-alt"></i> System Logs
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="settingsTabContent">
                        <!-- General Settings Tab -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <form id="generalSettingsForm" onsubmit="saveGeneralSettings(event)">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="mb-3">Application Settings</h5>
                                        <div class="form-group">
                                            <label for="appName">Application Name</label>
                                            <input type="text" class="form-control" id="appName" name="app_name" value="{{ config('app.name') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="appUrl">Application URL</label>
                                            <input type="url" class="form-control" id="appUrl" name="app_url" value="{{ config('app.url') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="timezone">Timezone</label>
                                            <select class="form-control" id="timezone" name="timezone">
                                                <option value="Asia/Manila" {{ config('app.timezone') == 'Asia/Manila' ? 'selected' : '' }}>Asia/Manila (GMT+8)</option>
                                                <option value="UTC" {{ config('app.timezone') == 'UTC' ? 'selected' : '' }}>UTC (GMT+0)</option>
                                                <option value="America/New_York" {{ config('app.timezone') == 'America/New_York' ? 'selected' : '' }}>America/New_York (GMT-5)</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="locale">Language</label>
                                            <select class="form-control" id="locale" name="locale">
                                                <option value="en" {{ config('app.locale') == 'en' ? 'selected' : '' }}>English</option>
                                                <option value="tl" {{ config('app.locale') == 'tl' ? 'selected' : '' }}>Tagalog</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="mb-3">System Configuration</h5>
                                        <div class="form-group">
                                            <label for="maintenanceMode">Maintenance Mode</label>
                                            <select class="form-control" id="maintenanceMode" name="maintenance_mode">
                                                <option value="0">Disabled</option>
                                                <option value="1">Enabled</option>
                                            </select>
                                            <small class="form-text text-muted">Enable to put the system in maintenance mode</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="debugMode">Debug Mode</label>
                                            <select class="form-control" id="debugMode" name="debug_mode">
                                                <option value="0" {{ !config('app.debug') ? 'selected' : '' }}>Disabled</option>
                                                <option value="1" {{ config('app.debug') ? 'selected' : '' }}>Enabled</option>
                                            </select>
                                            <small class="form-text text-muted">Enable for development debugging</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="sessionLifetime">Session Lifetime (minutes)</label>
                                            <input type="number" class="form-control" id="sessionLifetime" name="session_lifetime" value="{{ config('session.lifetime') }}" min="1" max="1440">
                                        </div>
                                        <div class="form-group">
                                            <label for="maxLoginAttempts">Max Login Attempts</label>
                                            <input type="number" class="form-control" id="maxLoginAttempts" name="max_login_attempts" value="5" min="1" max="10">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Save General Settings
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Security Settings Tab -->
                        <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                            <form id="securitySettingsForm" onsubmit="saveSecuritySettings(event)">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="mb-3">Password Policy</h5>
                                        <div class="form-group">
                                            <label for="minPasswordLength">Minimum Password Length</label>
                                            <input type="number" class="form-control" id="minPasswordLength" name="min_password_length" value="8" min="6" max="20">
                                        </div>
                                        <div class="form-group">
                                            <label for="requireUppercase">Require Uppercase Letters</label>
                                            <select class="form-control" id="requireUppercase" name="require_uppercase">
                                                <option value="1" selected>Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="requireNumbers">Require Numbers</label>
                                            <select class="form-control" id="requireNumbers" name="require_numbers">
                                                <option value="1" selected>Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="requireSpecialChars">Require Special Characters</label>
                                            <select class="form-control" id="requireSpecialChars" name="require_special_chars">
                                                <option value="1">Yes</option>
                                                <option value="0" selected>No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="mb-3">Security Features</h5>
                                        <div class="form-group">
                                            <label for="twoFactorAuth">Two-Factor Authentication</label>
                                            <select class="form-control" id="twoFactorAuth" name="two_factor_auth">
                                                <option value="0">Disabled</option>
                                                <option value="1">Optional</option>
                                                <option value="2">Required for Admins</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="sessionTimeout">Session Timeout (minutes)</label>
                                            <input type="number" class="form-control" id="sessionTimeout" name="session_timeout" value="30" min="5" max="480">
                                        </div>
                                        <div class="form-group">
                                            <label for="ipWhitelist">IP Whitelist</label>
                                            <textarea class="form-control" id="ipWhitelist" name="ip_whitelist" rows="3" placeholder="Enter IP addresses, one per line"></textarea>
                                            <small class="form-text text-muted">Leave empty to allow all IPs</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="failedLoginLockout">Failed Login Lockout (minutes)</label>
                                            <input type="number" class="form-control" id="failedLoginLockout" name="failed_login_lockout" value="15" min="5" max="1440">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Save Security Settings
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Notifications Tab -->
                        <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
                            <form id="notificationSettingsForm" onsubmit="saveNotificationSettings(event)">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="mb-3">Email Notifications</h5>
                                        <div class="form-group">
                                            <label for="smtpHost">SMTP Host</label>
                                            <input type="text" class="form-control" id="smtpHost" name="smtp_host" value="{{ config('mail.mailers.smtp.host') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="smtpPort">SMTP Port</label>
                                            <input type="number" class="form-control" id="smtpPort" name="smtp_port" value="{{ config('mail.mailers.smtp.port') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="smtpUsername">SMTP Username</label>
                                            <input type="text" class="form-control" id="smtpUsername" name="smtp_username" value="{{ config('mail.mailers.smtp.username') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="smtpPassword">SMTP Password</label>
                                            <input type="password" class="form-control" id="smtpPassword" name="smtp_password">
                                            <small class="form-text text-muted">Leave blank to keep current password</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="smtpEncryption">SMTP Encryption</label>
                                            <select class="form-control" id="smtpEncryption" name="smtp_encryption">
                                                <option value="tls" {{ config('mail.mailers.smtp.encryption') == 'tls' ? 'selected' : '' }}>TLS</option>
                                                <option value="ssl" {{ config('mail.mailers.smtp.encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                                <option value="none" {{ config('mail.mailers.smtp.encryption') == 'none' ? 'selected' : '' }}>None</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="mb-3">Notification Preferences</h5>
                                        <div class="form-group">
                                            <label for="adminNotifications">Admin Notifications</label>
                                            <select class="form-control" id="adminNotifications" name="admin_notifications">
                                                <option value="all">All Notifications</option>
                                                <option value="important">Important Only</option>
                                                <option value="none">None</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="farmerNotifications">Farmer Notifications</label>
                                            <select class="form-control" id="farmerNotifications" name="farmer_notifications">
                                                <option value="all">All Notifications</option>
                                                <option value="important">Important Only</option>
                                                <option value="none">None</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="systemAlerts">System Alerts</label>
                                            <select class="form-control" id="systemAlerts" name="system_alerts">
                                                <option value="immediate">Immediate</option>
                                                <option value="hourly">Hourly</option>
                                                <option value="daily">Daily</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="backupNotifications">Backup Notifications</label>
                                            <select class="form-control" id="backupNotifications" name="backup_notifications">
                                                <option value="success">On Success</option>
                                                <option value="failure">On Failure</option>
                                                <option value="both">Both</option>
                                                <option value="none">None</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Save Notification Settings
                                        </button>
                                        <button type="button" class="btn btn-info ml-2" onclick="testEmailConnection()">
                                            <i class="fas fa-paper-plane"></i> Test Email Connection
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Backup & Maintenance Tab -->
                        <div class="tab-pane fade" id="backup" role="tabpanel" aria-labelledby="backup-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Backup Settings</h5>
                                    <div class="form-group">
                                        <label for="backupFrequency">Backup Frequency</label>
                                        <select class="form-control" id="backupFrequency" name="backup_frequency">
                                            <option value="daily">Daily</option>
                                            <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="backupRetention">Backup Retention (days)</label>
                                        <input type="number" class="form-control" id="backupRetention" name="backup_retention" value="30" min="1" max="365">
                                    </div>
                                    <div class="form-group">
                                        <label for="backupLocation">Backup Location</label>
                                        <input type="text" class="form-control" id="backupLocation" name="backup_location" value="storage/backups">
                                    </div>
                                    <div class="form-group">
                                        <label for="includeFiles">Include Files</label>
                                        <select class="form-control" id="includeFiles" name="include_files">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="mb-3">Maintenance Tasks</h5>
                                    <div class="form-group">
                                        <label for="logRetention">Log Retention (days)</label>
                                        <input type="number" class="form-control" id="logRetention" name="log_retention" value="90" min="7" max="365">
                                    </div>
                                    <div class="form-group">
                                        <label for="tempFileCleanup">Temp File Cleanup</label>
                                        <select class="form-control" id="tempFileCleanup" name="temp_file_cleanup">
                                            <option value="daily">Daily</option>
                                            <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="databaseOptimization">Database Optimization</label>
                                        <select class="form-control" id="databaseOptimization" name="database_optimization">
                                            <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                                            <option value="never">Never</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="cacheClear">Cache Clear</label>
                                        <select class="form-control" id="cacheClear" name="cache_clear">
                                            <option value="daily">Daily</option>
                                            <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="button" class="btn btn-success" onclick="createBackup()">
                                        <i class="fas fa-download"></i> Create Backup Now
                                    </button>
                                    <button type="button" class="btn btn-warning ml-2" onclick="clearCache()">
                                        <i class="fas fa-broom"></i> Clear Cache
                                    </button>
                                    <button type="button" class="btn btn-info ml-2" onclick="optimizeDatabase()">
                                        <i class="fas fa-database"></i> Optimize Database
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- System Logs Tab -->
                        <div class="tab-pane fade" id="logs" role="tabpanel" aria-labelledby="logs-tab">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="mb-3">System Logs</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="logsTable">
                                            <thead>
                                                <tr>
                                                    <th>Timestamp</th>
                                                    <th>Level</th>
                                                    <th>Message</th>
                                                    <th>User</th>
                                                    <th>IP Address</th>
                                                </tr>
                                            </thead>
                                            <tbody id="logsTableBody">
                                                <!-- Logs will be loaded here -->
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-secondary" onclick="refreshLogs()">
                                            <i class="fas fa-sync"></i> Refresh Logs
                                        </button>
                                        <button type="button" class="btn btn-danger ml-2" onclick="clearLogs()">
                                            <i class="fas fa-trash"></i> Clear Logs
                                        </button>
                                        <button type="button" class="btn btn-info ml-2" onclick="exportLogs()">
                                            <i class="fas fa-download"></i> Export Logs
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Notification -->
<div id="settingsNotification" class="alert" style="display: none; position: fixed; top: 100px; right: 20px; z-index: 9999; min-width: 300px;"></div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // Load current settings
    loadCurrentSettings();
    
    // Load system logs
    loadSystemLogs();
});

function loadCurrentSettings() {
    // Load current system settings via AJAX
    $.ajax({
        url: '{{ route("superadmin.settings.get") }}',
        method: 'GET',
        success: function(response) {
            // Populate form fields with current values
            populateSettingsForm(response.data);
        },
        error: function(xhr) {
            console.error('Error loading settings:', xhr);
        }
    });
}

function populateSettingsForm(settings) {
    // Populate general settings
    if (settings.general) {
        $('#appName').val(settings.general.app_name || '');
        $('#appUrl').val(settings.general.app_url || '');
        $('#timezone').val(settings.general.timezone || 'Asia/Manila');
        $('#locale').val(settings.general.locale || 'en');
        $('#maintenanceMode').val(settings.general.maintenance_mode || '0');
        $('#debugMode').val(settings.general.debug_mode || '0');
        $('#sessionLifetime').val(settings.general.session_lifetime || 120);
        $('#maxLoginAttempts').val(settings.general.max_login_attempts || 5);
    }
    
    // Populate security settings
    if (settings.security) {
        $('#minPasswordLength').val(settings.security.min_password_length || 8);
        $('#requireUppercase').val(settings.security.require_uppercase || '1');
        $('#requireNumbers').val(settings.security.require_numbers || '1');
        $('#requireSpecialChars').val(settings.security.require_special_chars || '0');
        $('#twoFactorAuth').val(settings.security.two_factor_auth || '0');
        $('#sessionTimeout').val(settings.security.session_timeout || 30);
        $('#ipWhitelist').val(settings.security.ip_whitelist || '');
        $('#failedLoginLockout').val(settings.security.failed_login_lockout || 15);
    }
    
    // Populate notification settings
    if (settings.notifications) {
        $('#smtpHost').val(settings.notifications.smtp_host || '');
        $('#smtpPort').val(settings.notifications.smtp_port || 587);
        $('#smtpUsername').val(settings.notifications.smtp_username || '');
        $('#smtpEncryption').val(settings.notifications.smtp_encryption || 'tls');
        $('#adminNotifications').val(settings.notifications.admin_notifications || 'all');
        $('#farmerNotifications').val(settings.notifications.farmer_notifications || 'all');
        $('#systemAlerts').val(settings.notifications.system_alerts || 'immediate');
        $('#backupNotifications').val(settings.notifications.backup_notifications || 'both');
    }
    
    // Populate backup settings
    if (settings.backup) {
        $('#backupFrequency').val(settings.backup.frequency || 'daily');
        $('#backupRetention').val(settings.backup.retention || 30);
        $('#backupLocation').val(settings.backup.location || 'storage/backups');
        $('#includeFiles').val(settings.backup.include_files || '1');
        $('#logRetention').val(settings.backup.log_retention || 90);
        $('#tempFileCleanup').val(settings.backup.temp_file_cleanup || 'weekly');
        $('#databaseOptimization').val(settings.backup.database_optimization || 'weekly');
        $('#cacheClear').val(settings.backup.cache_clear || 'weekly');
    }
}

function saveGeneralSettings(event) {
    event.preventDefault();
    
    const formData = new FormData($('#generalSettingsForm')[0]);
    
    $.ajax({
        url: '{{ route("superadmin.settings.general") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            showNotification('General settings saved successfully!', 'success');
        },
        error: function(xhr) {
            showNotification('Error saving general settings', 'danger');
        }
    });
}

function saveSecuritySettings(event) {
    event.preventDefault();
    
    const formData = new FormData($('#securitySettingsForm')[0]);
    
    $.ajax({
        url: '{{ route("superadmin.settings.security") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            showNotification('Security settings saved successfully!', 'success');
        },
        error: function(xhr) {
            showNotification('Error saving security settings', 'danger');
        }
    });
}

function saveNotificationSettings(event) {
    event.preventDefault();
    
    const formData = new FormData($('#notificationSettingsForm')[0]);
    
    $.ajax({
        url: '{{ route("superadmin.settings.notifications") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            showNotification('Notification settings saved successfully!', 'success');
        },
        error: function(xhr) {
            showNotification('Error saving notification settings', 'danger');
        }
    });
}

function testEmailConnection() {
    $.ajax({
        url: '{{ route("superadmin.settings.test-email") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showNotification('Email connection test successful!', 'success');
        },
        error: function(xhr) {
            showNotification('Email connection test failed', 'danger');
        }
    });
}

function createBackup() {
    if (!confirm('Are you sure you want to create a backup now? This may take a few minutes.')) return;
    
    $.ajax({
        url: '{{ route("superadmin.settings.create-backup") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showNotification('Backup created successfully!', 'success');
        },
        error: function(xhr) {
            showNotification('Error creating backup', 'danger');
        }
    });
}

function clearCache() {
    if (!confirm('Are you sure you want to clear all cache?')) return;
    
    $.ajax({
        url: '{{ route("superadmin.settings.clear-cache") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showNotification('Cache cleared successfully!', 'success');
        },
        error: function(xhr) {
            showNotification('Error clearing cache', 'danger');
        }
    });
}

function optimizeDatabase() {
    if (!confirm('Are you sure you want to optimize the database? This may take a few minutes.')) return;
    
    $.ajax({
        url: '{{ route("superadmin.settings.optimize-database") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showNotification('Database optimized successfully!', 'success');
        },
        error: function(xhr) {
            showNotification('Error optimizing database', 'danger');
        }
    });
}

function loadSystemLogs() {
    $.ajax({
        url: '{{ route("superadmin.settings.logs") }}',
        method: 'GET',
        success: function(response) {
            const logsTableBody = document.getElementById('logsTableBody');
            logsTableBody.innerHTML = '';
            
            response.data.forEach(log => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${new Date(log.created_at).toLocaleString()}</td>
                    <td><span class="badge badge-${getLogLevelBadgeClass(log.level)}">${log.level}</span></td>
                    <td>${log.message}</td>
                    <td>${log.user ? log.user.name : 'System'}</td>
                    <td>${log.ip_address || 'N/A'}</td>
                `;
                logsTableBody.appendChild(row);
            });
        },
        error: function(xhr) {
            console.error('Error loading logs:', xhr);
        }
    });
}

function getLogLevelBadgeClass(level) {
    switch(level.toLowerCase()) {
        case 'emergency':
        case 'alert':
        case 'critical':
        case 'error': return 'danger';
        case 'warning': return 'warning';
        case 'notice':
        case 'info': return 'info';
        case 'debug': return 'secondary';
        default: return 'secondary';
    }
}

function refreshLogs() {
    loadSystemLogs();
    showNotification('Logs refreshed successfully!', 'success');
}

function clearLogs() {
    if (!confirm('Are you sure you want to clear all system logs? This action cannot be undone.')) return;
    
    $.ajax({
        url: '{{ route("superadmin.settings.clear-logs") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            loadSystemLogs();
            showNotification('Logs cleared successfully!', 'success');
        },
        error: function(xhr) {
            showNotification('Error clearing logs', 'danger');
        }
    });
}

function exportLogs() {
    window.open('{{ route("superadmin.settings.export-logs") }}', '_blank');
}

function showNotification(message, type) {
    const notification = document.getElementById('settingsNotification');
    notification.className = `alert alert-${type} alert-dismissible fade show`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'times-circle'}"></i>
        ${message}
        <button type="button" class="close" onclick="this.parentElement.style.display='none'">
            <span>&times;</span>
        </button>
    `;
    notification.style.display = 'block';
    
    setTimeout(() => {
        notification.style.display = 'none';
    }, 5000);
}
</script>
@endpush
