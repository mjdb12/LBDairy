@extends('layouts.app')

@section('title', 'User Details - LBDAIRY')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Details</h1>
        <div>
            <a href="{{ route('superadmin.users.edit', $user->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit User
            </a>
            <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Profile</h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" 
                             class="rounded-circle img-profile" style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                    <h4 class="font-weight-bold">{{ $user->name }}</h4>
                    <p class="text-muted">{{ ucfirst($user->role) }}</p>
                    
                    <div class="mt-4">
                        <span class="badge badge-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Contact Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="font-weight-bold mb-1">Email</h6>
                        <p class="mb-0">
                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                        </p>
                    </div>
                    
                    @if($user->phone)
                    <div class="mb-3">
                        <h6 class="font-weight-bold mb-1">Phone</h6>
                        <p class="mb-0">
                            <a href="tel:{{ $user->phone }}">{{ $user->phone }}</a>
                        </p>
                    </div>
                    @endif
                    
                    @if($user->address)
                    <div>
                        <h6 class="font-weight-bold mb-1">Address</h6>
                        <p class="mb-0">{{ $user->address }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="font-weight-bold mb-1">User ID</h6>
                                <p class="mb-0">{{ $user->id }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="font-weight-bold mb-1">Account Status</h6>
                                <p class="mb-0">
                                    <span class="badge badge-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="font-weight-bold mb-1">Role</h6>
                                <p class="mb-0">{{ ucfirst($user->role) }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="font-weight-bold mb-1">Email Verified</h6>
                                <p class="mb-0">
                                    @if($user->email_verified_at)
                                        <span class="text-success">
                                            <i class="fas fa-check-circle"></i> Verified on {{ $user->email_verified_at->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-danger">
                                            <i class="fas fa-times-circle"></i> Not Verified
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="font-weight-bold mb-1">Last Login</h6>
                                <p class="mb-0">
                                    {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never logged in' }}
                                    @if($user->last_login_ip)
                                        <br><small class="text-muted">IP: {{ $user->last_login_ip }}</small>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="font-weight-bold mb-1">Account Created</h6>
                                <p class="mb-0">
                                    {{ $user->created_at->format('M d, Y') }}
                                    <small class="text-muted">({{ $user->created_at->diffForHumans() }})</small>
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($user->notes)
                    <div class="mb-3">
                        <h6 class="font-weight-bold mb-1">Notes</h6>
                        <div class="border rounded p-3 bg-light">
                            {!! nl2br(e($user->notes)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Activity Log</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" 
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" 
                             aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Filter Logs:</div>
                            <a class="dropdown-item" href="#">All Activities</a>
                            <a class="dropdown-item" href="#">Logins</a>
                            <a class="dropdown-item" href="#">Profile Updates</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Export Logs</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="activity-feed">
                        @if($activities && count($activities) > 0)
                            @foreach($activities as $activity)
                                <div class="activity-item d-flex">
                                    <div class="activity-bullet">
                                        <i class="fas fa-circle"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h6 class="font-weight-bold mb-1">{{ $activity->description }}</h6>
                                        <p class="text-muted small mb-0">
                                            {{ $activity->created_at->diffForHumans() }}
                                            @if($activity->properties && isset($activity->properties['attributes']))
                                                <br>
                                                @foreach($activity->properties['attributes'] as $key => $value)
                                                    <span class="badge badge-info">{{ $key }}: {{ $value }}</span>
                                                @endforeach
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-history fa-3x text-gray-300 mb-3"></i>
                                <p class="text-muted">No activity found for this user.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
                <p class="font-weight-bold">{{ $user->name }}</p>
                <p class="text-muted">{{ $user->email }}</p>
                
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Warning:</strong> This will permanently delete the user account and all associated data.
                </div>
                
                <form id="deleteUserForm" action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <div class="form-group mt-4">
                        <label for="delete_confirmation">Type "DELETE" to confirm:</label>
                        <input type="text" class="form-control" id="delete_confirmation" name="delete_confirmation" 
                               required pattern="DELETE">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" form="deleteUserForm" class="btn btn-danger">
                    <i class="fas fa-trash-alt"></i> Delete User
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .activity-feed {
        padding: 15px;
    }
    .activity-item {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }
    .activity-item:last-child {
        border-bottom: none;
    }
    .activity-bullet {
        padding-right: 15px;
        color: #4e73df;
        font-size: 10px;
        padding-top: 5px;
    }
    .activity-content {
        flex: 1;
    }
    .img-profile {
        border: 5px solid #f8f9fc;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
</style>
@endpush

@push('scripts')
<script>
    // Delete confirmation
    $(document).ready(function() {
        $('#deleteUserForm').on('submit', function(e) {
            if ($('#delete_confirmation').val() !== 'DELETE') {
                e.preventDefault();
                alert('Please type "DELETE" to confirm deletion.');
                return false;
            }
            return confirm('Are you absolutely sure you want to delete this user? This cannot be undone.');
        });
    });
</script>
@endpush
