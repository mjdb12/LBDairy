@extends('layouts.app')

@section('title', 'LBDAIRY: SuperAdmin-Profile')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-user-shield"></i>
        Super Admin Profile
    </h1>
    <p>Manage your administrative profile and system settings</p>
</div>

<!-- Stats Cards -->
<div class="row fade-in stagger-animation">
    <!-- Total Admins -->
    <div class="col-12 col-sm-6 col-md-3 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Admins</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::where('role', 'admin')->count() }}</div>
                </div>
                <div class="icon text-info">
                    <i class="fas fa-user-shield fa-2x"></i>
                </div>
            </div>
            <a href="{{ route('superadmin.manage-admins') }}" class="card-footer text-info small d-flex justify-content-between align-items-center">
                View Admins <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- Active Admins -->
    <div class="col-12 col-sm-6 col-md-3 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Admins</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::where('role', 'admin')->where('is_active', true)->count() }}</div>
                </div>
                <div class="icon text-success">
                    <i class="fas fa-user-check fa-2x"></i>
                </div>
            </div>
            <a href="{{ route('superadmin.manage-admins') }}" class="card-footer text-success small d-flex justify-content-between align-items-center">
                View Active <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- Pending Requests -->
    <div class="col-12 col-sm-6 col-md-3 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Requests</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Issue::where('status', 'open')->count() }}</div>
                </div>
                <div class="icon text-warning">
                    <i class="fas fa-user-clock fa-2x"></i>
                </div>
            </div>
            <a href="{{ route('superadmin.manage-admins') }}" class="card-footer text-warning small d-flex justify-content-between align-items-center">
                View Requests <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- Years as Admin -->
    <div class="col-12 col-sm-6 col-md-3 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Years as Super Admin</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \Carbon\Carbon::parse(auth()->user()->created_at)->diffInYears(now()) }}</div>
                </div>
                <div class="icon text-primary">
                    <i class="fas fa-user-shield fa-2x"></i>
                </div>
            </div>
            <a href="{{ route('superadmin.profile') }}" class="card-footer text-primary small d-flex justify-content-between align-items-center">
                See Profile <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Profile Section -->
<div class="row fade-in">
    <!-- Profile Card -->
    <div class="col-12 col-md-5 col-lg-4 mb-4">
        <div class="profile-card-bordered">
            <div class="card shadow profile-card">
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
    </div>
    
    <!-- Profile Details -->
    <div class="col-12 col-md-7 col-lg-8 mb-4">
        <div class="card shadow h-100">
            <div class="card-header">
                <h6>
                    <i class="fas fa-user-edit"></i>
                    Profile Details
                </h6>
                <div class="action-buttons">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editProfileModal" title="Edit Profile">
                        <i class="fas fa-edit mr-2"></i>Edit Profile
                    </button>
                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#changePasswordModal" title="Change Password">
                        <i class="fas fa-key mr-2"></i>Change Password
                    </button>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
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
                                <td>Super Admin III</td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <i class="fas fa-map-marker-alt text-danger"></i>Farm Address
                                </th>
                                <td>{{ auth()->user()->address ?? 'Brgy. Palola, Lucban, Quezon' }}</td>
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
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form class="modal-content" method="POST" action="{{ route('superadmin.profile.update') }}">
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
              <input type="text" class="form-control" id="editPosition" name="position" value="Super Admin III" readonly>
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
              <input type="text" class="form-control" id="editFarmAddress" name="address" value="{{ auth()->user()->address ?? 'Brgy. Palola, Lucban, Quezon' }}">
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
    <form class="modal-content" method="POST" action="{{ route('superadmin.profile.password') }}">
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
    /* Profile Picture Enhancement */
    .profile-picture-container {
        position: relative;
        display: inline-block;
    }

    .img-profile {
        border: 6px solid white;
        box-shadow: var(--shadow-lg);
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .img-profile:hover {
        transform: scale(1.08);
        box-shadow: 0 1.5rem 4rem rgba(78, 115, 223, 0.25);
    }

    /* Profile Card Thick Blue Border */
    .profile-card-bordered {
        background: var(--primary-color);
        border-radius: 18px;
        padding: 18px;
        box-shadow: 0 4px 32px rgba(78, 115, 223, 0.10);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .profile-card {
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow);
        width: 100%;
        max-width: 420px;
        margin: 0 auto;
    }

    /* Stagger Animation */
    .stagger-animation .col-12 {
        animation: fadeInUp 0.6s ease-out;
    }
    
    .stagger-animation .col-12:nth-child(1) { animation-delay: 0.1s; }
    .stagger-animation .col-12:nth-child(2) { animation-delay: 0.2s; }
    .stagger-animation .col-12:nth-child(3) { animation-delay: 0.3s; }
    .stagger-animation .col-12:nth-child(4) { animation-delay: 0.4s; }

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
