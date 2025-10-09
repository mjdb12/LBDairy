@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers-Profile')

@push('styles')
<style>
     /* User Details Modal Styling */
    #editProfileModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #editProfileModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #editProfileModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #editProfileModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #editProfileModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #editProfileModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #editProfileModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #editProfileModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }

    #changePasswordModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #changePasswordModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #changePasswordModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #changePasswordModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #changePasswordModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #changePasswordModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #changePasswordModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #changePasswordModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }

    /* Apply consistent buttons */
/* Action buttons styling */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        justify-content: center;
        min-width: 200px;
    }
    
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 0.25rem;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
        white-space: nowrap;
    }
    
    .btn-action-edit-profile {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }
    
        .btn-action-edit-profile:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }

    .btn-action-edit-pass {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
        .btn-action-edit-pass:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }

    .btn-action-oks {
        background-color: #18375d;
        border-color: #18375d;
        color: white;
    }
    .btn-action-oks:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }

    /* Custom styles for admin profile */
    
    /* Profile Picture Enhancement */
    .profile-picture-container {
        position: relative;
        display: inline-block;
    }

    .img-profile {
        border: 6px solid white;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .img-profile:hover {
        transform: scale(1.08);
        box-shadow: 0 1.5rem 4rem rgba(24, 55, 93, 0.25);
    }

    /* Profile Card Thick Blue Border */
    .profile-card-bordered {
        background: #18375d;
        border-radius: 18px;
        padding: 18px;
        box-shadow: 0 4px 32px rgba(24, 55, 93, 0.10);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .profile-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        width: 100%;
        max-width: 420px;
        margin: 0 auto;
    }

    /* Card Headers */
    .card-header {
        background: linear-gradient(135deg, #18375d 0%, #122a47 100%);
        color: white;
        border-bottom: none;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h6 {
        margin: 0;
        font-weight: 600;
        font-size: 1rem;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    /* -------------------------
   Profile Card (Left Column)
-------------------------- */
.profile-card {
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
    border: 1px solid #e3e6f0;
}

.profile-header {
    background: #ffffff;
    padding: 1.5rem 1rem;
}

.img-profile {
    width: 160px;
    height: 160px;
    object-fit: cover;
    border: 4px solid #18375d;
    transition: transform 0.2s ease;
}

.img-profile:hover {
    transform: scale(1.05);
}

/* Profile Picture Container */
.profile-picture-container {
    display: inline-block;
}

/* Camera Button Below Profile Picture */
.btn-action-ok {
    background: #18375d;
    color: #fff;
    border-radius: 50%; /* perfect circle */
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.25);
    transition: all 0.2s ease-in-out;
    border: none;
    margin: 0 auto;
}

.btn-action-ok:hover {
    background: #fca700;
    transform: scale(1.1); /* subtle pop effect */
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
}


/* Profile Actions */
.profile-actions .btn {
    margin: 0 0.25rem;
    font-size: 0.85rem;
    border-radius: 6px;
    transition: 0.2s;
}

.profile-actions .btn-outline-primary:hover {
    background: #18375d;
    color: #fff;
}

.profile-actions .btn-outline-secondary:hover {
    background: #18375d;
    color: #fff;
}

/* -------------------------
   Profile Info Table (Right Column)
-------------------------- */
.profile-info-table {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}

.profile-info-table th {
    color: #18375d;
    font-weight: 600;
    width: 200px;
    font-size: 0.9rem;
    padding: 1rem 0.75rem;
    border-bottom: 1px solid #e3e6f0;
    vertical-align: middle;
    white-space: nowrap;
}

.profile-info-table th i {
    margin-right: 0.5rem;
    width: 18px;
    text-align: center;
}

.profile-info-table td {
    color: #5a5c69;
    font-weight: 500;
    font-size: 0.95rem;
    padding: 1rem 0.75rem;
    border-bottom: 1px solid #e3e6f0;
    vertical-align: middle;
}

.profile-info-table tbody tr:hover {
    background-color: #f8f9fc;
    transition: background-color 0.2s ease;
}

.profile-info-table tbody tr:last-child td,
.profile-info-table tbody tr:last-child th {
    border-bottom: none;
}

/* Empty cell fallback */
.profile-info-table td:empty::after {
    content: "Not provided";
    color: #858796;
    font-style: italic;
}

/* -------------------------
   Custom Action Buttons (Details Header)
-------------------------- */
    .btn-action {
        font-size: 0.85rem;
        padding: 0.35rem 0.75rem;
        border-radius: 0.5rem;
        transition: 0.2s;
    }

    .btn-action-edit-profile {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }
    
    .btn-action-edit-profile:hover {
    background-color: #fca700;
    border-color: #fca700;
    color: white;
    }

    .btn-action-edit-pass {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }

    .btn-action-edit-pass:hover {
    background-color: #fca700;
    border-color: #fca700;
    color: white;
    }

.btn-action:hover {
    opacity: 0.85;
}
</style>
@endpush

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-2"></i>
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle mr-2"></i>
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle mr-2"></i>
    <strong>Please fix the following errors:</strong>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-user-circle"></i>
        Farmer Profile
    </h1>
    <p>Manage your personal information and farm details</p>
</div>
<!-- Statistics Grid -->
    <div class="row fade-in">
        <!-- Total Livestock -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Total Livestock</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ auth()->user()->livestock->count() }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-users fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Healthy Livestock -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Milk Production</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\ProductionRecord::where('farm_id', auth()->user()->farms->first()->id ?? 0)->whereMonth('production_date', now()->month)->sum('milk_quantity') }} <span class="text-xs">L/month</span></div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-heart fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Needs Attention -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Sales This Month</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format(\App\Models\Sale::where('farm_id', auth()->user()->farms->first()->id ?? 0)->whereMonth('sale_date', now()->month)->sum('total_amount'), 0) }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-exclamation-triangle fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Production Ready -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #18375d !important;">Account Age</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \Carbon\Carbon::parse(auth()->user()->created_at)->diffForHumans() }}</div>
                    </div>
                    <div class="icon" style="display: block !important; width: 60px; height: 60px; text-align: center; line-height: 60px;">
                        <i class="fas fa-tint fa-2x" style="color: #18375d !important; display: inline-block !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Profile Section -->
<div class="row fade-in">
    <!-- Profile Card -->
    <div class="col-12 col-md-5 col-lg-4 mb-4">
        <div class="card shadow profile-card h-100">
            <!-- Profile Header -->
            <div class="profile-header text-center p-4">
                <div class="profile-picture-container d-inline-block">
                    <img id="profilePicture" 
                        src="{{ asset('img/' . (auth()->user()->profile_image ?? 'ronaldo.png')) }}?t={{ time() }}" 
                        alt="Profile Picture" 
                        class="img-profile rounded-circle shadow">
                </div>
                
                <!-- Camera Button Below Profile Picture -->
                <div class="mt-3">
                    <button class="btn-action btn-action-ok" onclick="document.getElementById('uploadProfilePicture').click()">
                        <i class="fas fa-camera"></i>
                    </button>
                    <input type="file" id="uploadProfilePicture" accept="image/*" style="display:none;" onchange="changeProfilePicture(event)">
                </div>

                <h3 class="mt-3 mb-1 font-weight-bold">{{ auth()->user()->name }}</h3>
                <p class="text-muted mb-2">{{ auth()->user()->email }}</p>
                <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="btn-action btn-action-edit-profile" data-toggle="modal" data-target="#editProfileModal" title="Edit Profile">
                    <i class="fas fa-edit mr-1"></i>Edit Profile
                </button>
                <button class="btn-action btn-action-edit-pass" data-toggle="modal" data-target="#changePasswordModal" title="Change Password">
                    <i class="fas fa-key mr-2"></i>Change Password
                </button>
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
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0 profile-info-table">
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <i class="fas fa-user" style="color: #18375d;"></i>Full Name
                                </th>
                                <td>{{ auth()->user()->name ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <i class="fas fa-envelope" style="color: #18375d;"></i>Email
                                </th>
                                <td>{{ auth()->user()->email ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <i class="fas fa-phone" style="color: #18375d;"></i>Phone
                                </th>
                                <td>{{ auth()->user()->phone ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <i class="fas fa-seedling" style="color: #18375d;"></i>Farm Name
                                </th>
                                <td>{{ auth()->user()->farms->first()->name ?? 'No farm registered' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <i class="fas fa-map-marker-alt" style="color: #18375d;"></i>Address
                                </th>
                                <td>{{ auth()->user()->address ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <i class="fas fa-calendar-alt" style="color: #18375d;"></i>Member Since
                                </th>
                                <td>{{ auth()->user()->created_at->format('F Y') }}</td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <i class="fas fa-check-circle" style="color: #18375d;"></i>Status
                                </th>
                                <td><span class="badge badge-success">{{ ucfirst(auth()->user()->status ?? 'Active') }}</span></td>
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
    <form class="modal-content" method="POST" action="{{ route('farmer.profile.update') }}" id="editProfileForm">
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
                  <i class="fas fa-user mr-2"></i>Full Name
              <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="editFullName" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required placeholder="Enter your full name">
              @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>
          <div class="form-group">
              <label for="editEmail">
                  <i class="fas fa-envelope mr-2"></i>Email
              <span class="text-danger">*</span></label>
              <input type="email" class="form-control @error('email') is-invalid @enderror" id="editEmail" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required placeholder="Enter your email address">
              @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>
          <div class="form-group">
              <label for="editPhone">
                  <i class="fas fa-phone mr-2"></i>Contact Number
              <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('phone') is-invalid @enderror" id="editPhone" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" placeholder="Enter your contact number">
              @error('phone')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>
          <div class="form-group">
              <label for="editFarmName">
                  <i class="fas fa-seedling mr-2"></i>Farm Name
              <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('farm_name') is-invalid @enderror" id="editFarmName" name="farm_name" value="{{ old('farm_name', auth()->user()->farms->first()->name ?? '') }}" placeholder="Enter your farm name">
              @error('farm_name')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>
          <div class="form-group">
              <label for="editAddress">
                  <i class="fas fa-map-marker-alt mr-2"></i>Address
              <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('address') is-invalid @enderror" id="editAddress" name="address" value="{{ old('address', auth()->user()->address ?? '') }}" placeholder="Enter your address">
              @error('address')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-action btn-secondary" data-dismiss="modal">
            Cancel
        </button>
        <button type="submit" class="btn-action btn-action-oks">
            Save Changes
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form class="modal-content" method="POST" action="{{ route('farmer.profile.password') }}" id="changePasswordForm">
      @csrf
      @method('PUT')
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
                  <i class="fas fa-lock mr-2"></i>Current Password
              <span class="text-danger">*</span></label>
              <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="currentPassword" name="current_password" required>
              @error('current_password')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>
          <div class="form-group">
              <label for="newPassword">
                  <i class="fas fa-key mr-2"></i>New Password
              <span class="text-danger">*</span></label>
              <input type="password" class="form-control @error('password') is-invalid @enderror" id="newPassword" name="password" required>
              @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>
          <div class="form-group">
              <label for="confirmPassword">
                  <i class="fas fa-check-circle mr-2"></i>Confirm New Password
              <span class="text-danger">*</span></label>
              <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" required>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-action btn-secondary" data-dismiss="modal">
            Cancel
        </button>
        <button type="submit" class="btn-action btn-action-oks">
            Change Password
        </button>
      </div>
    </form>
  </div>
</div>
@endsection


@push('scripts')
<script>
    
    function changeProfilePicture(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Show loading state
        const button = event.target.previousElementSibling;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;

        // Create FormData
        const formData = new FormData();
        formData.append('profile_picture', file);
        formData.append('_token', '{{ csrf_token() }}');

        // Upload via AJAX
        fetch('{{ route("farmer.profile.picture") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the profile picture with cache busting
                document.getElementById('profilePicture').src = data.image_url + '?t=' + new Date().getTime();
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error uploading profile picture', 'danger');
        })
        .finally(() => {
            // Reset button state
            button.innerHTML = originalText;
            button.disabled = false;
            // Clear the file input
            event.target.value = '';
        });
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
    document.querySelector('#editProfileForm').addEventListener('submit', function(e) {
        // Let the form submit normally
    });

    document.querySelector('#changePasswordForm').addEventListener('submit', function(e) {
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            showNotification('Passwords do not match!', 'danger');
            return;
        }
    });
</script>
@endpush
