@extends('layouts.app')

@section('title', 'LBDAIRY: Admin-Profile')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-user-circle"></i>
        Admin Profile
    </h1>
    <p>Manage your profile information and account settings</p>
</div>

<!-- Stats Cards -->
<div class="stats-container stagger-animation">
    <div class="stat-card border-left-info">
        <div class="stat-number text-info">{{ \App\Models\User::where('role', 'farmer')->count() }}</div>
        <div class="stat-label">Farmers Managed</div>
    </div>
    <div class="stat-card border-left-success">
        <div class="stat-number text-success">{{ \App\Models\Livestock::count() }}</div>
        <div class="stat-label">Livestock Records</div>
    </div>
    <div class="stat-card border-left-warning">
        <div class="stat-number text-warning">{{ \App\Models\Issue::where('status', 'open')->count() }}</div>
        <div class="stat-label">Issues Reported</div>
    </div>
    <div class="stat-card border-left-primary">
        <div class="stat-number text-primary">{{ \Carbon\Carbon::parse(auth()->user()->created_at)->diffInYears(now()) }}</div>
        <div class="stat-label">Years as Admin</div>
    </div>
</div>

<div class="row">
    <!-- Profile Card -->
    <div class="col-12 col-md-5 col-lg-4 mb-4">
        <div class="card shadow profile-card fade-in">
            <div class="card-body text-center">
                <div class="profile-picture-container">
                    <img id="profilePicture" src="{{ asset('img/' . (auth()->user()->profile_image ?? 'ronaldo.png')) }}" alt="Profile Picture" class="img-profile rounded-circle mb-3" style="width:120px;height:120px;object-fit:cover;">
                </div>
                <h5 class="font-weight-bold mb-1">{{ auth()->user()->name }}</h5>
                <p class="text-muted mb-3">{{ auth()->user()->email }}</p>
                <div class="d-flex justify-content-center">
                    <button class="btn btn-primary btn-sm" onclick="document.getElementById('uploadProfilePicture').click()">
                        <i class="fas fa-camera mr-2"></i>Change Picture
                    </button>
                </div>
                <input type="file" id="uploadProfilePicture" accept="image/*" style="display:none;" onchange="changeProfilePicture(event)">
            </div>
        </div>
    </div>
    
    <!-- Profile Details -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow h-100 fade-in">
            <div class="card-header">
                <h6>
                    <i class="fas fa-user-edit"></i>
                    Profile Details
                </h6>
                <div class="action-buttons">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editProfileModal" title="Edit Profile">
                        <i class="fas fa-edit mr-2"></i> Edit Profile
                    </button>
                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#changePasswordModal" title="Change Password">
                        <i class="fas fa-key mr-2"></i> Change Password
                    </button>
                </div>
            </div>
            
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <th scope="row" style="width:180px;">
                                <i class="fas fa-user text-primary"></i>Full Name
                            </th>
                            <td>{{ auth()->user()->name }}</td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <i class="fas fa-envelope text-info"></i>Email
                            </th>
                            <td>{{ auth()->user()->email }}</td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <i class="fas fa-phone text-success"></i>Phone
                            </th>
                            <td>{{ auth()->user()->phone ?? '+63 912 345 6789' }}</td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <i class="fas fa-user-shield text-warning"></i>Position
                            </th>
                            <td>Admin I</td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <i class="fas fa-map-marker-alt text-danger"></i>Farm Address
                            </th>
                            <td>{{ auth()->user()->address ?? 'Brgy. Tinamnan, Lucban, Quezon' }}</td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <i class="fas fa-calendar text-secondary"></i>Member Since
                            </th>
                            <td>{{ auth()->user()->created_at->format('F Y') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form class="modal-content" method="POST" action="{{ route('admin.profile.update') }}">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title" id="editProfileLabel">
            <i class="fas fa-user-edit mr-2"></i>Edit Profile
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <label for="editFullName">
                  <i class="fas fa-user"></i>Full Name
              </label>
              <input type="text" class="form-control" id="editFullName" name="name" value="{{ auth()->user()->name }}" required>
          </div>
          <div class="form-group">
              <label for="editEmail">
                  <i class="fas fa-envelope"></i>Email
              </label>
              <input type="email" class="form-control" id="editEmail" name="email" value="{{ auth()->user()->email }}" required>
          </div>
          <div class="form-group">
              <label for="editPosition">
                  <i class="fas fa-user-shield"></i>Position
              </label>
              <input type="text" class="form-control" id="editPosition" name="position" value="Admin I" readonly>
          </div>
          <div class="form-group">
              <label for="editPhone">
                  <i class="fas fa-phone"></i>Contact Number
              </label>
              <input type="text" class="form-control" id="editPhone" name="phone" value="{{ auth()->user()->phone ?? '+63 912 345 6789' }}">
          </div>
          <div class="form-group">
              <label for="editFarmAddress">
                  <i class="fas fa-map-marker-alt"></i>Address
              </label>
              <input type="text" class="form-control" id="editFarmAddress" name="address" value="{{ auth()->user()->address ?? 'Brgy. Tinamnan, Lucban, Quezon' }}">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-2"></i>Cancel
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-2"></i>Save Changes
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form class="modal-content" method="POST" action="{{ route('admin.profile.password') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="changePasswordLabel">
            <i class="fas fa-key mr-2"></i>Change Password
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <label for="currentPassword">
                  <i class="fas fa-lock"></i>Current Password
              </label>
              <input type="password" class="form-control" id="currentPassword" name="current_password" required>
          </div>
          <div class="form-group">
              <label for="newPassword">
                  <i class="fas fa-key"></i>New Password
              </label>
              <input type="password" class="form-control" id="newPassword" name="password" required>
          </div>
          <div class="form-group">
              <label for="confirmPassword">
                  <i class="fas fa-check-circle"></i>Confirm New Password
              </label>
              <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" required>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-2"></i>Cancel
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-2"></i>Change Password
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('styles')
<style>
    /* Enhanced Card Styling */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: var(--shadow);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }

    .card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-4px);
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-bottom: none;
        padding: 1.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }

    .card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }

    .card:hover .card-header::before {
        transform: translateX(100%);
    }

    .card-header h6 {
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        letter-spacing: 0.5px;
        position: relative;
        z-index: 1;
    }

    .card-header h6 i {
        padding: 0.5rem;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        backdrop-filter: blur(10px);
    }

    /* Enhanced Button Styling */
    .btn {
        border-radius: 10px;
        font-weight: 600;
        padding: 0.625rem 1.25rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        font-size: 0.875rem;
        position: relative;
        overflow: hidden;
        letter-spacing: 0.25px;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .btn:active {
        transform: translateY(0);
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        border-radius: 8px;
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
        box-shadow: 0 4px 15px rgba(28, 200, 138, 0.3);
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
        box-shadow: 0 4px 15px rgba(231, 74, 59, 0.3);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
        box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
    }

    .btn-warning {
        background: linear-gradient(135deg, var(--warning-color) 0%, var(--warning-dark) 100%);
        box-shadow: 0 4px 15px rgba(246, 194, 62, 0.3);
    }

    .btn-info {
        background: linear-gradient(135deg, var(--info-color) 0%, var(--info-dark) 100%);
        box-shadow: 0 4px 15px rgba(54, 185, 204, 0.3);
    }

    /* Enhanced Modal Styling */
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: var(--shadow-xl);
        backdrop-filter: blur(20px);
        background: rgba(255, 255, 255, 0.98);
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-bottom: none;
        border-radius: 16px 16px 0 0;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .modal-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    }

    .modal-title {
        font-weight: 700;
        font-size: 1.3rem;
        position: relative;
        z-index: 1;
    }

    .modal-body {
        padding: 2.5rem;
    }

    .modal-footer {
        border-top: 1px solid var(--border-color);
        padding: 2rem;
    }

    /* Page Header Enhancement */
    .page-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 3rem 2rem;
        border-radius: 16px;
        margin-bottom: 2.5rem;
        box-shadow: var(--shadow-lg);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        transform: rotate(45deg);
    }

    .page-header h1 {
        margin: 0;
        font-weight: 800;
        font-size: 2.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        position: relative;
        z-index: 1;
    }

    .page-header p {
        margin: 1rem 0 0 0;
        opacity: 0.9;
        font-size: 1.2rem;
        position: relative;
        z-index: 1;
    }

    /* Stats Cards Enhancement */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: var(--shadow);
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, rgba(78, 115, 223, 0.1) 0%, rgba(78, 115, 223, 0.05) 100%);
        border-radius: 50%;
        transform: translate(30px, -30px);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .stat-label {
        color: var(--dark-color);
        margin: 0.5rem 0 0 0;
        font-weight: 500;
        position: relative;
        z-index: 1;
    }

    .border-left-info {
        border-left: 5px solid var(--info-color) !important;
        position: relative;
        overflow: hidden;
    }

    .border-left-success {
        border-left: 5px solid var(--success-color) !important;
        position: relative;
        overflow: hidden;
    }

    .border-left-warning {
        border-left: 5px solid var(--warning-color) !important;
        position: relative;
        overflow: hidden;
    }

    .border-left-primary {
        border-left: 5px solid var(--primary-color) !important;
        position: relative;
        overflow: hidden;
    }

    /* Profile Picture Enhancement */
    .img-profile {
        border: 6px solid white;
        box-shadow: var(--shadow-lg);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .img-profile:hover {
        transform: scale(1.08);
        box-shadow: var(--shadow-xl);
    }

    /* Table Enhancement */
    .table {
        margin-bottom: 0;
        font-size: 0.95rem;
    }

    .table th {
        font-weight: 700;
        color: var(--dark-color);
        border-top: none;
        padding: 1.25rem 0.75rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table td {
        padding: 1.25rem 0.75rem;
        vertical-align: middle;
        border-top: 2px solid #f8f9fc;
        font-weight: 500;
        color: #2d3436;
    }

    /* Form Enhancement */
    .form-control {
        border-radius: 10px;
        border: 3px solid #e3e6f0;
        padding: 0.875rem 1.25rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 500;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        background: white;
        transform: translateY(-1px);
    }

    .form-group label {
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-group label i {
        margin-right: 0.5rem;
        width: 16px;
        text-align: center;
    }

    /* Stagger Animation */
    .stagger-animation .stat-card {
        animation: fadeInUp 0.6s ease-out;
    }
    
    .stagger-animation .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stagger-animation .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stagger-animation .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stagger-animation .stat-card:nth-child(4) { animation-delay: 0.4s; }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* CSS Variables */
    :root {
        --primary-color: #4e73df;
        --primary-dark: #3c5aa6;
        --primary-light: #6f8ae6;
        --success-color: #1cc88a;
        --success-dark: #17a673;
        --warning-color: #f6c23e;
        --warning-dark: #d69e2e;
        --danger-color: #e74a3b;
        --danger-dark: #c73e1d;
        --info-color: #36b9cc;
        --info-dark: #2c9faf;
        --light-color: #f8f9fc;
        --dark-color: #5a5c69;
        --border-color: #e3e6f0;
        --shadow-sm: 0 0.125rem 0.5rem rgba(58, 59, 69, 0.1);
        --shadow: 0 0.15rem 1.75rem rgba(58, 59, 69, 0.15);
        --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        --shadow-xl: 0 2rem 4rem rgba(0, 0, 0, 0.2);
        --border-radius: 12px;
        --border-radius-lg: 16px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --transition-fast: all 0.15s ease;
    }
</style>
@endpush

@push('scripts')
<script>
    function changeProfilePicture(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profilePicture').src = e.target.result;
                showNotification('Profile picture updated successfully!', 'success');
            };
            reader.readAsDataURL(file);
        }
    }

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} notification`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'times-circle'} mr-2"></i>
            ${message}
            <button type="button" class="close" onclick="this.parentElement.remove()">
                <span>&times;</span>
            </button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

    // Handle form submissions
    document.querySelector('#editProfileModal form').addEventListener('submit', function(e) {
        e.preventDefault();
        // Submit form via AJAX or let it submit normally
        this.submit();
    });

    document.querySelector('#changePasswordModal form').addEventListener('submit', function(e) {
        e.preventDefault();
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
        if (newPassword !== confirmPassword) {
            showNotification('Passwords do not match!', 'danger');
            return;
        }
        
        // Submit form
        this.submit();
    });
</script>
@endpush
