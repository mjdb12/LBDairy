@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers - My Farms')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-home"></i>
        My Farms
    </h1>
    <p>Manage and monitor your farm operations</p>
</div>

<!-- Add Farm Button -->
<div class="row mb-4">
    <div class="col-12">
        <button class="btn btn-primary btn-lg" onclick="openAddFarmModal()">
            <i class="fas fa-plus"></i> Add New Farm
        </button>
    </div>
</div>

<div class="row">
    @forelse($farms as $farm)
    <div class="col-lg-6 col-xl-4 mb-4">
        <div class="card shadow h-100 fade-in">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-home"></i>
                    {{ $farm->name }}
                </h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Location</small>
                        <p class="mb-0">{{ $farm->location ?? 'Not specified' }}</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Size</small>
                        <p class="mb-0">{{ $farm->size ? $farm->size . ' hectares' : 'Not specified' }}</p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Status</small>
                        <span class="badge badge-{{ $farm->status === 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($farm->status ?? 'Unknown') }}
                        </span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Livestock Count</small>
                        <p class="mb-0">{{ $farm->livestock->count() ?? 0 }}</p>
                    </div>
                </div>

                @if($farm->description)
                <div class="mb-3">
                    <small class="text-muted">Description</small>
                    <p class="mb-0">{{ Str::limit($farm->description, 100) }}</p>
                </div>
                @endif

                <div class="d-flex justify-content-between">
                    <a href="{{ route('farmer.farm-details', $farm->id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> View Details
                    </a>
                    <a href="{{ route('farmer.farm-analysis') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-chart-line"></i> Analysis
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card shadow fade-in">
            <div class="card-body text-center py-5">
                <i class="fas fa-home fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Farms Found</h5>
                <p class="text-muted">You haven't registered any farms yet.</p>
                <p class="text-muted">Contact an administrator to set up your farm.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Farm Summary -->
@if($farms->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow fade-in">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-pie"></i>
                    Farm Summary
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="border-right">
                            <h4 class="text-primary">{{ $farms->count() }}</h4>
                            <small class="text-muted">Total Farms</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="border-right">
                            <h4 class="text-success">{{ $farms->where('status', 'active')->count() }}</h4>
                            <small class="text-muted">Active Farms</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="border-right">
                            <h4 class="text-info">{{ $farms->sum(function($farm) { return $farm->livestock->count(); }) }}</h4>
                            <small class="text-muted">Total Livestock</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div>
                            <h4 class="text-warning">{{ number_format($farms->sum('size'), 1) }}</h4>
                            <small class="text-muted">Total Hectares</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- Add Farm Modal -->
<div class="modal fade" id="addFarmModal" tabindex="-1" role="dialog" aria-labelledby="addFarmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFarmModalLabel">
                    <i class="fas fa-plus"></i>
                    Add New Farm
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addFarmForm" method="POST" action="{{ route('farmer.farms.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Farm Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status *</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location">Location *</label>
                                <input type="text" class="form-control" id="location" name="location" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="size">Size (hectares)</label>
                                <input type="number" class="form-control" id="size" name="size" min="0" step="0.01">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Farm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openAddFarmModal() {
    $('#addFarmModal').modal('show');
}

// Handle form submission
$('#addFarmForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showToast('Farm created successfully!', 'success');
                $('#addFarmModal').modal('hide');
                location.reload();
            } else {
                showToast(response.message || 'Error creating farm', 'error');
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(field => {
                    showToast(errors[field][0], 'error');
                });
            } else {
                showToast('Error creating farm', 'error');
            }
        }
    });
});

function showToast(message, type = 'info') {
    // Create a simple alert instead of Bootstrap 5 toast for Bootstrap 4 compatibility
    const alertClass = type === 'error' ? 'alert-danger' : type === 'success' ? 'alert-success' : 'alert-info';
    const alert = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <strong>${type.charAt(0).toUpperCase() + type.slice(1)}:</strong> ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;
    
    // Add alert to page
    const alertContainer = document.createElement('div');
    alertContainer.innerHTML = alert;
    document.body.appendChild(alertContainer);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alertContainer.parentNode) {
            alertContainer.parentNode.removeChild(alertContainer);
        }
    }, 5000);
}
</script>
@endpush

@push('styles')
<style>
.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
}
</style>
@endpush
