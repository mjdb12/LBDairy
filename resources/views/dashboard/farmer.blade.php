@extends('layouts.app')

@section('title', 'Farmer Dashboard - LBDAIRY')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Farmer Dashboard</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
    </a>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Total Livestock Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Livestock</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLivestock }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-cow fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Production Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Production (L)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalProduction, 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Sales Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Sales</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalSales, 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Expenses Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Total Expenses</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalExpenses, 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-receipt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- My Farms -->
    <div class="col-xl-6 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">My Farms</h6>
                <a href="{{ route('farmer.farms') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                @if($farms->count() > 0)
                    @foreach($farms as $farm)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-1">{{ $farm->name }}</h6>
                                <small class="text-muted">{{ $farm->location }} • {{ $farm->livestock_count }} livestock</small>
                            </div>
                            <span class="badge badge-{{ $farm->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($farm->status) }}
                            </span>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No farms registered yet.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Production -->
    <div class="col-xl-6 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Recent Production</h6>
                <a href="{{ route('farmer.production') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                @if($recentProduction->count() > 0)
                    @foreach($recentProduction as $production)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-1">{{ $production->livestock->name ?? 'Unknown' }}</h6>
                                <small class="text-muted">{{ $production->production_date->format('M d, Y') }}</small>
                            </div>
                            <span class="font-weight-bold">{{ $production->milk_quantity }}L</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No production records yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Recent Sales -->
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Recent Sales</h6>
                <a href="{{ route('farmer.sales') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                @if($recentSales->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Quantity (L)</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSales as $sale)
                                    <tr>
                                        <td>{{ $sale->customer_name }}</td>
                                        <td>{{ $sale->quantity }}</td>
                                        <td>${{ number_format($sale->total_amount, 2) }}</td>
                                        <td>{{ $sale->sale_date->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $sale->payment_status === 'paid' ? 'success' : ($sale->payment_status === 'partial' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($sale->payment_status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No sales records yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
