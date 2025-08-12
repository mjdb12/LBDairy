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
@endsection

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
