@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers - Scan Livestock')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-qrcode"></i>
        Scan Livestock
    </h1>
    <p>Scan QR codes to access livestock information and manage records</p>
</div>

<div class="row">
    <!-- Scanner Section -->
    <div class="col-md-6">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header">
                <h6>
                    <i class="fas fa-camera"></i>
                    QR Code Scanner
                </h6>
            </div>
            <div class="card-body text-center">
                <div id="reader" style="width: 100%; max-width: 400px; margin: 0 auto;"></div>
                <div id="statusIndicator" class="status-indicator status-ready mt-3">
                    <i class="fas fa-camera"></i> Ready to scan
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary" onclick="startScanning()" id="startBtn">
                        <i class="fas fa-play"></i> Start Scanning
                    </button>
                    <button class="btn btn-secondary" onclick="stopScanning()" id="stopBtn" style="display: none;">
                        <i class="fas fa-stop"></i> Stop Scanning
                    </button>
                </div>
            </div>
        </div>

        <!-- Test QR Codes -->
        <div class="card shadow mb-4 fade-in">
            <div class="card-header">
                <h6>
                    <i class="fas fa-qrcode"></i>
                    Test QR Codes
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted small">Scan these test codes to see sample livestock data:</p>
                <div class="row">
                    <div class="col-6 text-center mb-3">
                        <div id="qrCode1"></div>
                        <small class="text-muted">LS001</small>
                    </div>
                    <div class="col-6 text-center mb-3">
                        <div id="qrCode2"></div>
                        <small class="text-muted">LS002</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 text-center mb-3">
                        <div id="qrCode3"></div>
                        <small class="text-muted">LS003</small>
                    </div>
                    <div class="col-6 text-center mb-3">
                        <div id="qrCode4"></div>
                        <small class="text-muted">LS004</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Livestock Details Section -->
    <div class="col-md-6">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header">
                <h6>
                    <i class="fas fa-info-circle"></i>
                    Livestock Details
                </h6>
                <span class="badge badge-primary" id="detailLivestockId">No ID</span>
            </div>
            <div class="card-body">
                <div class="nav nav-tabs" id="livestockTab" role="tablist">
                    <a class="nav-link active" id="basic-tab" data-toggle="tab" href="#basicForm" role="tab">
                        <i class="fas fa-info"></i> Basic Info
                    </a>
                    <a class="nav-link" id="growth-tab" data-toggle="tab" href="#growthForm" role="tab">
                        <i class="fas fa-chart-line"></i> Growth
                    </a>
                    <a class="nav-link" id="milk-tab" data-toggle="tab" href="#milkForm" role="tab">
                        <i class="fas fa-milk"></i> Milk
                    </a>
                    <a class="nav-link" id="breeding-tab" data-toggle="tab" href="#breedingForm" role="tab">
                        <i class="fas fa-heart"></i> Breeding
                    </a>
                    <a class="nav-link" id="health-tab" data-toggle="tab" href="#healthForm" role="tab">
                        <i class="fas fa-heartbeat"></i> Health
                    </a>
                </div>
                
                <div class="tab-content" id="livestockTabContent">
                    <!-- Basic Info (Read-only) -->
                    <div class="tab-pane fade show active" id="basicForm" role="tabpanel" aria-labelledby="basic-tab">
                        <form id="basicDetailsForm">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><th>Owned By</th><td><input type="text" class="form-control" id="ownedBy" readonly></td></tr>
                                        <tr><th>Dispersal From</th><td><input type="text" class="form-control" id="dispersalFrom" readonly></td></tr>
                                        <tr><th>Registry ID</th><td><input type="text" class="form-control" id="registryId" readonly></td></tr>
                                        <tr><th>Tag ID</th><td><input type="text" class="form-control" id="tagId" readonly></td></tr>
                                        <tr><th>Name</th><td><input type="text" class="form-control" id="livestockName" readonly></td></tr>
                                        <tr><th>Date of Birth</th><td><input type="date" class="form-control" id="dob" readonly></td></tr>
                                        <tr><th>Sex</th><td><input type="text" class="form-control" id="sex" readonly></td></tr>
                                        <tr><th>Breed</th><td><input type="text" class="form-control" id="breed" readonly></td></tr>
                                        <tr><th>Sire Registry ID</th><td><input type="text" class="form-control" id="sireId" readonly></td></tr>
                                        <tr><th>Dam Registry ID</th><td><input type="text" class="form-control" id="damId" readonly></td></tr>
                                        <tr><th>Sire Name</th><td><input type="text" class="form-control" id="sireName" readonly></td></tr>
                                        <tr><th>Dam Name</th><td><input type="text" class="form-control" id="damName" readonly></td></tr>
                                        <tr><th>Sire Breed</th><td><input type="text" class="form-control" id="sireBreed" readonly></td></tr>
                                        <tr><th>Dam Breed</th><td><input type="text" class="form-control" id="damBreed" readonly></td></tr>
                                        <tr><th>Natural Marks</th><td><input type="text" class="form-control" id="naturalMarks" readonly></td></tr>
                                        <tr><th>Property No.</th><td><input type="text" class="form-control" id="propertyNo" readonly></td></tr>
                                        <tr><th>Date Acquired</th><td><input type="date" class="form-control" id="acquisitionDate" readonly></td></tr>
                                        <tr><th>Acquisition Cost</th><td><input type="number" class="form-control" id="acquisitionCost" readonly></td></tr>
                                        <tr><th>Source</th><td><input type="text" class="form-control" id="source" readonly></td></tr>
                                        <tr><th>Remarks</th><td><textarea class="form-control" id="remarks" readonly></textarea></td></tr>
                                        <tr><th>Cooperator</th><td><input type="text" class="form-control" id="cooperator" readonly></td></tr>
                                        <tr><th>Date Released</th><td><input type="date" class="form-control" id="releasedDate" readonly></td></tr>
                                        <tr><th>Cooperative</th><td><input type="text" class="form-control" id="cooperative" readonly></td></tr>
                                        <tr><th>Address</th><td><input type="text" class="form-control" id="address" readonly></td></tr>
                                        <tr><th>Contact No.</th><td><input type="text" class="form-control" id="contactNo" readonly></td></tr>
                                        <tr><th>In-Charge</th><td><input type="text" class="form-control" id="inCharge" readonly></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Growth (Editable) -->
                    <div class="tab-pane fade" id="growthForm" role="tabpanel" aria-labelledby="growth-tab">
                        <form id="growthDetailsForm">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><th>Date</th><td><input type="date" class="form-control" id="growthDate"></td></tr>
                                        <tr><th>Weight (kg)</th><td><input type="number" class="form-control" id="weight"></td></tr>
                                        <tr><th>Height (cm)</th><td><input type="number" class="form-control" id="height"></td></tr>
                                        <tr><th>Heart Girth (cm)</th><td><input type="number" step="0.1" class="form-control" id="heartGirthCm" required></td></tr>
                                        <tr><th>Body Length (cm)</th><td><input type="number" step="0.1" class="form-control" id="bodyLengthCm" required></td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Record
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Milk (Editable) -->
                    <div class="tab-pane fade" id="milkForm" role="tabpanel" aria-labelledby="milk-tab">
                        <form id="milkDetailsForm">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><th>Date of Calving</th><td><input type="date" class="form-control" id="calvingDate" required></td></tr>
                                        <tr><th>Calf ID Number</th><td><input type="text" class="form-control" id="calfIdNumber" required></td></tr>
                                        <tr><th>Sex</th><td><select class="form-control" id="calfSex" required><option value="" disabled selected>Select Sex</option><option value="Male">Male</option><option value="Female">Female</option></select></td></tr>
                                        <tr><th>Breed</th><td><input type="text" class="form-control" id="calfBreed" required></td></tr>
                                        <tr><th>Sire ID Number</th><td><input type="text" class="form-control" id="sireIdNumber" required></td></tr>
                                        <tr><th>Milk Production (liters)</th><td><input type="number" step="0.01" class="form-control" id="milkProduction" required></td></tr>
                                        <tr><th>Days in Milk</th><td><input type="number" class="form-control" id="daysInMilk" required></td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Record
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Breeding Form -->
                    <div class="tab-pane fade" id="breedingForm" role="tabpanel" aria-labelledby="breeding-tab">
                        <form id="breedingDetailsForm">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><th>Breeding Date</th><td><input type="date" class="form-control" id="breedingDate" value="2023-04-15" readonly></td></tr>
                                        <tr><th>Breeding Type</th><td><select class="form-control" id="breedingType" readonly><option value="Natural" selected>Natural</option><option value="Artificial Insemination">Artificial Insemination</option></select></td></tr>
                                        <tr><th>Sire Registry ID</th><td><input type="text" class="form-control" id="breedingSireId" value="SR123" readonly></td></tr>
                                        <tr><th>Dam Registry ID</th><td><input type="text" class="form-control" id="breedingDamId" value="DR456" readonly></td></tr>
                                        <tr><th>Pregnancy Check Date</th><td><input type="date" class="form-control" id="pregnancyCheckDate" value="2023-05-01" readonly></td></tr>
                                        <tr><th>Pregnancy Result</th><td><select class="form-control" id="pregnancyResult" readonly><option value="Positive" selected>Positive</option><option value="Negative">Negative</option><option value="Unknown">Unknown</option></select></td></tr>
                                        <tr><th>Remarks</th><td><textarea class="form-control" id="breedingRemarks" readonly>Healthy pregnancy confirmed.</textarea></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>

                    <!-- Health Form -->
                    <div class="tab-pane fade" id="healthForm" role="tabpanel" aria-labelledby="health-tab">
                        <form id="healthDetailsForm">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><th>Date</th><td><input type="date" class="form-control" id="healthDate" value="2023-04-20" readonly></td></tr>
                                        <tr><th>Health Status</th><td><input type="text" class="form-control" id="healthStatus" value="Good" readonly></td></tr>
                                        <tr><th>Treatment</th><td><input type="text" class="form-control" id="treatment" value="Routine check-up" readonly></td></tr>
                                        <tr><th>Remarks</th><td><textarea class="form-control" id="healthRemarks" readonly>No issues found during the check-up.</textarea></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.status-indicator {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.status-ready {
    background-color: #e3f2fd;
    color: #1976d2;
}

.status-scanning {
    background-color: #fff3e0;
    color: #f57c00;
}

.status-success {
    background-color: #e8f5e8;
    color: #388e3c;
}

.status-error {
    background-color: #ffebee;
    color: #d32f2f;
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
let html5QrCode;
let isScanning = false;

document.addEventListener('DOMContentLoaded', function() {
    // Generate test QR codes
    generateTestQRCodes();
    
    // Initialize forms
    initializeForms();
});

function updateStatus(message, type) {
    const statusElement = document.getElementById('statusIndicator');
    const iconMap = {
        'ready': 'fas fa-camera',
        'scanning': 'fas fa-search',
        'success': 'fas fa-check-circle',
        'error': 'fas fa-exclamation-triangle'
    };
    
    statusElement.className = `status-indicator status-${type}`;
    statusElement.innerHTML = `<i class="${iconMap[type]}"></i> ${message}`;
}

function generateTestQRCodes() {
    const qrCodes = ['LS001', 'LS002', 'LS003', 'LS004'];
    qrCodes.forEach((code, index) => {
        const qrDiv = document.getElementById(`qrCode${index + 1}`);
        new QRCode(qrDiv, {
            text: code,
            width: 80,
            height: 80
        });
    });
}

function startScanning() {
    if (isScanning) return;
    
    html5QrCode = new Html5Qrcode("reader");
    
    const config = {
        fps: 10,
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0
    };
    
    html5QrCode.start(
        { facingMode: "environment" },
        config,
        onScanSuccess,
        onScanFailure
    ).then(() => {
        isScanning = true;
        updateStatus('Scanning...', 'scanning');
        document.getElementById('startBtn').style.display = 'none';
        document.getElementById('stopBtn').style.display = 'inline-block';
    }).catch(err => {
        console.error('Failed to start scanner:', err);
        updateStatus('Failed to start scanner', 'error');
    });
}

function stopScanning() {
    if (!isScanning || !html5QrCode) return;
    
    html5QrCode.stop().then(() => {
        isScanning = false;
        updateStatus('Ready to scan', 'ready');
        document.getElementById('startBtn').style.display = 'inline-block';
        document.getElementById('stopBtn').style.display = 'none';
    }).catch(err => {
        console.error('Failed to stop scanner:', err);
    });
}

function onScanSuccess(decodedText, decodedResult) {
    console.log('QR Code scanned:', decodedText);
    
    // Stop scanning after successful scan
    stopScanning();
    
    // Update status
    updateStatus('QR Code detected!', 'success');
    
    // Process the scanned data
    viewLivestockDetails(decodedText);
    
    // Reset status after 2 seconds
    setTimeout(() => {
        updateStatus('Ready to scan', 'ready');
    }, 2000);
}

function onScanFailure(error) {
    // Handle scan failure silently
    console.log('Scan failed:', error);
}

function viewLivestockDetails(id) {
    // Simulate data fetching
    const data = {
        LS001: {
            ownedBy: "Juan Dela Cruz",
            dispersalFrom: "LGU Lucban",
            registryId: "REG123",
            tagId: "TAG001",
            livestockName: "Boer Goat Alpha",
            dob: "2021-03-10",
            sex: "Male",
            breed: "Boer",
            sireId: "SR001",
            damId: "DR001",
            sireName: "Billy",
            damName: "Nanny",
            sireBreed: "Boer",
            damBreed: "Boer",
            naturalMarks: "White patch on right ear",
            propertyNo: "PROP-001",
            acquisitionDate: "2022-01-15",
            acquisitionCost: "12000",
            source: "DA Dispersal",
            remarks: "Healthy and active",
            cooperator: "Maria Clara",
            releasedDate: "2022-02-01",
            cooperative: "Lucban Goat Farmers",
            address: "Brgy. Kulapi, Lucban",
            contactNo: "09123456789",
            inCharge: "Mr. Veterinarian"
        },
        LS002: {
            ownedBy: "Maria Reyes",
            dispersalFrom: "DA Office",
            registryId: "REG124",
            tagId: "TAG002",
            livestockName: "Angus Cow Beta",
            dob: "2020-05-15",
            sex: "Female",
            breed: "Angus",
            sireId: "SR002",
            damId: "DR002",
            sireName: "Bull",
            damName: "Daisy",
            sireBreed: "Angus",
            damBreed: "Angus",
            naturalMarks: "Black coat with white star on forehead",
            propertyNo: "PROP-002",
            acquisitionDate: "2021-06-20",
            acquisitionCost: "45000",
            source: "Private Purchase",
            remarks: "Excellent milk producer",
            cooperator: "Pedro Santos",
            releasedDate: "2021-07-01",
            cooperative: "Lucban Cattle Farmers",
            address: "Brgy. Aliliw, Lucban",
            contactNo: "09234567890",
            inCharge: "Dr. Animal Health"
        },
        LS003: {
            ownedBy: "Josefa Reyes",
            dispersalFrom: "LGU Lucban",
            registryId: "REG125",
            tagId: "TAG003",
            livestockName: "Carabao Gamma",
            dob: "2019-08-22",
            sex: "Male",
            breed: "Philippine Native",
            sireId: "SR003",
            damId: "DR003",
            sireName: "Kalabaw",
            damName: "Maya",
            sireBreed: "Philippine Native",
            damBreed: "Philippine Native",
            naturalMarks: "Gray coat with white markings",
            propertyNo: "PROP-003",
            acquisitionDate: "2020-09-10",
            acquisitionCost: "35000",
            source: "Government Dispersal",
            remarks: "Strong and healthy",
            cooperator: "Luis Garcia",
            releasedDate: "2020-10-01",
            cooperative: "Lucban Carabao Farmers",
            address: "Brgy. Atulinao, Lucban",
            contactNo: "09345678901",
            inCharge: "Mr. Farm Manager"
        },
        LS004: {
            ownedBy: "Maria Rivera",
            dispersalFrom: "DA Office",
            registryId: "REG126",
            tagId: "TAG004",
            livestockName: "Dorper Goat Delta",
            dob: "2021-11-05",
            sex: "Female",
            breed: "Dorper",
            sireId: "SR004",
            damId: "DR004",
            sireName: "Rocky",
            damName: "Flora",
            sireBreed: "Dorper",
            damBreed: "Dorper",
            naturalMarks: "White head with black body",
            propertyNo: "PROP-004",
            acquisitionDate: "2022-12-01",
            acquisitionCost: "15000",
            source: "Private Breeder",
            remarks: "Good for breeding",
            cooperator: "Ana Lopez",
            releasedDate: "2022-12-15",
            cooperative: "Lucban Goat Farmers",
            address: "Brgy. Ayuti, Lucban",
            contactNo: "09456789012",
            inCharge: "Ms. Livestock Specialist"
        }
    };

    const info = data[id];
    if (info) {
        console.log('Found livestock data for:', id, info);
        document.getElementById("detailLivestockId").textContent = id;
        
        // Populate form fields
        Object.keys(info).forEach(key => {
            const element = document.getElementById(key);
            if (element) {
                element.value = info[key];
            }
        });
        
        // Show success message
        showNotification(`Livestock ${id} information loaded successfully!`, 'success');
    } else {
        showNotification(`No data found for livestock ID: ${id}`, 'error');
    }
}

function initializeForms() {
    // Growth form submission
    document.getElementById('growthDetailsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        showNotification('Growth record saved successfully!', 'success');
    });
    
    // Milk form submission
    document.getElementById('milkDetailsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        showNotification('Milk record saved successfully!', 'success');
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}
</script>
@endpush

