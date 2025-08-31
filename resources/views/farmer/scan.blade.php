@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers - Scan Livestock')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in">
    <h1>
        <i class="fas fa-qrcode"></i>
        QR Code Scanner
    </h1>
    <p>Scan livestock QR codes to view and manage animal information</p>
</div>

<!-- QR Scanner Container -->
<div class="scanner-container fade-in">
    <div id="qr-reader-container" class="pulse">
        <!-- Status Indicator -->
        <div class="status-indicator status-ready" id="statusIndicator">
            <i class="fas fa-camera"></i> Ready to Scan
        </div>

        <!-- Scanner Overlay -->
        <div class="scanner-overlay">
            <div class="corner"></div>
        </div>

        <!-- Camera feed -->
        <div id="qr-reader"></div>

        <!-- Scanner Controls -->
        <div class="scanner-controls">
            <button id="captureButton" class="scanner-btn" onclick="viewLivestockDetails('LS001')" title="Capture QR Code">
                <i class="fas fa-camera"></i>
            </button>
            <label for="uploadQR" class="scanner-btn mb-0" title="Upload QR Image">
                <i class="fas fa-upload"></i>
            </label>
            <input type="file" id="uploadQR" accept="image/*" style="display: none;">
        </div>
    </div>

    <!-- Scanner Info Panel -->
    <div class="scanner-info">
        <h5><i class="fas fa-info-circle"></i> How to Use</h5>
        <p><strong>Camera Scan:</strong> Point your camera at a QR code to scan</p>
        <p><strong>Upload Image:</strong> Upload a QR code image file</p>
        <p><strong>Capture:</strong> Take a photo of a QR code</p>
        <p><strong>View Details:</strong> Successfully scanned codes will display livestock information</p>
    </div>

    <!-- Scan Results -->
    <div id="qr-reader-results" class="mt-3 text-center" style="font-size: 1.1rem; font-weight: 500;"></div>
</div>

<!-- Test QR Codes Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow mb-4 fade-in">
            <div class="card-header">
                <h6>
                    <i class="fas fa-qrcode"></i>
                    Test QR Codes
                </h6>
            </div>
            <div class="card-body text-center">
                <p class="text-muted mb-3">Scan these test codes to see sample livestock data:</p>
                <div class="row justify-content-center">
                    <div class="col-3 text-center mb-3">
                        <div id="qrCode1" class="test-qr-code"></div>
                        <small class="text-muted d-block mt-2">LS001</small>
                    </div>
                    <div class="col-3 text-center mb-3">
                        <div id="qrCode2" class="test-qr-code"></div>
                        <small class="text-muted d-block mt-2">LS002</small>
                    </div>
                    <div class="col-3 text-center mb-3">
                        <div id="qrCode3" class="test-qr-code"></div>
                        <small class="text-muted d-block mt-2">LS003</small>
                    </div>
                    <div class="col-3 text-center mb-3">
                        <div id="qrCode4" class="test-qr-code"></div>
                        <small class="text-muted d-block mt-2">LS004</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- LIVESTOCK DETAILS & EDIT MODAL WITH OPTION BAR -->
<div class="modal fade" id="livestockDetailsModal" tabindex="-1" role="dialog" aria-labelledby="livestockDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-paw"></i>
                    Livestock Details: <span id="detailLivestockId"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Option Bar -->
                <ul class="nav nav-tabs mb-3" id="livestockTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="basic-tab" data-toggle="tab" href="#basicForm" role="tab" aria-controls="basicForm" aria-selected="true">
                            <i class="fas fa-info-circle"></i> Basic Info
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="growth-tab" data-toggle="tab" href="#growthForm" role="tab" aria-controls="growthForm" aria-selected="false">
                            <i class="fas fa-chart-line"></i> Growth
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="milk-tab" data-toggle="tab" href="#milkForm" role="tab" aria-controls="milkForm" aria-selected="false">
                            <i class="fas fa-tint"></i> Milk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="breeding-tab" data-toggle="tab" href="#breedingForm" role="tab" aria-controls="breedingForm" aria-selected="false">
                            <i class="fas fa-heart"></i> Breeding
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="health-tab" data-toggle="tab" href="#healthForm" role="tab" aria-controls="healthForm" aria-selected="false">
                            <i class="fas fa-heartbeat"></i> Health
                        </a>
                    </li>
                </ul>
                
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


/* Enhanced Card Styling */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    overflow: hidden;
}

.card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
}

.card-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    border-bottom: none;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
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

/* Enhanced Button Styling */
.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
    border: none;
    font-size: 0.85rem;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-sm {
    padding: 0.4rem 0.8rem;
    font-size: 0.8rem;
}

/* QR Scanner Container Enhancement */
.scanner-container {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: var(--shadow-lg);
    margin: 2rem auto;
    max-width: 700px;
    transition: all 0.3s ease;
}

.scanner-container:hover {
    transform: translateY(-4px);
    box-shadow: 0 2rem 4rem rgba(0, 0, 0, 0.2);
}

#qr-reader-container {
    position: relative;
    width: 100%;
    max-width: 600px;
    height: 400px;
    background: linear-gradient(135deg, #000 0%, #1a1a1a 100%);
    overflow: hidden;
    margin: 0 auto;
    border-radius: 20px;
    box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.5);
    border: 3px solid var(--primary-color);
}

#qr-reader {
    width: 100%;
    height: 100%;
    overflow: hidden;
    border-radius: inherit;
    display: flex;
    align-items: center;
    justify-content: center;
}

#qr-reader video {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    border-radius: inherit;
}

/* Scanner Controls Enhancement */
.scanner-controls {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
    display: flex;
    gap: 12px;
}

.scanner-btn {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 3px solid rgba(255, 255, 255, 0.8);
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    color: white;
    font-size: 1.2rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.scanner-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: white;
    transform: scale(1.1);
    color: white;
}

.scanner-btn:focus {
    outline: none;
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
}

/* Scanner Info Panel */
.scanner-info {
    text-align: center;
    margin-top: 2rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8f9fc 0%, #e3e6f0 100%);
    border-radius: 12px;
    border-left: 4px solid var(--primary-color);
}

.scanner-info h5 {
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 1rem;
}

.scanner-info p {
    color: var(--dark-color);
    margin-bottom: 0.5rem;
}

/* Status Indicators */
.status-indicator {
    position: absolute;
    top: 20px;
    right: 20px;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    z-index: 10;
}

.status-ready {
    background: rgba(28, 200, 138, 0.9);
    color: white;
}

.status-scanning {
    background: rgba(246, 194, 62, 0.9);
    color: white;
}

.status-error {
    background: rgba(231, 74, 59, 0.9);
    color: white;
}

/* Scanner Overlay */
.scanner-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 200px;
    height: 200px;
    border: 2px solid rgba(78, 115, 223, 0.8);
    border-radius: 12px;
    z-index: 5;
    pointer-events: none;
}

.scanner-overlay::before,
.scanner-overlay::after,
.scanner-overlay .corner::before,
.scanner-overlay .corner::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    border: 3px solid var(--primary-color);
}

.scanner-overlay::before {
    top: -3px;
    left: -3px;
    border-right: none;
    border-bottom: none;
}

.scanner-overlay::after {
    top: -3px;
    right: -3px;
    border-left: none;
    border-bottom: none;
}

.scanner-overlay .corner:nth-child(1)::before {
    bottom: -3px;
    left: -3px;
    border-right: none;
    border-top: none;
}

.scanner-overlay .corner:nth-child(1)::after {
    bottom: -3px;
    right: -3px;
    border-left: none;
    border-top: none;
}

/* Animation Classes */
.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(78, 115, 223, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(78, 115, 223, 0); }
    100% { box-shadow: 0 0 0 0 rgba(78, 115, 223, 0); }
}

/* Tab Enhancement */
.nav-tabs {
    border-bottom: 2px solid var(--border-color);
}

.nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    color: var(--dark-color);
    font-weight: 500;
    padding: 1rem 1.5rem;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link:hover {
    border-color: var(--primary-color);
    color: var(--primary-color);
}

.nav-tabs .nav-link.active {
    background: none;
    border-color: var(--primary-color);
    color: var(--primary-color);
    font-weight: 600;
}

/* Form Enhancement */
.form-control {
    border-radius: 8px;
    border: 2px solid var(--border-color);
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.form-control[readonly] {
    background-color: #f8f9fc;
    border-color: #e3e6f0;
}

/* Test QR Code Styling */
.test-qr-code {
    display: inline-block;
    padding: 10px;
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
}

.test-qr-code:hover {
    transform: scale(1.05);
    box-shadow: var(--shadow-lg);
}

/* Responsive Design */
@media (max-width: 768px) {
    .scanner-container {
        margin: 1rem;
        padding: 1rem;
    }

    #qr-reader-container {
        height: 300px;
        border-radius: 16px;
    }

    .scanner-btn {
        width: 50px;
        height: 50px;
        font-size: 1rem;
    }

    .page-header h1 {
        font-size: 1.5rem;
    }

    .page-header p {
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    #qr-reader-container {
        height: 250px;
        border-radius: 12px;
    }

    .scanner-controls {
        gap: 8px;
    }

    .scanner-btn {
        width: 45px;
        height: 45px;
        font-size: 0.9rem;
    }
}

/* Hide default QR reader elements */
#qr-reader__dashboard_section_csr,
#qr-reader__dashboard_section_swaplink {
    display: none !important;
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
    
    // Initialize scanner
    initializeScanner();
});

function initializeScanner() {
    html5QrCode = new Html5Qrcode("qr-reader");
    
    const config = {
        fps: 10,
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0
    };
    
    // Start scanning automatically
    html5QrCode.start(
        { facingMode: "environment" },
        config,
        onScanSuccess,
        onScanFailure
    ).then(() => {
        isScanning = true;
        updateStatus('Scanning...', 'scanning');
    }).catch(err => {
        console.error('Failed to start scanner:', err);
        updateStatus('Failed to start scanner', 'error');
    });
}

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

function onScanSuccess(decodedText, decodedResult) {
    console.log('QR Code scanned:', decodedText);
    
    // Update status
    updateStatus('QR Code detected!', 'success');
    
    // Process the scanned data
    viewLivestockDetails(decodedText);
    
    // Reset status after 2 seconds
    setTimeout(() => {
        updateStatus('Scanning...', 'scanning');
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
        
        // Show the modal
        $('#livestockDetailsModal').modal('show');
        
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

// Handle file upload for QR codes
document.getElementById('uploadQR').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Here you would implement QR code reading from image file
        // For now, we'll simulate a successful scan
        const simulatedId = 'LS001'; // You can change this to test different IDs
        viewLivestockDetails(simulatedId);
    }
});
</script>
@endpush

