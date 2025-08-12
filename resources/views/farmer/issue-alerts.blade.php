@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers - Issue Alerts')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-exclamation-triangle"></i>
        Issue Alerts
    </h1>
    <p>Monitor and respond to critical farm and livestock issues</p>
</div>

<div class="row">
    <!-- Critical Issues -->
    <div class="col-lg-8">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header">
                <h6>
                    <i class="fas fa-exclamation-circle text-danger"></i>
                    Critical Issues
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-danger border-left-danger">
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="font-weight-bold">High Temperature Alert</h6>
                            <p class="mb-1">Barn temperature exceeds 32°C. Cooling measures needed to prevent heat stress.</p>
                            <small class="text-muted">Detected: 2 hours ago | Location: Main Barn</small>
                        </div>
                        <div class="col-md-4 text-right">
                            <span class="badge badge-danger">URGENT</span>
                            <br><br>
                            <button class="btn btn-sm btn-outline-danger">Mark Resolved</button>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning border-left-warning">
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="font-weight-bold">Low Milk Yield Alert</h6>
                            <p class="mb-1">Average milk yield dropped by 12% this week compared to the last. Check feeding and stress factors.</p>
                            <small class="text-muted">Detected: 1 day ago | Location: Dairy Section</small>
                        </div>
                        <div class="col-md-4 text-right">
                            <span class="badge badge-warning">MEDIUM</span>
                            <br><br>
                            <button class="btn btn-sm btn-outline-warning">Mark Resolved</button>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info border-left-info">
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="font-weight-bold">Feed Inventory Low</h6>
                            <p class="mb-1">Feed inventory is running low. Reorder within 3 days to avoid shortage.</p>
                            <small class="text-muted">Detected: 2 days ago | Location: Storage Area</small>
                        </div>
                        <div class="col-md-4 text-right">
                            <span class="badge badge-info">LOW</span>
                            <br><br>
                            <button class="btn btn-sm btn-outline-info">Mark Resolved</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Issues -->
        <div class="card shadow mb-4 fade-in">
            <div class="card-header">
                <h6>
                    <i class="fas fa-history"></i>
                    Recent Issues
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Issue</th>
                                <th>Status</th>
                                <th>Detected</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Water system malfunction</td>
                                <td><span class="badge badge-success">Resolved</span></td>
                                <td>3 days ago</td>
                                <td><button class="btn btn-sm btn-outline-secondary" disabled>Resolved</button></td>
                            </tr>
                            <tr>
                                <td>Vaccination due</td>
                                <td><span class="badge badge-warning">Pending</span></td>
                                <td>4 days ago</td>
                                <td><button class="btn btn-sm btn-outline-warning">Schedule</button></td>
                            </tr>
                            <tr>
                                <td>Equipment maintenance</td>
                                <td><span class="badge badge-info">In Progress</span></td>
                                <td>5 days ago</td>
                                <td><button class="btn btn-sm btn-outline-info">Update</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Issue Statistics -->
    <div class="col-lg-4">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header">
                <h6>
                    <i class="fas fa-chart-pie"></i>
                    Issue Statistics
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border-left-danger">
                            <div class="text-danger font-weight-bold" style="font-size: 2rem;">3</div>
                            <div class="text-muted small">Critical</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border-left-warning">
                            <div class="text-warning font-weight-bold" style="font-size: 2rem;">5</div>
                            <div class="text-muted small">Medium</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border-left-info">
                            <div class="text-info font-weight-bold" style="font-size: 2rem;">8</div>
                            <div class="text-muted small">Low</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border-left-success">
                            <div class="text-success font-weight-bold" style="font-size: 2rem;">12</div>
                            <div class="text-muted small">Resolved</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow mb-4 fade-in">
            <div class="card-header">
                <h6>
                    <i class="fas fa-tools"></i>
                    Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <button class="btn btn-primary btn-block mb-2">
                    <i class="fas fa-plus"></i> Report New Issue
                </button>
                <button class="btn btn-outline-secondary btn-block mb-2">
                    <i class="fas fa-bell"></i> Notification Settings
                </button>
                <button class="btn btn-outline-info btn-block">
                    <i class="fas fa-download"></i> Export Report
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.border-left-danger {
    border-left: 4px solid #e74a3b !important;
}

.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}

.border-left-info {
    border-left: 4px solid #36b9cc !important;
}

.border-left-success {
    border-left: 4px solid #1cc88a !important;
}

.alert {
    border-radius: 8px;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.alert:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}
</style>
@endpush
