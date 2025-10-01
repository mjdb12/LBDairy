@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers - Clients')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-users"></i>
        Clients Management
    </h1>
    <p>Manage your client relationships and track sales performance</p>
</div>

<div class="row">
    <!-- Client Statistics -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 fade-in">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Clients</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalClients }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2 fade-in">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Active Clients</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeClients }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2 fade-in">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Monthly Revenue</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($monthlyRevenue) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2 fade-in">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            New This Month</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $newThisMonth }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 1. Client Directory -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Client Directory</h6>
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addClientModal">
                    <i class="fas fa-plus"></i> Add New Client
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Contact</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Total Orders</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clientsData as $client)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img class="img-profile rounded-circle mr-3" src="{{ asset('img/ronaldo.png') }}" width="40">
                                        <div>
                                            <div class="font-weight-bold">{{ $client['name'] }}</div>
                                            <small class="text-muted">{{ $client['type_label'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $client['phone'] ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $client['email'] ?? 'N/A' }}</small>
                                </td>
                                <td><span class="badge {{ $client['type_badge'] }}">{{ $client['type_label'] }}</span></td>
                                <td><span class="badge {{ $client['status_badge'] }}">{{ $client['status_label'] }}</span></td>
                                <td>{{ $client['total_orders'] }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewClient('{{ $client['name'] }}')">View</button>
                                    <button class="btn btn-sm btn-outline-info" onclick="editClient('{{ $client['name'] }}')">Edit</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center text-muted">N/A</td>
                                <td class="text-center text-muted">N/A</td>
                                <td class="text-center text-muted">N/A</td>
                                <td class="text-center text-muted">N/A</td>
                                <td class="text-center text-muted">No clients found</td>
                                <td class="text-center text-muted">N/A</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2. Client Distribution -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-pie"></i>
                    Client Distribution
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="clientDistributionChart"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mt-4 text-center small">
                            <div class="mb-3">
                                <span class="mr-2">
                                    <i class="fas fa-circle text-primary"></i> Retail
                                </span>
                                <div class="font-weight-bold text-primary">{{ $clientDistribution['retail'] ?? 0 }} clients</div>
                            </div>
                            <div class="mb-3">
                                <span class="mr-2">
                                    <i class="fas fa-circle text-info"></i> Wholesale
                                </span>
                                <div class="font-weight-bold text-info">{{ $clientDistribution['wholesale'] ?? 0 }} clients</div>
                            </div>
                            <div class="mb-3">
                                <span class="mr-2">
                                    <i class="fas fa-circle text-warning"></i> Business
                                </span>
                                <div class="font-weight-bold text-warning">{{ $clientDistribution['business'] ?? 0 }} clients</div>
                            </div>
                            <div class="mb-3">
                                <span class="mr-2">
                                    <i class="fas fa-circle text-secondary"></i> Market
                                </span>
                                <div class="font-weight-bold text-secondary">{{ $clientDistribution['market'] ?? 0 }} clients</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3. Top Clients -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-trophy"></i>
                    Top Clients
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($topClients as $client)
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="card border-left-primary h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <img class="img-profile rounded-circle mr-3" src="{{ asset('img/ronaldo.png') }}" width="50">
                                    <div class="flex-grow-1">
                                        <div class="font-weight-bold text-primary">{{ $client['name'] }}</div>
                                        <small class="text-muted">{{ $client['type'] }}</small>
                                        <div class="mt-2">
                                            <span class="badge badge-primary badge-pill">₱{{ number_format($client['total_spent']) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-trophy fa-3x mb-3 text-muted"></i>
                            <p>No top clients data available yet.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Client Modal -->
<div class="modal fade" id="addClientModal" tabindex="-1" role="dialog" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClientModalLabel">
                    <i class="fas fa-user-plus"></i>
                    Add New Client
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addClientForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clientName">Full Name *</label>
                                <input type="text" class="form-control" id="clientName" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clientType">Client Type *</label>
                                <select class="form-control" id="clientType" required>
                                    <option value="">Select Type</option>
                                    <option value="retail">Retail</option>
                                    <option value="wholesale">Wholesale</option>
                                    <option value="business">Business</option>
                                    <option value="market">Market</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clientPhone">Phone Number *</label>
                                <input type="tel" class="form-control" id="clientPhone" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clientEmail">Email Address</label>
                                <input type="email" class="form-control" id="clientEmail">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="clientAddress">Address</label>
                                <textarea class="form-control" id="clientAddress" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clientNotes">Notes</label>
                                <textarea class="form-control" id="clientNotes" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clientStatus">Status</label>
                                <select class="form-control" id="clientStatus">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Client
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Client Distribution Chart
    const clientCtx = document.getElementById('clientDistributionChart').getContext('2d');
    const clientChart = new Chart(clientCtx, {
        type: 'doughnut',
        data: {
            labels: ['Retail', 'Wholesale', 'Business', 'Market'],
            datasets: [{
                data: [
                    {{ $clientDistribution['retail'] ?? 0 }},
                    {{ $clientDistribution['wholesale'] ?? 0 }},
                    {{ $clientDistribution['business'] ?? 0 }},
                    {{ $clientDistribution['market'] ?? 0 }}
                ],
                backgroundColor: ['#4e73df', '#36b9cc', '#f6c23e', '#6c757d'],
                hoverBackgroundColor: ['#2e59d9', '#2c9faf', '#f4b619', '#545b62'],
                hoverBorderColor: 'rgba(234, 236, 244, 1)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%'
        }
    });

    // Form submission
    document.getElementById('addClientForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show success message
        const notification = document.createElement('div');
        notification.className = 'alert alert-success alert-dismissible fade show position-fixed';
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <i class="fas fa-check-circle"></i>
            Client added successfully!
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;
        
        document.body.appendChild(notification);
        
        // Close modal
        $('#addClientModal').modal('hide');
        
        // Reset form
        this.reset();
        
        // Remove notification after 5 seconds
        setTimeout(() => {
            notification.remove();
        }, 5000);
    });
});
</script>
@endpush
