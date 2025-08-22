@extends('layouts.app')

@section('title', 'Livestock Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1>
            <i class="fas fa-cow"></i>
            Livestock Management
        </h1>
        <p>Select a farmer to view and manage their livestock</p>
    </div>

    <!-- Farmer Selection Section -->
    <div class="card shadow mb-4" id="farmerSelectionCard">
        <div class="card-header">
            <h6>
                <i class="fas fa-users"></i>
                Select Farmer
            </h6>
            <div class="d-flex gap-2">
                <input type="text" class="form-control" id="farmerSearch" placeholder="Search farmers..." style="max-width: 300px;">
                <button class="btn btn-info btn-sm" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="farmersTable">
                    <thead>
                        <tr>
                            <th>Farmer ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Total Livestock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="farmersTableBody">
                        <!-- Farmers will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Livestock Section (Initially Hidden) -->
    <div class="card shadow mb-4" id="livestockCard" style="display: none;">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>
                        <i class="fas fa-cow"></i>
                        Livestock for: <span id="selectedFarmerName" class="text-primary font-weight-bold"></span>
                    </h6>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-secondary btn-sm" onclick="backToFarmers()">
                        <i class="fas fa-arrow-left"></i> Back to Farmers
                    </button>
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addLivestockModal">
                        <i class="fas fa-plus"></i> Add Livestock
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Statistics Cards -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card border-left-success">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Livestock</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="farmerTotalLivestock">0</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-left-info">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Active</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="farmerActiveLivestock">0</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-left-warning">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Inactive</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="farmerInactiveLivestock">0</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-left-primary">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Farms</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="farmerTotalFarms">0</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Livestock Table -->
            <div class="table-responsive">
                <table class="table table-bordered" id="livestockTable">
                    <thead>
                        <tr>
                            <th>Tag Number</th>
                            <th>Type</th>
                            <th>Breed</th>
                            <th>Gender</th>
                            <th>Farm</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="livestockTableBody">
                        <!-- Livestock will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Livestock Modal -->
<div class="modal fade" id="addLivestockModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Livestock</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="addLivestockForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="selectedFarmerId" name="farmer_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tag Number</label>
                                <input type="text" class="form-control" name="livestock_id" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="cow">Cow</option>
                                    <option value="buffalo">Buffalo</option>
                                    <option value="goat">Goat</option>
                                    <option value="sheep">Sheep</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Breed</label>
                                <select class="form-control" name="breed" required>
                                    <option value="">Select Breed</option>
                                    <option value="holstein">Holstein</option>
                                    <option value="jersey">Jersey</option>
                                    <option value="guernsey">Guernsey</option>
                                    <option value="ayrshire">Ayrshire</option>
                                    <option value="brown_swiss">Brown Swiss</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Farm</label>
                                <select class="form-control" name="farm_id" required>
                                    <option value="">Select Farm</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Birth Date</label>
                                <input type="date" class="form-control" name="birth_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gender</label>
                                <select class="form-control" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Livestock</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #4e73df 0%, #3c5aa6 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }
    
    .farmer-link {
        color: #4e73df;
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
    }
    
    .farmer-link:hover {
        color: #3c5aa6;
        text-decoration: underline;
    }
    
    .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
    .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
    .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
    .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
    
    .gap-2 { gap: 0.5rem !important; }
</style>
@endpush

@push('scripts')
<script>
    let selectedFarmerId = null;
    let selectedFarmerName = '';

    $(document).ready(function() {
        console.log('Document ready, loading farmers...');
        loadFarmers();
        
        // Search functionality
        $('#farmerSearch').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('#farmersTable tbody tr').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(searchTerm) > -1);
            });
        });
    });

    function loadFarmers() {
        $('#farmersTableBody').html('<tr><td colspan="7" class="text-center">Loading farmers...</td></tr>');
        
        $.ajax({
            url: '{{ route("admin.livestock.farmers") }}',
            method: 'GET',
            success: function(response) {
                console.log('Farmers response:', response);
                if (response.success) {
                    let html = '';
                    if (response.data.length === 0) {
                        html = '<tr><td colspan="7" class="text-center">No farmers found</td></tr>';
                    } else {
                        response.data.forEach(farmer => {
                            const displayName = farmer.first_name && farmer.last_name 
                                ? `${farmer.first_name} ${farmer.last_name}` 
                                : farmer.name || 'N/A';
                            
                            html += `
                                <tr>
                                    <td>${farmer.id}</td>
                                    <td><a href="#" class="farmer-link" onclick="selectFarmer('${farmer.id}', '${displayName}')">${displayName}</a></td>
                                    <td>${farmer.email}</td>
                                    <td>${farmer.contact_number || 'N/A'}</td>
                                    <td>${farmer.livestock_count || 0}</td>
                                    <td><span class="badge badge-${getStatusBadgeClass(farmer.status)}">${farmer.status}</span></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" onclick="selectFarmer('${farmer.id}', '${displayName}')">
                                            <i class="fas fa-cow"></i> View Livestock
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    $('#farmersTableBody').html(html);
                } else {
                    $('#farmersTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading farmers: ' + (response.message || 'Unknown error') + '</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr, status, error);
                console.log('Response Text:', xhr.responseText);
                $('#farmersTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading farmers. Check console for details.</td></tr>');
            }
        });
    }

    function selectFarmer(farmerId, farmerName) {
        selectedFarmerId = farmerId;
        selectedFarmerName = farmerName;
        
        $('#selectedFarmerName').text(farmerName);
        $('#selectedFarmerId').val(farmerId);
        
        $('#farmerSelectionCard').hide();
        $('#livestockCard').show();
        
        loadFarmerLivestock(farmerId);
        loadFarmerFarms(farmerId);
    }

    function backToFarmers() {
        selectedFarmerId = null;
        selectedFarmerName = '';
        
        $('#farmerSelectionCard').show();
        $('#livestockCard').hide();
        
        $('#livestockTableBody').empty();
    }

    function loadFarmerLivestock(farmerId) {
        $('#livestockTableBody').html('<tr><td colspan="7" class="text-center">Loading livestock...</td></tr>');
        
        $.ajax({
            url: `{{ route("admin.livestock.farmer-livestock", ":id") }}`.replace(':id', farmerId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    let html = '';
                    if (response.data.livestock.length === 0) {
                        html = '<tr><td colspan="7" class="text-center">No livestock found for this farmer</td></tr>';
                    } else {
                        response.data.livestock.forEach(animal => {
                            html += `
                                <tr>
                                    <td>${animal.tag_number}</td>
                                    <td>${animal.type}</td>
                                    <td>${animal.breed}</td>
                                    <td>${animal.gender}</td>
                                    <td>${animal.farm ? animal.farm.name : 'N/A'}</td>
                                    <td>
                                        <select class="form-control" onchange="updateStatus(this, '${animal.id}')">
                                            <option value="active" ${animal.status === 'active' ? 'selected' : ''}>Active</option>
                                            <option value="inactive" ${animal.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-sm" onclick="editLivestock('${animal.id}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteLivestock('${animal.id}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    $('#livestockTableBody').html(html);
                    updateFarmerStats(response.data.stats);
                } else {
                    $('#livestockTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading livestock</td></tr>');
                }
            },
            error: function() {
                $('#livestockTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading livestock</td></tr>');
            }
        });
    }

    function loadFarmerFarms(farmerId) {
        $.ajax({
            url: `{{ route("admin.livestock.farmer-farms", ":id") }}`.replace(':id', farmerId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const farmSelect = $('select[name="farm_id"]');
                    farmSelect.empty().append('<option value="">Select Farm</option>');
                    
                    response.data.forEach(farm => {
                        farmSelect.append(`<option value="${farm.id}">${farm.name}</option>`);
                    });
                }
            }
        });
    }

    function updateFarmerStats(stats) {
        $('#farmerTotalLivestock').text(stats.total || 0);
        $('#farmerActiveLivestock').text(stats.active || 0);
        $('#farmerInactiveLivestock').text(stats.inactive || 0);
        $('#farmerTotalFarms').text(stats.farms || 0);
    }

    function refreshData() {
        if (selectedFarmerId) {
            loadFarmerLivestock(selectedFarmerId);
        } else {
            loadFarmers();
        }
    }

    function getStatusBadgeClass(status) {
        switch(status) {
            case 'approved': return 'success';
            case 'pending': return 'warning';
            case 'suspended': return 'danger';
            default: return 'secondary';
        }
    }

    function updateStatus(select, livestockId) {
        const status = select.value;
        
        $.ajax({
            url: `{{ route('admin.livestock.update-status', ':id') }}`.replace(':id', livestockId),
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            data: JSON.stringify({ status: status }),
            success: function(response) {
                if (response.success) {
                    if (selectedFarmerId) {
                        loadFarmerLivestock(selectedFarmerId);
                    }
                }
            }
        });
    }

    function editLivestock(livestockId) {
        // Implementation for editing livestock
        alert('Edit functionality coming soon');
    }

    function deleteLivestock(livestockId) {
        if (confirm('Are you sure you want to delete this livestock?')) {
            $.ajax({
                url: `{{ route('admin.livestock.destroy', ':id') }}`.replace(':id', livestockId),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (selectedFarmerId) {
                        loadFarmerLivestock(selectedFarmerId);
                    }
                }
            });
        }
    }

    // Handle add livestock form submission
    $('#addLivestockForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('farmer_id', selectedFarmerId);
        
        $.ajax({
            url: '{{ route("admin.livestock.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#addLivestockModal').modal('hide');
                $('#addLivestockForm')[0].reset();
                loadFarmerLivestock(selectedFarmerId);
            }
        });
    });
</script>
@endpush
