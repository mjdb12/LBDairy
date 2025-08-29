@extends('layouts.app')

@section('title', 'LBDAIRY: SuperAdmin - Manage Farmers')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-users"></i>
        Manage Farmers
    </h1>
    <p>Comprehensive farmer management and oversight dashboard</p>
</div>

<!-- Stats Cards -->
<div class="row fade-in stagger-animation">
    <!-- Total Farmers -->
    <div class="col-12 col-sm-6 col-md-3 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Farmers</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::where('role', 'farmer')->count() }}</div>
                </div>
                <div class="icon text-info">
                    <i class="fas fa-users fa-2x"></i>
                </div>
            </div>
            
        </div>
    </div>
    <!-- Active Farmers -->
    <div class="col-12 col-sm-6 col-md-3 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Farmers</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::where('role', 'farmer')->where('is_active', true)->count() }}</div>
                </div>
                <div class="icon text-success">
                    <i class="fas fa-user-check fa-2x"></i>
                </div>
            </div>
            
        </div>
    </div>
    <!-- Pending Approvals -->
    <div class="col-12 col-sm-6 col-md-3 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Approvals</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::where('role', 'farmer')->where('is_active', false)->count() }}</div>
                </div>
                <div class="icon text-warning">
                    <i class="fas fa-user-clock fa-2x"></i>
                </div>
            </div>
            
        </div>
    </div>
    <!-- New This Month -->
    <div class="col-12 col-sm-6 col-md-3 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">New This Month</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::where('role', 'farmer')->whereMonth('created_at', now()->month)->count() }}</div>
                </div>
                <div class="icon text-primary">
                    <i class="fas fa-user-plus fa-2x"></i>
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row fade-in">
    <!-- Farmers Table -->
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h6>
                    <i class="fas fa-list"></i>
                    Farmers Directory
                </h6>
                <div class="action-buttons">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addFarmerModal">
                        <i class="fas fa-plus mr-2"></i>Add Farmer
                    </button>
                    <button class="btn btn-success btn-sm">
                        <i class="fas fa-download mr-2"></i>Export
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="farmersTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Farm Count</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\User::where('role', 'farmer')->get() as $farmer)
                            <tr>
                                <td>{{ $farmer->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('img/' . ($farmer->profile_image ?? 'ronaldo.png')) }}" 
                                             alt="Profile" class="rounded-circle mr-2" width="32" height="32">
                                        <div>
                                            <div class="font-weight-bold">{{ $farmer->name }}</div>
                                            <small class="text-muted">{{ $farmer->address ?? 'No address' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $farmer->email }}</td>
                                <td>{{ $farmer->phone ?? 'No phone' }}</td>
                                <td>
                                    @if($farmer->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $farmer->farms->count() }}</td>
                                <td>{{ $farmer->created_at->format('M Y') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-action-view" onclick="viewFarmerDetails('{{ $farmer->id }}')" title="View Details">
                                            <i class="fas fa-eye"></i>
                                            <span>View</span>
                                        </button>
                                        <button class="btn-action btn-action-edit" onclick="editFarmer('{{ $farmer->id }}')" title="Edit">
                                            <i class="fas fa-edit"></i>
                                            <span>Edit</span>
                                        </button>
                                        @if($farmer->is_active)
                                            <button class="btn-action btn-action-toggle" onclick="toggleFarmerStatus('{{ $farmer->id }}', 'deactivate')" title="Deactivate">
                                                <i class="fas fa-user-slash"></i>
                                                <span>Deactivate</span>
                                            </button>
                                        @else
                                            <button class="btn-action btn-action-toggle" onclick="toggleFarmerStatus('{{ $farmer->id }}', 'activate')" title="Activate">
                                                <i class="fas fa-user-check"></i>
                                                <span>Activate</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Farmer Modal -->
<div class="modal fade" id="addFarmerModal" tabindex="-1" role="dialog" aria-labelledby="addFarmerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" id="addFarmerForm">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addFarmerLabel">
                    <i class="fas fa-user-plus mr-2"></i>Add New Farmer
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="addFarmerErrors" class="alert alert-danger" style="display:none;"></div>
                <div class="form-group">
                    <label for="farmerName">
                        <i class="fas fa-user"></i>Full Name
                    </label>
                    <input type="text" class="form-control" id="farmerName" name="name" required>
                </div>
                <div class="form-group">
                    <label for="farmerUsername">
                        <i class="fas fa-id-badge"></i>Username
                    </label>
                    <input type="text" class="form-control" id="farmerUsername" name="username" required>
                </div>
                <div class="form-group">
                    <label for="farmerEmail">
                        <i class="fas fa-envelope"></i>Email
                    </label>
                    <input type="email" class="form-control" id="farmerEmail" name="email" required>
                </div>
                <div class="form-group">
                    <label for="farmerPhone">
                        <i class="fas fa-phone"></i>Phone
                    </label>
                    <input type="text" class="form-control" id="farmerPhone" name="phone">
                </div>
                <div class="form-group">
                    <label for="farmerAddress">
                        <i class="fas fa-map-marker-alt"></i>Address
                    </label>
                    <textarea class="form-control" id="farmerAddress" name="address" rows="3"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="farmerPassword">
                            <i class="fas fa-key"></i>Password
                        </label>
                        <input type="password" class="form-control" id="farmerPassword" name="password" required minlength="8">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="farmerPasswordConfirmation">
                            <i class="fas fa-lock"></i>Confirm Password
                        </label>
                        <input type="password" class="form-control" id="farmerPasswordConfirmation" name="password_confirmation" required minlength="8">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>Add Farmer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Page Header Enhancement */
    .page-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: var(--shadow);
    }

    .page-header h1 {
        margin: 0;
        font-weight: 700;
        font-size: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
        font-size: 1.1rem;
    }

    /* Enhanced Stat Cards */
    .border-left-info {
        border-left: 4px solid var(--info-color) !important;
    }

    .border-left-success {
        border-left: 4px solid var(--success-color) !important;
    }

    .border-left-warning {
        border-left: 4px solid var(--warning-color) !important;
    }

    .border-left-primary {
        border-left: 4px solid var(--primary-color) !important;
    }

    .card-footer {
        background: rgba(0, 0, 0, 0.03);
        border-top: 1px solid var(--border-color);
        padding: 0.75rem 1.5rem;
        transition: all 0.2s ease;
    }

    .card-footer:hover {
        background: rgba(0, 0, 0, 0.05);
    }

    /* Enhanced Card Header */
    .card-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-bottom: none;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .card-header h6 {
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-header h6::before {
        content: '';
        width: 4px;
        height: 20px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 2px;
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

    /* Enhanced Table */
    .table thead th {
        background-color: #f8f9fc;
        border-top: none;
        border-bottom: 2px solid var(--border-color);
        font-weight: 600;
        color: var(--dark-color);
        padding: 1rem 0.75rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05);
    }

    /* Enhanced Badges */
    .badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-success {
        background: linear-gradient(135deg, var(--success-color) 0%, #17a673 100%);
    }

    .badge-warning {
        background: linear-gradient(135deg, var(--warning-color) 0%, #d69e2e 100%);
    }

    .badge-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    }

    .badge-info {
        background: linear-gradient(135deg, var(--info-color) 0%, #2c9faf 100%);
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#farmersTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'desc']],
            language: {
                search: "Search farmers:",
                lengthMenu: "Show _MENU_ farmers per page",
                info: "Showing _START_ to _END_ of _TOTAL_ farmers"
            }
        });

        // Add fade-in animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    });

    // Add Farmer submission
    const addFarmerForm = document.getElementById('addFarmerForm');
    if (addFarmerForm) {
        addFarmerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(addFarmerForm);
            fetch('{{ route("superadmin.farmers.store") }}', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async (r) => {
                let data = null;
                try { data = await r.json(); } catch (_) {}
                if (!r.ok) {
                    const errorsBox = document.getElementById('addFarmerErrors');
                    let message = data?.message || 'Failed to add farmer';
                    if (data?.errors) {
                        const list = Object.values(data.errors).map(arr => `<li>${arr[0]}</li>`).join('');
                        message = `<ul class="mb-0">${list}</ul>`;
                    }
                    errorsBox.innerHTML = message;
                    errorsBox.style.display = 'block';
                    return;
                }
                if (data?.success) {
                    document.getElementById('addFarmerErrors').style.display = 'none';
                    $('#addFarmerModal').modal('hide');
                    location.reload();
                } else {
                    const errorsBox = document.getElementById('addFarmerErrors');
                    errorsBox.textContent = data?.message || 'Failed to add farmer';
                    errorsBox.style.display = 'block';
                }
            })
            .catch(() => alert('Network error while adding farmer'));
        });
    }
</script>
@endpush
