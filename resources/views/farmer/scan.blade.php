@extends('layouts.app')

@section('title', 'LBDAIRY: Farmers - Scan Livestock')

@section('content')
<!-- Page Header -->
<div class="page bg-white shadow-md rounded p-4 mb-4 fade-in">
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

        <!-- How to Use Button - Upper Right -->
        <div class="how-to-use-button">
            <button class="btn btn-outline-light btn-sm" onclick="toggleHowToUse()" id="howToUseBtn">
                <i class="fas fa-info-circle"></i> How to Use
            </button>
        </div>

        <!-- Scanner Overlay -->
        <div class="scanner-overlay">
            <div class="corner"></div>
        </div>

        <!-- Camera feed -->
        <div id="qr-reader"></div>
    </div>

    <!-- Scanner Controls - Now outside the scanner container -->
    <div class="scanner-controls">
        <button id="captureButton" type="button" class="scanner-btn" onclick="toggleCamera()" title="Start/Stop Camera">
            <i class="fas fa-camera"></i>
        </button>
        <label for="uploadQR" class="scanner-btn mb-0" title="Upload QR Image">
            <i class="fas fa-upload"></i>
        </label>
        <input type="file" id="uploadQR" accept="image/*,.jpg,.jpeg,.png,.gif,.bmp,.webp" style="display: none;">
        <button id="manualInputBtn" type="button" class="scanner-btn" onclick="showManualInput()" title="Manual Input">
            <i class="fas fa-keyboard"></i>
        </button>
    </div>


    <!-- Scan Results -->
    <div id="qr-reader-results" class="mt-3 text-center" style="font-size: 1.1rem; font-weight: 500;"></div>
    
    <!-- Processing Progress -->
    <div id="processing-progress" class="mt-3 text-center" style="display: none;">
        <div class="progress" style="height: 6px; border-radius: 3px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%; background-color: #4e73df;"></div>
        </div>
        <small class="text-muted mt-2 d-block">Processing QR code...</small>
    </div>
</div>

<!-- Manual Input Modal -->
<div class="modal fade admin-modal" id="manualInputModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content smart-form text-center p-4">

      <!-- Icon + Header -->
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-circle">
          <i class="fas fa-keyboard fa-2x"></i>
        </div>
        <h5 class="fw-bold mb-1">Manual Livestock ID Input</h5>
        <p class="text-muted mb-0 small">
          Enter the livestock ID or tag number manually if QR code scanning is not working.
        </p>
      </div>

      <!-- Form -->
      <form id="manualInputForm">
        <div class="form-wrapper text-start mx-auto">
          <!-- Livestock ID / Tag -->
          <div class="col-md-12">
            <label for="manualLivestockId" class="fw-semibold">Livestock ID / Tag Number <span class="text-danger">*</span></label>
            <input 
              type="text" 
              class="form-control mt-1" 
              id="manualLivestockId" 
              placeholder="Enter livestock ID or tag number" 
              autofocus 
              required>
          </div>
        </div>

        <!-- Footer Buttons -->
        <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
          <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn-modern btn-ok" onclick="processManualInput()">
            <i class="fas fa-search"></i> Search
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade admin-modal" id="addLivestockModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content smart-form text-center p-4">
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-circle">
          <i class="fas fa-paw fa-2x"></i>
        </div>
        <h5 class="fw-bold mb-1">Add Livestock</h5>
      </div>
      <form id="addLivestockForm">
        @csrf
        <div class="form-wrapper text-start mx-auto">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="al_tag_number" class="fw-semibold">Tag Number</label>
              <input type="text" class="form-control" id="al_tag_number" name="tag_number" required>
            </div>
            <div class="col-md-6">
              <label for="al_name" class="fw-semibold">Name</label>
              <input type="text" class="form-control" id="al_name" name="name" required>
            </div>
            <div class="col-md-6">
              <label for="al_type" class="fw-semibold">Type</label>
              <input type="text" id="al_type" class="form-control" name="type" placeholder="e.g., Cow" required>
            </div>
            <div class="col-md-6">
              <label for="al_breed" class="fw-semibold">Breed</label>
              <input type="text" id="al_breed" class="form-control" name="breed" placeholder="e.g., Holstein" required>
            </div>
            <div class="col-md-6">
              <label for="al_birth_date" class="fw-semibold">Birth Date</label>
              <input type="date" class="form-control" id="al_birth_date" name="birth_date" required>
            </div>
            <div class="col-md-6">
              <label for="al_gender" class="fw-semibold">Gender</label>
              <select id="al_gender" class="form-control" name="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="al_weight" class="fw-semibold">Weight (kg)</label>
              <input type="number" step="0.01" class="form-control" id="al_weight" name="weight">
            </div>
            <div class="col-md-6">
              <label for="al_health_status" class="fw-semibold">Health Status</label>
              <select id="al_health_status" class="form-control" name="health_status" required>
                <option value="healthy">Healthy</option>
                <option value="sick">Sick</option>
                <option value="recovering">Recovering</option>
                <option value="under_treatment">Under Treatment</option>
              </select>
            </div>
            <div class="col-md-12">
              <label for="al_status" class="fw-semibold">Status</label>
              <select id="al_status" class="form-control" name="status" required>
                <option value="active" selected>Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
          </div>
          <div id="al_formNotification" class="mt-2" style="display: none;"></div>
        </div>
        <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
          <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-modern btn-ok">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- SMART DETAIL MODAL - Livestock Details -->
<div class="modal fade admin" id="livestockDetailsModal" tabindex="-1" role="dialog" aria-labelledby="livestockDetailsLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content smart-details p-4">

      <!-- Header -->
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-circle">
          <i class="fas fa-paw fa-2x"></i>
        </div>
        <h5 class="fw-bold mb-1">Livestock Details</h5>
        <span id="detailLivestockId"></span>
        <p class="text-muted small mb-0">Below are the detailed records of the selected livestock.</p>
      </div>

      <!-- BODY -->
      <div class="modal-body">

        <!-- OPTION BAR / NAV TABS -->
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

        <!-- TAB CONTENT -->
        <div class="tab-content" id="livestockTabContent">

          <!-- BASIC INFO TAB (READ-ONLY) -->
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

          <!-- GROWTH TAB (EDITABLE) -->
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
                <button type="submit" class="btn-action btn-action-ok btn-sm">
                  Save Record
                </button>
              </div>
            </form>
          </div>

          <!-- MILK TAB (EDITABLE) -->
          <div class="tab-pane fade" id="milkForm" role="tabpanel" aria-labelledby="milk-tab">
            <form id="milkDetailsForm">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <tbody>
                    <tr><th>Date of Calving</th><td><input type="date" class="form-control" id="calvingDate" required></td></tr>
                    <tr><th>Calf ID Number</th><td><input type="text" class="form-control" id="calfIdNumber" required></td></tr>
                    <tr><th>Sex</th><td>
                      <select class="form-control" id="calfSex" required>
                        <option value="" disabled selected>Select Sex</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                      </select>
                    </td></tr>
                    <tr><th>Breed</th><td><input type="text" class="form-control" id="calfBreed" required></td></tr>
                    <tr><th>Sire ID Number</th><td><input type="text" class="form-control" id="sireIdNumber" required></td></tr>
                    <tr><th>Milk Production (liters)</th><td><input type="number" step="0.01" class="form-control" id="milkProduction" required></td></tr>
                    <tr><th>Days in Milk</th><td><input type="number" class="form-control" id="daysInMilk" required></td></tr>
                  </tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn-action btn-action-ok btn-sm">
                  Save Record
                </button>
              </div>
            </form>
          </div>

          <!-- BREEDING TAB -->
          <div class="tab-pane fade" id="breedingForm" role="tabpanel" aria-labelledby="breeding-tab">
            <form id="breedingDetailsForm">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <tbody>
                    <tr><th>Breeding Date</th><td><input type="date" class="form-control" id="breedingDate" value="2023-04-15" readonly></td></tr>
                    <tr><th>Breeding Type</th><td>
                      <select class="form-control" id="breedingType" readonly>
                        <option value="Natural" selected>Natural</option>
                        <option value="Artificial Insemination">Artificial Insemination</option>
                      </select>
                    </td></tr>
                    <tr><th>Sire Registry ID</th><td><input type="text" class="form-control" id="breedingSireId" value="SR123" readonly></td></tr>
                    <tr><th>Dam Registry ID</th><td><input type="text" class="form-control" id="breedingDamId" value="DR456" readonly></td></tr>
                    <tr><th>Pregnancy Check Date</th><td><input type="date" class="form-control" id="pregnancyCheckDate" value="2023-05-01" readonly></td></tr>
                    <tr><th>Pregnancy Result</th><td>
                      <select class="form-control" id="pregnancyResult" readonly>
                        <option value="Positive" selected>Positive</option>
                        <option value="Negative">Negative</option>
                        <option value="Unknown">Unknown</option>
                      </select>
                    </td></tr>
                    <tr><th>Remarks</th><td><textarea class="form-control" id="breedingRemarks" readonly>Healthy pregnancy confirmed.</textarea></td></tr>
                  </tbody>
                </table>
              </div>
            </form>
          </div>

          <!-- HEALTH TAB -->
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

        </div> <!-- END TAB CONTENT -->
      </div> <!-- END MODAL BODY -->
        <!-- Footer -->
        <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
            <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
        </div>

    </div>
  </div>
</div>



<!-- How to Use Modal -->
<div class="modal fade " id="howToUseModal" tabindex="-1" role="dialog" aria-labelledby="howToUseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content smart-detail p-4">

      <!-- Icon + Header -->
      <div class="d-flex flex-column align-items-center mb-4">
        <div class="icon-circle">
          <i class="fas fa-info-circle fa-2x"></i>
        </div>
        <h5 class="fw-bold mb-1">How to Use QR Scanner</h5>
        <p class="text-muted mb-0 small text-center">
          Learn the different ways to scan or input QR codes below.
        </p>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <div class="row gy-3 text-center">
          <!-- Camera Scan -->
          <div class="col-md-6">
            <div class="info-card h-100">
              <h6><i class="fas fa-camera mr-2"></i>Camera Scan</h6>
              <p>Point your camera at a QR code to scan automatically.</p>
            </div>
          </div>

          <!-- Upload Image -->
          <div class="col-md-6">
            <div class="info-card h-100">
              <h6><i class="fas fa-upload mr-2"></i>Upload Image</h6>
              <p>Upload a QR code image file (JPG, PNG, GIF, BMP, WebP).</p>
            </div>
          </div>

         <!-- Manual Input -->
<div class="col-md-6 mb-3">
  <div class="info-card h-100">
    <h6><i class="fas fa-keyboard mr-2"></i>Manual Input</h6>
    <p>Click the keyboard icon (⌨️) to enter livestock ID manually.</p>
  </div>
</div>

<!-- Tips for Better Scanning -->
<div class="col-md-6 mb-3">
  <div class="info-card h-100">
    <h6><i class="fas fa-lightbulb mr-2"></i>Tips for Better Scanning</h6>
    <ul class="mb-0 info-list">
      <li>Ensure QR codes are clear and well-lit.</li>
      <li>Hold the camera steady and at a proper distance.</li>
      <li>If scanning fails, use manual input instead.</li>
      <li>Supported formats: JPG, PNG, GIF, BMP, WebP (max 5MB).</li>
    </ul>
  </div>
</div>


        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer justify-content-center mt-4">
        <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Got it</button>
      </div>
    </div>
  </div>
</div>


@endsection

@push('styles')
<style>
  /* Make nav tabs responsive and scrollable on small screens */
@media (max-width: 768px) {
  #livestockTab {
    display: flex;
    flex-wrap: nowrap;          /* prevent wrapping */
    overflow-x: auto;           /* allow horizontal scroll */
    white-space: nowrap;        /* keep text inline */
    -webkit-overflow-scrolling: touch; /* smooth scroll on iOS */
    gap: 4px;
  }

  #livestockTab::-webkit-scrollbar {
    height: 6px; /* small horizontal scrollbar */
  }

  #livestockTab::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 10px;
  }

  #livestockTab .nav-item {
    flex: 0 0 auto; /* don't shrink items */
  }

  #livestockTab .nav-link {
    font-size: 0.9rem;
    padding: 8px 10px;
  }

  #livestockTab .nav-link i {
    margin-right: 4px;
    font-size: 0.9rem;
  }
}

    /* ============================
   SMART DETAIL MODAL (Livestock)
   ============================ */
/* ============================
   BUTTONS
   ============================ */
.btn-approve,
.btn-delete,
.btn-ok {
  font-weight: 600;
  border: none;
  border-radius: 10px;
  padding: 10px 24px;
  transition: all 0.2s ease-in-out;
}

.btn-approves {
  background: #387057;
  color: #fff;
}
.btn-approves:hover {
  background: #fca700;
  color: #fff;
}
.btn-cancel {
  background: #387057;
  color: #fff;
}
.btn-cancel:hover {
  background: #fca700;
  color: #fff;
}

.btn-delete {
  background: #dc3545;
  color: #fff;
}
.btn-delete:hover {
  background: #fca700;
  color: #fff;
}

.btn-ok {
  background: #18375d;
  color: #fff;
}
.btn-ok:hover {
  background: #fca700;
  color: #fff;
}
/* General Modal Styling */
.smart-detail {
  border: none;
  border-radius: 18px;
  box-shadow: 0 10px 35px rgba(0, 0, 0, 0.12);
  background-color: #ffffff;
  transition: all 0.3s ease;
  max-height: 100vh;
  overflow-y: auto;
}

.smart-detail::-webkit-scrollbar {
  width: 8px;
}
.smart-detail::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.15);
  border-radius: 6px;
}

/* Make Modal Taller and Better Layout */
.modal-dialog.modal-xl {
  max-width: 1200px;
}

.modal-content.smart-detail {
  padding: 2rem 2.5rem;
}

/* Header Icon Circle */
.icon-circle {
  width: 65px;
  height: 65px;
  background: #18375d;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  box-shadow: 0 6px 20px rgba(24, 55, 93, 0.2);
  margin-bottom: 1rem;
}


/* Table Styling */
.table {
  border-collapse: separate;
  border-spacing: 0 6px;
}

.table th {
  width: 30%;
  background-color: #f8fafc;
  color: #18375d;
  vertical-align: middle;
  font-weight: 600;
  padding: 0.75rem;
  border-top: 1px solid #dee2e6;
}

.table td {
  padding: 0.75rem;
  vertical-align: middle;
  background-color: #ffffff;
  border-top: 1px solid #dee2e6;
}

/* Input Fields */
.smart-detail .form-control,
.smart-detail select,
.smart-detail textarea {
  border-radius: 8px;
  border: 1px solid #d5d9e0;
  box-shadow: none;
  transition: all 0.2s ease;
}

.smart-detail .form-control:focus,
.smart-detailm select:focus,
.smart-detail textarea:focus {
  border-color: #18375d;
  box-shadow: 0 0 0 0.15rem rgba(24, 55, 93, 0.25);
}


/* Responsive Adjustments */
@media (max-width: 992px) {
  .modal-dialog.modal-xl {
    max-width: 95%;
  }
  .smart-form {
    padding: 1.5rem;
  }
  .table th {
    width: 40%;
  }
}

    /* ============================
   SMART FORM - Enhanced Version
   ============================ */
.smart-form {
  border: none;
  border-radius: 22px; /* slightly more rounded */
  box-shadow: 0 15px 45px rgba(0, 0, 0, 0.15);
  background-color: #ffffff;
  padding: 3rem 3.5rem; /* bigger spacing */
  transition: all 0.3s ease;
  max-width: 900px; /* slightly wider form container */
  margin: 2rem auto;
}

.smart-form:hover {
  box-shadow: 0 18px 55px rgba(0, 0, 0, 0.18);
}

/* Header Icon */
.smart-form .icon-circle {
  width: 60px;
    height: 60px;
    background-color: #e8f0fe;
    color: #18375d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

/* Titles & Paragraphs */
.smart-form h5 {
  color: #18375d;
  font-weight: 700;
  margin-bottom: 0.4rem;
  letter-spacing: 0.5px;
}

.smart-form p {
  color: #6b7280;
  font-size: 0.96rem;
  margin-bottom: 1.8rem;
  line-height: 1.5;
}

/* Form Container */
.smart-form .form-wrapper {
  max-width: 720px;
  margin: 0 auto;
}

/* ============================
   FORM ELEMENT STYLES
   ============================ */
#livestockDetailsModal form {
  text-align: left;
}

#livestockDetailsModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#livestockDetailsModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#livestockDetailsModal .form-control,
#livestockDetailsModal select.form-control,
#livestockDetailsModal textarea.form-control {
  border-radius: 12px;
  border: 1px solid #d1d5db;
  padding: 12px 15px;          /* consistent padding */
  font-size: 15px;             /* consistent font */
  line-height: 1.5;
  transition: all 0.2s ease;
  width: 100%;
  height: 46px;                /* unified height */
  box-sizing: border-box;
  margin-top: 0.5rem;
  margin-bottom: 1rem;
  background-color: #fff;
}

/* Keep textarea resizable but visually aligned */
#livestockDetailsModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#livestockDetailsModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}


#editLivestockModal form {
  text-align: left;
}

#editLivestockModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#editLivestockModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#editLivestockModal .form-control,
#editLivestockModal select.form-control,
#editLivestockModal textarea.form-control {
  border-radius: 12px;
  border: 1px solid #d1d5db;
  padding: 12px 15px;          /* consistent padding */
  font-size: 15px;             /* consistent font */
  line-height: 1.5;
  transition: all 0.2s ease;
  width: 100%;
  height: 46px;                /* unified height */
  box-sizing: border-box;
  margin-top: 0.5rem;
  margin-bottom: 1rem;
  background-color: #fff;
}

/* Keep textarea resizable but visually aligned */
#editLivestockModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#editLivestockModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}
/* ============================
   FORM ELEMENT STYLES
   ============================ */
#addLivestockModal form {
  text-align: left;
}

#addLivestockModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#addLivestockModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#addLivestockModal .form-control,
#addLivestockModal select.form-control,
#addLivestockModal textarea.form-control {
  border-radius: 12px;
  border: 1px solid #d1d5db;
  padding: 12px 15px;          /* consistent padding */
  font-size: 15px;             /* consistent font */
  line-height: 1.5;
  transition: all 0.2s ease;
  width: 100%;
  height: 46px;                /* unified height */
  box-sizing: border-box;
  margin-top: 0.5rem;
  margin-bottom: 1rem;
  background-color: #fff;
}

/* Keep textarea resizable but visually aligned */
#addLivestockModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#addLivestockModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}

/* ============================
   CRITICAL FIX FOR DROPDOWN TEXT CUTTING
   ============================ */
.admin-modal select.form-control,
.modal.admin-modal select.form-control,
.admin-modal .modal-body select.form-control {
  min-width: 250px !important;
  width: 100% !important;
  max-width: 100% !important;
  box-sizing: border-box !important;
  padding: 12px 15px !important;  /* match input padding */
  white-space: nowrap !important;
  text-overflow: clip !important;
  overflow: visible !important;
  font-size: 15px !important;     /* match input font */
  line-height: 1.5 !important;
  height: 46px !important;        /* same height as input */
  background-color: #fff !important;
}

/* Ensure columns don't constrain dropdowns */
.admin-modal .col-md-6 {
  min-width: 280px !important;
  overflow: visible !important;
}

/* Prevent modal body from clipping dropdowns */
.admin-modal .modal-body {
  overflow: visible !important;
}

/* ============================
   BUTTONS
   ============================ */
.btn-approve,
.btn-delete,
.btn-ok {
  font-weight: 600;
  border: none;
  border-radius: 10px;
  padding: 10px 24px;
  transition: all 0.2s ease-in-out;
}

.btn-approves {
  background: #387057;
  color: #fff;
}
.btn-approves:hover {
  background: #fca700;
  color: #fff;
}
.btn-cancel {
  background: #387057;
  color: #fff;
}
.btn-cancel:hover {
  background: #fca700;
  color: #fff;
}

.btn-delete {
  background: #dc3545;
  color: #fff;
}
.btn-delete:hover {
  background: #fca700;
  color: #fff;
}

.btn-ok {
  background: #18375d;
  color: #fff;
}
.btn-ok:hover {
  background: #fca700;
  color: #fff;
}

/* ============================
   FOOTER & ALIGNMENT
   ============================ */
#livestockDetailsModal .modal-footer {
  text-align: center;
  border-top: 1px solid #e5e7eb;
  padding-top: 1.25rem;
  margin-top: 1.5rem;
}

/* ============================
   RESPONSIVE DESIGN
   ============================ */
@media (max-width: 768px) {
  .smart-form {
    padding: 1.5rem;
  }

  .smart-form .form-wrapper {
    max-width: 100%;
  }

  #livestockDetailsModal .form-control {
    font-size: 14px;
  }

  #editLivestockModal .form-control {
    font-size: 14px;
  }
   #issueAlertModal .form-control {
    font-size: 14px;
  }

  .btn-ok,
  .btn-delete,
  .btn-approves {
    width: 100%;
    margin-top: 0.5rem;
  }
}
    /* SMART DETAIL MODAL TEMPLATE */
.smart-detail .modal-content {
    border-radius: 1.5rem;
    border: none;
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.12);
    background-color: #fff;
    transition: all 0.3s ease-in-out;
}

/* Center alignment for header section */
.smart-detail .modal-header,
.smart-detail .modal-footer {
    text-align: center;
}

/* Icon Header */
.smart-detail .icon-circle {
    width: 60px;
    height: 60px;
    background-color: #e8f0fe;
    color: #18375d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

/* Titles & Paragraphs */
.smart-details h5 {
    color: #18375d;
    font-weight: 700;
    margin-bottom: 0.4rem;
    letter-spacing: 0.5px;
}

.smart-details p {
    color: #6b7280;
    font-size: 1rem;
    margin-bottom: 1.8rem;
    line-height: 1.6;
    text-align: left; /* ensures proper centering */
}

/* MODAL BODY */
.smart-details .modal-body {
    background: #ffffff;
    padding: 3rem 3.5rem; /* more spacious layout */
    border-radius: 1rem;
    max-height: 88vh; /* taller for longer content */
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

/* Wider modal container */
.smart-details .modal-dialog {
    max-width: 92%; /* slightly wider modal */
    width: 100%;
    margin: 1.75rem auto;
}

/* Detail Section */
.smart-details .detail-wrapper {
    background: #f9fafb;
    border-radius: 1.25rem;
    padding: 2.25rem; /* more inner padding */
    font-size: 1rem;
    line-height: 1.65;
}

/* Detail Rows */
.smart-details .detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px dashed #ddd;
    padding: 0.6rem 0;
}

.smart-details .detail-row:last-child {
    border-bottom: none;
}

.smart-details .detail-label {
    font-weight: 600;
    color: #1b3043;
}

.smart-details .detail-value {
    color: #333;
    text-align: right;
}

/* Footer */
#livestockDetailsModal .modal-footer {
    text-align: center;
    border-top: 1px solid #e5e7eb;
    padding-top: 1.5rem;
    margin-top: 2rem;
}

/* RESPONSIVE ADJUSTMENTS */
@media (max-width: 992px) {
    .smart-details .modal-dialog {
        max-width: 95%;
    }

    .smart-details .modal-body {
        padding: 2rem;
        max-height: 82vh;
    }

    .smart-details .detail-wrapper {
        padding: 1.5rem;
        font-size: 0.95rem;
    }

    .smart-details p {
        text-align: center;
        font-size: 0.95rem;
    }
}

@media (max-width: 576px) {
    .smart-details .modal-body {
        padding: 0.5rem;
        max-height: 95vh;
    }

    .smart-details .detail-wrapper {
        padding: 1.25rem;
    }

    .smart-details .detail-row {
        flex-direction: column;
        text-align: left;
        gap: 0.3rem;
    }

    .smart-details .detail-value {
        text-align: left;
    }
}

    /* 🌟 Page Header Styling */
.page {
    background-color: #18375d;
    border-radius: 12px;
    padding: 1.5rem 2rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease-in-out;
    animation: fadeIn 0.6s ease-in-out;
}

/* Hover lift effect for interactivity */
.page:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12);
}

/* 🧭 Header Title */
.page h1 {
    color: #18375d;
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Icon style */
.page i {
    color: #18375d; /* Bootstrap primary color */
}

/* 💬 Subtitle text */
.page p {
    color: #18375d;
    font-size: 1rem;
    margin: 0;
}

/* ✨ Fade-in Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
}

/* Top Section (Header inside card-body) */
.card-body:first-of-type {
    background-color: #ffffff;
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    border-top-left-radius: 0.75rem;
    border-top-right-radius: 0.75rem;
    padding: 1rem 1.5rem;
}

/* Title (h6) */
.card-body:first-of-type h6 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 700;
    color: #18375d !important;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Second Card Body (Main Content) */
.card-body:last-of-type {
    background-color: #ffffff;
    padding: 1.25rem 1.5rem;
    border-bottom-left-radius: 0.75rem;
    border-bottom-right-radius: 0.75rem;
}
    /* Adds clean spacing between info cards */
.row.gy-3 > [class*='col-'] {
  margin-bottom: 1rem;
}
 .info-card {
  background: #ffffff;
  border: 1px solid #e0e6ed;
  border-radius: 14px;
  box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
  padding: 1.5rem;
  transition: all 0.3s ease;
  text-align: center;
}

.info-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
}

.info-card h6 {
  font-weight: 600;
  color: #18375d;
  margin-bottom: 0.5rem;
}

.info-card p {
  color: #6c757d;
  font-size: 0.95rem;
  line-height: 1.6;
  margin-bottom: 0;
}

/* Make list match paragraph style */
.info-card .info-list {
  color: #6c757d;
  font-size: 0.95rem;
  line-height: 1.6;
  text-align: left;
  padding-left: 1.2rem;
  margin-top: 0.5rem;
}

.info-card .info-list li {
  margin-bottom: 0.3rem;
}

/* Responsive adjustments for small screens */
@media (max-width: 576px) {
  .info-card {
    padding: 1.25rem;
    text-align: left;
  }

  .info-card h6 {
    font-size: 1rem;
    margin-bottom: 0.4rem;
  }
}

    /* ============================
   SMART FORM - Enhanced Version
   ============================ */
.smart-form {
  border: none;
  border-radius: 22px; /* slightly more rounded */
  box-shadow: 0 15px 45px rgba(0, 0, 0, 0.15);
  background-color: #ffffff;
  padding: 3rem 3.5rem; /* bigger spacing */
  transition: all 0.3s ease;
  max-width: 900px; /* slightly wider form container */
  margin: 2rem auto;
}

.smart-form:hover {
  box-shadow: 0 18px 55px rgba(0, 0, 0, 0.18);
}

/* Header Icon */
.smart-form .icon-circle {
  width: 60px;
    height: 60px;
    background-color: #e8f0fe;
    color: #18375d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

/* Titles & Paragraphs */
.smart-form h5 {
  color: #18375d;
  font-weight: 700;
  margin-bottom: 0.4rem;
  letter-spacing: 0.5px;
}

.smart-form p {
  color: #6b7280;
  font-size: 0.96rem;
  margin-bottom: 1.8rem;
  line-height: 1.5;
}

/* Form Container */
.smart-form .form-wrapper {
  max-width: 720px;
  margin: 0 auto;
}

/* ============================
   FORM ELEMENT STYLES
   ============================ */
#manualInputModal form {
  text-align: left;
}

#manualInputModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#manualInputModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#manualInputModal .form-control,
#manualInputModal select.form-control,
#manualInputModal textarea.form-control {
  border-radius: 12px;
  border: 1px solid #d1d5db;
  padding: 12px 15px;          /* consistent padding */
  font-size: 15px;             /* consistent font */
  line-height: 1.5;
  transition: all 0.2s ease;
  width: 100%;
  height: 46px;                /* unified height */
  box-sizing: border-box;
  margin-top: 0.5rem;
  margin-bottom: 1rem;
  background-color: #fff;
}

/* Keep textarea resizable but visually aligned */
#manualInputModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#manualInputModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}


#editLivestockModal form {
  text-align: left;
}

#editLivestockModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#editLivestockModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#editLivestockModal .form-control,
#editLivestockModal select.form-control,
#editLivestockModal textarea.form-control {
  border-radius: 12px;
  border: 1px solid #d1d5db;
  padding: 12px 15px;          /* consistent padding */
  font-size: 15px;             /* consistent font */
  line-height: 1.5;
  transition: all 0.2s ease;
  width: 100%;
  height: 46px;                /* unified height */
  box-sizing: border-box;
  margin-top: 0.5rem;
  margin-bottom: 1rem;
  background-color: #fff;
}

/* Keep textarea resizable but visually aligned */
#editLivestockModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#editLivestockModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}
/* ============================
   FORM ELEMENT STYLES
   ============================ */
#issueAlertModal form {
  text-align: left;
}

#issueAlertModal .form-group {
  width: 100%;
  margin-bottom: 1.2rem;
}

#issueAlertModal label {
  font-weight: 600;            /* make labels bold */
  color: #18375d;              /* consistent primary blue */
  display: inline-block;
  margin-bottom: 0.5rem;
}

/* Unified input + select + textarea styles */
#issueAlertModal .form-control,
#issueAlertModal select.form-control,
#issueAlertModal textarea.form-control {
  border-radius: 12px;
  border: 1px solid #d1d5db;
  padding: 12px 15px;          /* consistent padding */
  font-size: 15px;             /* consistent font */
  line-height: 1.5;
  transition: all 0.2s ease;
  width: 100%;
  height: 46px;                /* unified height */
  box-sizing: border-box;
  margin-top: 0.5rem;
  margin-bottom: 1rem;
  background-color: #fff;
}

/* Keep textarea resizable but visually aligned */
#issueAlertModal textarea.form-control {
  min-height: 100px;
  height: auto;                /* flexible height for textarea */
}

/* Focus state */
#issueAlertModal .form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}

/* ============================
   CRITICAL FIX FOR DROPDOWN TEXT CUTTING
   ============================ */
.admin-modal select.form-control,
.modal.admin-modal select.form-control,
.admin-modal .modal-body select.form-control {
  min-width: 250px !important;
  width: 100% !important;
  max-width: 100% !important;
  box-sizing: border-box !important;
  padding: 12px 15px !important;  /* match input padding */
  white-space: nowrap !important;
  text-overflow: clip !important;
  overflow: visible !important;
  font-size: 15px !important;     /* match input font */
  line-height: 1.5 !important;
  height: 46px !important;        /* same height as input */
  background-color: #fff !important;
}

/* Ensure columns don't constrain dropdowns */
.admin-modal .col-md-6 {
  min-width: 280px !important;
  overflow: visible !important;
}

/* Prevent modal body from clipping dropdowns */
.admin-modal .modal-body {
  overflow: visible !important;
}

/* ============================
   BUTTONS
   ============================ */
.btn-approve,
.btn-delete,
.btn-ok {
  font-weight: 600;
  border: none;
  border-radius: 10px;
  padding: 10px 24px;
  transition: all 0.2s ease-in-out;
}

.btn-approves {
  background: #387057;
  color: #fff;
}
.btn-approves:hover {
  background: #fca700;
  color: #fff;
}
.btn-cancel {
  background: #387057;
  color: #fff;
}
.btn-cancel:hover {
  background: #fca700;
  color: #fff;
}

.btn-delete {
  background: #dc3545;
  color: #fff;
}
.btn-delete:hover {
  background: #fca700;
  color: #fff;
}

.btn-ok {
  background: #18375d;
  color: #fff;
}
.btn-ok:hover {
  background: #fca700;
  color: #fff;
}

/* ============================
   FOOTER & ALIGNMENT
   ============================ */
#addLivestockModal .modal-footer {
  text-align: center;
  border-top: 1px solid #e5e7eb;
  padding-top: 1.25rem;
  margin-top: 1.5rem;
}

/* ============================
   RESPONSIVE DESIGN
   ============================ */
@media (max-width: 768px) {
  .smart-form {
    padding: 1.5rem;
  }

  .smart-form .form-wrapper {
    max-width: 100%;
  }

  #addLivestockModal .form-control {
    font-size: 14px;
  }

  #editLivestockModal .form-control {
    font-size: 14px;
  }
   #issueAlertModal .form-control {
    font-size: 14px;
  }

  .btn-ok,
  .btn-delete,
  .btn-approves {
    width: 100%;
    margin-top: 0.5rem;
  }
}
    /* SMART MODAL CSS */
    .smart-modal {
  border: none;
  border-radius: 16px;
  background: #fff;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  max-width: 500px;
  margin: auto;
  transition: all 0.3s ease;
}

.smart-modal .icon-circle {
  width: 55px;
    height: 55px;
    background-color: #e8f0fe;
    color: #18375d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.smart-modal h5 {
  color: #18375d;
  font-weight: 600;
}

.smart-modal p {
  color: #6b7280;
  font-size: 0.95rem;
}
.btn-approve {
  background: #387057;
  color: #fff;
  border: none;
}
.btn-approve:hover {
  background: #fca700;
}
.btn-delete {
  background: #dc3545;
  color: #fff;
  border: none;
}
.btn-delete:hover {
  background: #fca700;
}
.btn-ok {
  background: #18375d;
  color: #fff;
  border: none;
}
.btn-ok:hover {
  background: #fca700;
}
    /* SMART DETAIL MODAL TEMPLATE */
.smart-detail .modal-content {
    border-radius: 1.5rem;
    border: none;
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.12);
    background-color: #fff;
    transition: all 0.3s ease-in-out;
}

/* Icon Header */
.smart-detail .icon-circle {
    width: 60px;
    height: 60px;
    background-color: #e8f0fe;
    color: #18375d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

/* Titles & Paragraphs */
.smart-detail h5 {
    color: #18375d;
    font-weight: 700;
    margin-bottom: 0.4rem;
    letter-spacing: 0.5px;
}

.smart-detail p {
    color: #6b7280;
    font-size: 0.96rem;
    margin-bottom: 1.8rem;
    line-height: 1.5;
}

/* MODAL BODY */
.smart-detail .modal-body {
    background: #ffffff;
    padding: 1.75rem 2rem;
    border-radius: 1rem;
    max-height: 70vh; /* ensures content scrolls on smaller screens */
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

/* Detail Section */
.smart-detail .detail-wrapper {
    background: #f9fafb;
    border-radius: 1rem;
    padding: 1.5rem;
    font-size: 0.95rem;
}

.smart-detail .detail-row {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px dashed #ddd;
    padding: 0.5rem 0;
}

.smart-detail .detail-row:last-child {
    border-bottom: none;
}

.smart-detail .detail-label {
    font-weight: 600;
    color: #1b3043;
}

.smart-detail .detail-value {
    color: #333;
    text-align: right;
}


     /* Action buttons styling */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        justify-content: center;
        min-width: 200px;
    }
    
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 0.25rem;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
        white-space: nowrap;
    }
    
    .btn-action-edit {
        background-color: #387057;
        border-color: #387057;
        color: white;
    }
    
    .btn-action-edit:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    .btn-action-ok {
        background-color: #18375d;
        border-color: #18375d;
        color: white;
    }
    
    .btn-action-ok:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    .btn-action-deletes {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    .btn-action-deletes:hover {
        background-color: #fca700;
        border-color: #fca700;
        color: white;
    }
    
    #howToUseModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #howToUseModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #howToUseModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #howToUseModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #howToUseModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #howToUseModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #howToUseModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #howToUseModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }
    /*manual*/
    #manualInputModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #manualInputModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #manualInputModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #manualInputModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #manualInputModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #manualInputModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #manualInputModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #manualInputModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }

/* Custom Dark Green Button */
.btn-dark-green {
    background-color: #387057 !important;
    border-color: #387057 !important;
    color: white !important;
    box-shadow: 0 2px 8px rgba(56, 112, 87, 0.3);
}

.btn-dark-green:hover {
    background-color: #2d5a47 !important;
    border-color: #2d5a47 !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(56, 112, 87, 0.4);
}

.btn-dark-green:focus {
    box-shadow: 0 0 0 0.2rem rgba(56, 112, 87, 0.25) !important;
}

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
    position: relative;
    margin-top: 20px;
    display: flex;
    justify-content: center;
    gap: 12px;
    z-index: 10;
}

.scanner-btn {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 2px solid #18375d;
    background: #18375d;
    color: white;
    font-size: 1.2rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(24, 55, 93, 0.3);
}

.scanner-btn:hover {
    background: #fca700;
    border-color: #fca700;
    transform: scale(1.1);
    color: white;
    box-shadow: 0 4px 12px rgba(45, 90, 71, 0.4);
}

.scanner-btn:focus {
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(24, 55, 93, 0.25);
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
    left: 20px;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    z-index: 10;
}

/* How to Use Button */
.how-to-use-button {
    position: absolute;
    top: 20px;
    right: 20px;
    z-index: 10;
}

.how-to-use-button .btn {
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.1);
    color: white;
    font-weight: 500;
    transition: all 0.3s ease;
}

.how-to-use-button .btn:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
    transform: translateY(-1px);
}

/* Got it button styling */
.btn-got-it {
    background-color: #387057 !important;
    border-color: #387057 !important;
    color: white !important;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-got-it:hover {
    background-color: #2d5a47 !important;
    border-color: #2d5a47 !important;
    color: white !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(56, 112, 87, 0.3);
}

.btn-got-it:focus {
    background-color: #2d5a47 !important;
    border-color: #2d5a47 !important;
    color: white !important;
    box-shadow: 0 0 0 0.2rem rgba(56, 112, 87, 0.25);
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


/* Responsive Design */
@media (max-width: 768px) {
    .scanner-container {
        margin: 1rem auto;
        padding: 1.25rem;
        max-width: 95%; /* keeps it centered and avoids edge overflow */
        border-radius: 16px;
    }

    #qr-reader-container {
        height: 280px;
        border-radius: 14px;
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
        font-size: 0.95rem;
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
<script src="https://unpkg.com/jsqr@1.4.0/dist/jsQR.js"></script>
<script>
let html5QrCode;
let isScanning = false;
let currentCameraId = null;
const scannerConfig = { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };

document.addEventListener('DOMContentLoaded', function() {
    // Initialize forms
    initializeForms();
    
    // Initialize scanner
    initializeScanner();
});

function initializeScanner() {
    html5QrCode = new Html5Qrcode("qr-reader");
    updateStatus('Ready to Scan', 'ready');
}

function isSecureOrigin() {
    return (window.isSecureContext === true) || location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1';
}

function updateCameraButton(active) {
    const btn = document.getElementById('captureButton');
    if (!btn) return;
    btn.innerHTML = active ? '<i class="fas fa-stop"></i>' : '<i class="fas fa-camera"></i>';
}

async function startScanner() {
    if (!isSecureOrigin()) {
        updateStatus('Camera requires HTTPS. Use Upload or Manual Input.', 'error');
        return;
    }
    try {
        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("qr-reader");
        }
        let started = false;
        try {
            await html5QrCode.start(
                { facingMode: "environment" },
                scannerConfig,
                onScanSuccess,
                onScanFailure
            );
            started = true;
        } catch (fmErr) {
            const cameras = await Html5Qrcode.getCameras();
            if (cameras && cameras.length > 0) {
                const back = cameras.find(c => /back|rear|environment/i.test(c.label));
                const cam = back || cameras[0];
                currentCameraId = cam.id;
                await html5QrCode.start(
                    { deviceId: { exact: cam.id } },
                    scannerConfig,
                    onScanSuccess,
                    onScanFailure
                );
                started = true;
            }
        }
        if (!started) {
            updateStatus('No camera found', 'error');
            return;
        }
        isScanning = true;
        updateStatus('Scanning...', 'scanning');
        updateCameraButton(true);
    } catch (err) {
        console.error('Failed to start scanner:', err);
        const msg = (err && err.name === 'NotAllowedError') ? 'Camera permission denied. Allow camera access in your browser settings.' :
                    (err && err.name === 'NotFoundError') ? 'No camera available.' :
                    (err && /insecure|secure context/i.test(err.message || '')) ? 'Camera blocked on HTTP. Use HTTPS.' :
                    'Failed to start scanner';
        updateStatus(msg, 'error');
    }
}

async function stopScanner() {
    try {
        if (html5QrCode && isScanning) {
            await html5QrCode.stop();
        }
    } finally {
        isScanning = false;
        updateStatus('Ready to Scan', 'ready');
        updateCameraButton(false);
    }
}

function toggleCamera() {
    if (isScanning) {
        stopScanner();
    } else {
        startScanner();
    }
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
    // Show loading state
    updateStatus('Loading livestock data...', 'scanning');
    
    // Check if the ID is JSON data from QR code
    let livestockId = id;
    try {
        const qrData = JSON.parse(id);
        if (qrData.livestock_id) {
            livestockId = qrData.livestock_id;
            console.log('Extracted livestock ID from QR data:', livestockId);
        }
    } catch (e) {
        // Not JSON, use as is
        console.log('Using ID as is:', livestockId);
    }
    
    // Fetch livestock data from server
    fetch(`/farmer/scan/${livestockId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.livestock) {
            const livestock = data.livestock;
            console.log('Found livestock data:', livestock);
            
            // Update modal title
            document.getElementById("detailLivestockId").textContent = livestock.tag_number || livestock.id;
            
            // Populate basic info form fields
            populateBasicInfo(livestock);
            
            // Show the modal
            $('#livestockDetailsModal').modal('show');
            
            // Show success message
            showNotification(`Livestock ${livestock.tag_number || livestock.id} information loaded successfully!`, 'success');
            
            // Reset status
            setTimeout(() => {
                updateStatus('Scanning...', 'scanning');
            }, 2000);
        } else {
            showNotification(`No livestock found with ID: ${livestockId}`, 'error');
            openAddLivestockModal(livestockId);
            updateStatus('Scanning...', 'scanning');
        }
    })
    .catch(error => {
        console.error('Error fetching livestock data:', error);
        showNotification(`Error loading livestock data: ${error.message}`, 'error');
        updateStatus('Scanning...', 'scanning');
    });
}

function populateBasicInfo(livestock) {
    // Map database fields to form fields
    const fieldMapping = {
        'ownedBy': livestock.farm?.owner?.name || livestock.farm?.name || 'N/A',
        'dispersalFrom': livestock.source || 'N/A',
        'registryId': livestock.tag_number || livestock.id,
        'tagId': livestock.tag_number || livestock.id,
        'livestockName': livestock.name || livestock.tag_number || 'N/A',
        'dob': livestock.birth_date ? livestock.birth_date.split('T')[0] : '',
        'sex': livestock.gender || 'N/A',
        'breed': livestock.breed || 'N/A',
        'sireId': livestock.sire_id || 'N/A',
        'damId': livestock.dam_id || 'N/A',
        'sireName': livestock.sire_name || 'N/A',
        'damName': livestock.dam_name || 'N/A',
        'sireBreed': livestock.sire_breed || 'N/A',
        'damBreed': livestock.dam_breed || 'N/A',
        'naturalMarks': livestock.physical_characteristics || 'N/A',
        'propertyNo': livestock.farm?.id || 'N/A',
        'acquisitionDate': livestock.acquisition_date ? livestock.acquisition_date.split('T')[0] : '',
        'acquisitionCost': livestock.acquisition_cost || 'N/A',
        'source': livestock.source || 'N/A',
        'remarks': livestock.notes || livestock.remarks || 'N/A',
        'cooperator': livestock.farm?.owner?.name || 'N/A',
        'releasedDate': livestock.released_date ? livestock.released_date.split('T')[0] : '',
        'cooperative': livestock.farm?.name || 'N/A',
        'address': livestock.farm?.address || 'N/A',
        'contactNo': livestock.farm?.owner?.phone || 'N/A',
        'inCharge': livestock.farm?.owner?.name || 'N/A'
    };
    
    // Populate form fields
    Object.keys(fieldMapping).forEach(key => {
        const element = document.getElementById(key);
        if (element) {
            element.value = fieldMapping[key];
        }
    });
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
    const notification = $(`
        <div class="alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show refresh-notification">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : type === 'info' ? 'info-circle' : 'times-circle'}"></i>
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `);
    
    $('body').append(notification);
    
    setTimeout(() => {
        notification.alert('close');
    }, 5000);
}

// Handle file upload for QR codes
document.getElementById('uploadQR').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            showNotification('Please select an image file', 'error');
            return;
        }
        
        // Validate file size (max 5MB for faster processing)
        if (file.size > 5 * 1024 * 1024) {
            showNotification('File size too large. Please select an image smaller than 5MB', 'error');
            return;
        }
        
        // Show loading state
        updateStatus('Processing uploaded image...', 'scanning');
        document.getElementById('processing-progress').style.display = 'block';
        
        // Set a timeout for processing
        const processingTimeout = setTimeout(() => {
            document.getElementById('processing-progress').style.display = 'none';
            updateStatus('Scanning...', 'scanning');
            showNotification('QR code processing timed out. Please try a clearer image or smaller file.', 'error');
        }, 8000); // Reduced to 8 seconds
        
        // Create a FileReader to read the file
        const reader = new FileReader();
        reader.onload = function(event) {
            const imageDataUrl = event.target.result;
            console.log('File loaded successfully, data URL length:', imageDataUrl.length);
            console.log('Data URL preview:', imageDataUrl.substring(0, 100) + '...');
            
            // Try enhanced QR scanning
            enhancedQRScan(imageDataUrl)
                .then(decodedText => {
                    clearTimeout(processingTimeout);
                    document.getElementById('processing-progress').style.display = 'none';
                    console.log('QR Code from file:', decodedText);
                    updateStatus('QR Code detected!', 'success');
                    viewLivestockDetails(decodedText);
                })
                .catch(err => {
                    clearTimeout(processingTimeout);
                    document.getElementById('processing-progress').style.display = 'none';
                    console.error('Error scanning file:', err);
                    updateStatus('Scanning...', 'scanning');
                    
                    // Provide more specific error messages
                    if (err.message === 'Scan timeout') {
                        showNotification('QR code processing timed out. Please try a clearer image or use manual input.', 'error');
                    } else if (err.message && (err.message.includes('No QR code found') || err.message.includes('NotFoundException'))) {
                        showNotification('No QR code found in the uploaded image. Please try a clearer image or use manual input.', 'error');
                    } else if (err.message && err.message.includes('Invalid image')) {
                        showNotification('Invalid image format. Please try a different image.', 'error');
                    } else if (err.message && err.message.includes('Html5Qrcode.scanFile is not a function')) {
                        showNotification('QR scanner not properly loaded. Please use manual input or refresh the page.', 'error');
                    } else if (err.message && err.message.includes('Potential text detected')) {
                        showNotification('Text detected in image. Please use manual input to enter the livestock ID.', 'info');
                    } else {
                        console.log('Full error object:', err);
                        showNotification('QR code not recognized. Please use manual input (⌨️ button) to enter livestock ID.', 'error');
                    }
                });
        };
        
        reader.onerror = function() {
            clearTimeout(processingTimeout);
            document.getElementById('processing-progress').style.display = 'none';
            updateStatus('Scanning...', 'scanning');
            showNotification('Error reading the uploaded file', 'error');
        };
        
        // Read the file as data URL
        reader.readAsDataURL(file);
        
        // Clear the input so the same file can be uploaded again
        e.target.value = '';
    }
});

// Capture QR Code function
function captureQRCode() {
    // This would typically capture a photo and scan it
    // For now, we'll show a message to use the camera scanner
    showNotification('Please point the camera at a QR code to scan', 'info');
}

// Debug function to test QR code scanning
function debugQRScanning() {
    console.log('QR Scanner Debug Info:');
    console.log('- Html5Qrcode available:', typeof Html5Qrcode !== 'undefined');
    console.log('- Scanner initialized:', html5QrCode !== null);
    console.log('- Is scanning:', isScanning);
    console.log('- Camera permissions:', navigator.permissions ? 'Available' : 'Not available');
    
    // Test with a sample QR code data URL
    const testQRDataUrl = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';
    
    Html5Qrcode.scanFile(testQRDataUrl, true)
        .then(result => {
            console.log('Test QR scan successful:', result);
        })
        .catch(err => {
            console.log('Test QR scan failed (expected):', err);
        });
}

// Add debug function to window for console access
window.debugQRScanning = debugQRScanning;

// Test function to validate QR code scanning
function testQRCodeScanning() {
    console.log('Testing QR code scanning functionality...');
    
    // Test if Html5Qrcode is available
    if (typeof Html5Qrcode === 'undefined') {
        console.error('Html5Qrcode library not loaded');
        return false;
    }
    
    console.log('Html5Qrcode library is loaded:', Html5Qrcode);
    console.log('Available methods:', Object.keys(Html5Qrcode));
    
    // Test with a simple QR code data URL (this is a minimal 1x1 pixel image)
    const testImageDataUrl = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';
    
    Html5Qrcode.scanFile(testImageDataUrl, true)
        .then(result => {
            console.log('QR scanning test successful:', result);
        })
        .catch(err => {
            console.log('QR scanning test failed (expected for test image):', err);
            console.log('Error type:', typeof err);
            console.log('Error message:', err.message || err);
        });
    
    return true;
}

// Alternative QR scanning method using a different approach
function alternativeQRScan(imageDataUrl) {
    return new Promise((resolve, reject) => {
        console.log('Trying alternative QR scan method...');
        
        try {
            // Create an image element
            const img = new Image();
            img.onload = function() {
                try {
                    // Create a canvas
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    
                    // Set canvas size to image size
                    canvas.width = img.width;
                    canvas.height = img.height;
                    
                    // Draw image to canvas
                    ctx.drawImage(img, 0, 0);
                    
                    // Try to scan the canvas
                    const canvasDataUrl = canvas.toDataURL('image/png');
                    console.log('Canvas created, trying to scan...');
                    
                    Html5Qrcode.scanFile(canvasDataUrl, true)
                        .then(result => {
                            console.log('Alternative scan successful:', result);
                            resolve(result);
                        })
                        .catch(err => {
                            console.log('Alternative scan failed:', err);
                            reject(err);
                        });
                } catch (canvasErr) {
                    console.error('Canvas error:', canvasErr);
                    reject(canvasErr);
                }
            };
            
            img.onerror = function() {
                console.error('Image load error');
                reject(new Error('Failed to load image'));
            };
            
            img.src = imageDataUrl;
        } catch (err) {
            console.error('Alternative scan setup error:', err);
            reject(err);
        }
    });
}

// Add test function to window
window.testQRCodeScanning = testQRCodeScanning;

// Generate a test QR code for debugging
function generateTestQRCode() {
    console.log('Generating test QR code...');
    
    // Create a simple test QR code data URL (this is a basic QR code containing "TEST123")
    const testQRDataUrl = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';
    
    // Try to scan this test QR code
    Html5Qrcode.scanFile(testQRDataUrl, true)
        .then(result => {
            console.log('Test QR scan successful:', result);
            showNotification('QR scanner is working! Test result: ' + result, 'success');
        })
        .catch(err => {
            console.log('Test QR scan failed:', err);
            showNotification('QR scanner test failed: ' + (err.message || err), 'error');
        });
}

// Add test QR generator to window
window.generateTestQRCode = generateTestQRCode;

// Manual input functions
function showManualInput() {
    $('#manualInputModal').modal('show');
}

function processManualInput() {
    const livestockId = document.getElementById('manualLivestockId').value.trim();
    
    if (!livestockId) {
        showNotification('Please enter a livestock ID or tag number', 'error');
        return;
    }
    
    // Close the manual input modal
    $('#manualInputModal').modal('hide');
    
    // Process the manual input
    viewLivestockDetails(livestockId);
}

function openAddLivestockModal(prefillTag) {
    const tagInput = document.getElementById('al_tag_number');
    if (tagInput) { tagInput.value = prefillTag || ''; }
    const name = document.getElementById('al_name');
    if (name) name.value = '';
    const type = document.getElementById('al_type');
    if (type) type.value = 'cow';
    const breed = document.getElementById('al_breed');
    if (breed) breed.value = 'holstein';
    const bd = document.getElementById('al_birth_date');
    if (bd) bd.value = '';
    const gender = document.getElementById('al_gender');
    if (gender) gender.value = 'female';
    const wt = document.getElementById('al_weight');
    if (wt) wt.value = '';
    const hs = document.getElementById('al_health_status');
    if (hs) hs.value = 'healthy';
    const st = document.getElementById('al_status');
    if (st) st.value = 'active';
    $('#addLivestockModal').modal('show');
}

document.getElementById('addLivestockForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const payload = {
        tag_number: document.getElementById('al_tag_number')?.value?.trim(),
        name: document.getElementById('al_name')?.value?.trim(),
        type: document.getElementById('al_type')?.value,
        breed: document.getElementById('al_breed')?.value,
        birth_date: document.getElementById('al_birth_date')?.value,
        gender: document.getElementById('al_gender')?.value,
        weight: document.getElementById('al_weight')?.value,
        health_status: document.getElementById('al_health_status')?.value,
        status: document.getElementById('al_status')?.value
    };
    const btn = this.querySelector('.btn-ok');
    const original = btn ? btn.innerHTML : null;
    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...'; }
    try {
        const res = await fetch('/farmer/livestock', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (res.ok && data.success && data.livestock) {
            $('#addLivestockModal').modal('hide');
            showNotification('Livestock added successfully!', 'success');
            document.getElementById('detailLivestockId').textContent = data.livestock.tag_number || data.livestock.id;
            populateBasicInfo(data.livestock);
            $('#livestockDetailsModal').modal('show');
        } else {
            const msg = (data && data.message) ? data.message : 'Failed to add livestock. Please check inputs.';
            showNotification(msg, 'error');
        }
    } catch (err) {
        showNotification('Error saving livestock: ' + (err.message || err), 'error');
    } finally {
        if (btn && original) { btn.disabled = false; btn.innerHTML = original; }
    }
});
 
// Improved QR code scanning function with fallback
function scanQRCodeFromImage(imageDataUrl) {
    return new Promise((resolve, reject) => {
        console.log('Starting QR scan with image data URL length:', imageDataUrl.length);
        
        // Check if Html5Qrcode is available
        if (typeof Html5Qrcode === 'undefined' || typeof Html5Qrcode.scanFile !== 'function') {
            console.error('Html5Qrcode.scanFile is not available');
            reject(new Error('QR scanner library not properly loaded'));
            return;
        }
        
        // Create a timeout promise
        const timeoutPromise = new Promise((_, timeoutReject) => 
            setTimeout(() => timeoutReject(new Error('Scan timeout')), 5000) // 5 seconds timeout
        );
        
        // Try primary method first
        const primaryScan = Html5Qrcode.scanFile(imageDataUrl, true)
            .then(result => {
                console.log('Primary QR scan successful:', result);
                return result;
            })
            .catch(err => {
                console.log('Primary QR scan failed:', err);
                // Try alternative method
                return alternativeQRScan(imageDataUrl);
            });
        
        // Race between scanning and timeout
        Promise.race([primaryScan, timeoutPromise])
            .then(result => {
                resolve(result);
            })
            .catch(err => {
                console.error('All QR scan methods failed:', err);
                reject(err);
            });
    });
}

// Alternative scanning method using canvas
function scanWithCanvas(imageDataUrl) {
    return new Promise((resolve, reject) => {
        try {
            const img = new Image();
            img.onload = function() {
                try {
                    // Create canvas
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    
                    // Set canvas size
                    canvas.width = img.width;
                    canvas.height = img.height;
                    
                    // Draw image to canvas
                    ctx.drawImage(img, 0, 0);
                    
                    // Convert canvas to data URL
                    const canvasDataUrl = canvas.toDataURL('image/png');
                    
                    // Try scanning the canvas data
                    Html5Qrcode.scanFile(canvasDataUrl, true)
                        .then(resolve)
                        .catch(reject);
                } catch (canvasErr) {
                    reject(canvasErr);
                }
            };
            
            img.onerror = function() {
                reject(new Error('Failed to load image'));
            };
            
            img.src = imageDataUrl;
        } catch (err) {
            reject(err);
        }
    });
}

// Simple text-based QR code detection (fallback)
function detectTextInImage(imageDataUrl) {
    return new Promise((resolve, reject) => {
        console.log('Trying text-based detection...');
        
        // Create an image element to analyze
        const img = new Image();
        img.onload = function() {
            try {
                // Create canvas for analysis
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                
                // Set canvas size
                canvas.width = img.width;
                canvas.height = img.height;
                
                // Draw image to canvas
                ctx.drawImage(img, 0, 0);
                
                // Get image data
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const data = imageData.data;
                
                // Simple analysis - look for patterns that might indicate text
                // This is a very basic approach
                let hasTextPattern = false;
                
                // Check for high contrast areas (potential text)
                for (let i = 0; i < data.length; i += 4) {
                    const r = data[i];
                    const g = data[i + 1];
                    const b = data[i + 2];
                    const brightness = (r + g + b) / 3;
                    
                    // Look for very dark or very light pixels (potential text)
                    if (brightness < 50 || brightness > 200) {
                        hasTextPattern = true;
                        break;
                    }
                }
                
                if (hasTextPattern) {
                    // If we detect potential text patterns, suggest manual input
                    reject(new Error('Potential text detected in image. Please use manual input to enter the livestock ID.'));
                } else {
                    reject(new Error('No recognizable patterns found in image'));
                }
            } catch (err) {
                reject(new Error('Image analysis failed: ' + err.message));
            }
        };
        
        img.onerror = function() {
            reject(new Error('Failed to load image for analysis'));
        };
        
        img.src = imageDataUrl;
    });
}

// Enhanced QR scanning with multiple fallbacks including jsQR
function enhancedQRScan(imageDataUrl) {
    return new Promise((resolve, reject) => {
        console.log('Starting enhanced QR scan...');
        
        // Try different approaches in sequence
        const approaches = [
            () => scanWithJsQR(imageDataUrl),  // Try jsQR first (most reliable)
            () => scanWithJsQREnhanced(imageDataUrl),  // Try enhanced jsQR with preprocessing
            () => Html5Qrcode.scanFile(imageDataUrl, true),
            () => Html5Qrcode.scanFile(imageDataUrl, { fps: 2, qrbox: { width: 250, height: 250 } }),
            () => scanWithCanvas(imageDataUrl),
            () => detectTextInImage(imageDataUrl)
        ];
        
        let currentApproach = 0;
        
        function tryNextApproach() {
            if (currentApproach >= approaches.length) {
                reject(new Error('All scanning approaches failed'));
                return;
            }
            
            console.log(`Trying approach ${currentApproach + 1}...`);
            
            approaches[currentApproach]()
                .then(result => {
                    console.log(`Approach ${currentApproach + 1} successful:`, result);
                    resolve(result);
                })
                .catch(err => {
                    console.log(`Approach ${currentApproach + 1} failed:`, err);
                    currentApproach++;
                    tryNextApproach();
                });
        }
        
        tryNextApproach();
    });
}

// jsQR-based scanning (most reliable)
function scanWithJsQR(imageDataUrl) {
    return new Promise((resolve, reject) => {
        console.log('Trying jsQR scanning...');
        
        // Check if jsQR is available
        if (typeof jsQR === 'undefined') {
            reject(new Error('jsQR library not loaded'));
            return;
        }
        
        const img = new Image();
        img.onload = function() {
            try {
                // Create canvas
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                
                // Set canvas size
                canvas.width = img.width;
                canvas.height = img.height;
                
                // Draw image to canvas
                ctx.drawImage(img, 0, 0);
                
                // Get image data
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                
                // Scan for QR code
                const code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: "dontInvert",
                });
                
                if (code) {
                    console.log('jsQR found QR code:', code.data);
                    resolve(code.data);
                } else {
                    // Try with inversion
                    const codeInverted = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: "onlyInvert",
                    });
                    
                    if (codeInverted) {
                        console.log('jsQR found QR code (inverted):', codeInverted.data);
                        resolve(codeInverted.data);
                    } else {
                        // Try with all attempts
                        const codeAll = jsQR(imageData.data, imageData.width, imageData.height, {
                            inversionAttempts: "attemptBoth",
                        });
                        
                        if (codeAll) {
                            console.log('jsQR found QR code (all attempts):', codeAll.data);
                            resolve(codeAll.data);
                        } else {
                            reject(new Error('No QR code found by jsQR'));
                        }
                    }
                }
            } catch (err) {
                reject(new Error('jsQR scanning error: ' + err.message));
            }
        };
        
        img.onerror = function() {
            reject(new Error('Failed to load image for jsQR scanning'));
        };
        
        img.src = imageDataUrl;
    });
}

// Image preprocessing for better QR detection
function preprocessImage(canvas, ctx) {
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const data = imageData.data;
    
    // Convert to grayscale and increase contrast
    for (let i = 0; i < data.length; i += 4) {
        const r = data[i];
        const g = data[i + 1];
        const b = data[i + 2];
        
        // Convert to grayscale
        const gray = 0.299 * r + 0.587 * g + 0.114 * b;
        
        // Increase contrast (simple threshold)
        const enhanced = gray > 128 ? 255 : 0;
        
        data[i] = enhanced;     // Red
        data[i + 1] = enhanced; // Green
        data[i + 2] = enhanced; // Blue
        // Alpha stays the same
    }
    
    ctx.putImageData(imageData, 0, 0);
    return imageData;
}

// Enhanced jsQR scanning with preprocessing
function scanWithJsQREnhanced(imageDataUrl) {
    return new Promise((resolve, reject) => {
        console.log('Trying enhanced jsQR scanning with preprocessing...');
        
        if (typeof jsQR === 'undefined') {
            reject(new Error('jsQR library not loaded'));
            return;
        }
        
        const img = new Image();
        img.onload = function() {
            try {
                // Create canvas
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                
                // Set canvas size
                canvas.width = img.width;
                canvas.height = img.height;
                
                // Draw image to canvas
                ctx.drawImage(img, 0, 0);
                
                // Try original image first
                let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                let code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: "attemptBoth",
                });
                
                if (code) {
                    console.log('Enhanced jsQR found QR code (original):', code.data);
                    resolve(code.data);
                    return;
                }
                
                // Try with preprocessing
                imageData = preprocessImage(canvas, ctx);
                code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: "attemptBoth",
                });
                
                if (code) {
                    console.log('Enhanced jsQR found QR code (preprocessed):', code.data);
                    resolve(code.data);
                } else {
                    reject(new Error('No QR code found by enhanced jsQR'));
                }
            } catch (err) {
                reject(new Error('Enhanced jsQR scanning error: ' + err.message));
            }
        };
        
        img.onerror = function() {
            reject(new Error('Failed to load image for enhanced jsQR scanning'));
        };
        
        img.src = imageDataUrl;
    });
}

// Update test function to test jsQR
function testJsQRScanning() {
    console.log('Testing jsQR scanning functionality...');
    
    if (typeof jsQR === 'undefined') {
        console.error('jsQR library not loaded');
        showNotification('jsQR library not loaded', 'error');
        return false;
    }
    
    console.log('jsQR library is loaded successfully');
    showNotification('jsQR library is loaded and ready!', 'success');
    return true;
}

// Add enhanced test function to window
window.testJsQRScanning = testJsQRScanning;

// Show How to Use modal
function toggleHowToUse() {
    $('#howToUseModal').modal('show');
}
</script>
@endpush

