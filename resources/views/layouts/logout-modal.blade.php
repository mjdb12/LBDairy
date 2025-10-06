<!-- Modern Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content smart-modal text-center p-4">
        <!-- Icon -->
            <div class="icon-wrapper mx-auto mb-4 text-active">
                <i class="fas fa-info-circle fa-2x"></i>
            </div>
            <!-- Title -->
            <h5>Ready to Leave?</h5>
            <p class="text-muted mb-4 px-3">
                Are you sure you want to <strong>logout</strong> and end your current session? Select <strong>“Logout”</strong> below if you are ready to proceed.
            </p>
            <!-- Buttons -->
            <div class="modal-footer d-flex gap-2 justify-content-center flex-wrap">
                <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn-modern btn-confirm"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
                </button>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>


<style>
.smart-modal {
  border: none;
  border-radius: 16px;
  background: #fff;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  max-width: 500px;
  margin: auto;
  transition: all 0.3s ease;
}

.smart-modal .icon-wrapper {
  background-color: #e6ebf4ff;
  color: #18375d;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
}

.smart-modal h5 {
  color: #18375d;
  font-weight: 600;
}

.smart-modal p {
  color: #6b7280;
  font-size: 0.95rem;
}

.btn-modern {
  border-radius: 8px;       /* slightly smaller rounded corners */
  padding: 8px 19px;        /* smaller padding */
  font-size: 0.9rem;        /* smaller font size */
  font-weight: 600;
  border: 1px solid transparent;
  transition: all 0.3s ease;
}


.btn-cancel {
  background: #fff;
  border: 1px solid #d1d5db;
  color: #111827;
}

.btn-cancel:hover {
  background: #fca700;
  color: #fff;
}

.btn-confirm {
  background: #dc3545;
  color: #fff;
  border: none;
}

.btn-confirm:hover {
  background: #fca700;
}

    /* User Details Modal Styling */
    #logoutModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }
    
    #logoutModal .modal-header {
        background: #18375d !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
    }
    
    #logoutModal .modal-title {
        color: white !important;
        font-weight: 600;
    }
    
    #logoutModal .modal-body {
        padding: 2rem;
        background: white;
    }
    
    #logoutModal .modal-body h6 {
        color: #18375d !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem !important;
    }
    
    #logoutModal .modal-body p {
        margin-bottom: 0.75rem;
        color: #333 !important;
    }
    
    #logoutModal .modal-body strong {
        color: #5a5c69 !important;
        font-weight: 600;
    }

    /* Style all labels inside form Modal */
    #logoutModal .form-group label {
        font-weight: 600;           /* make labels bold */
        color: #18375d;             /* Bootstrap primary blue */
        display: inline-block;      /* keep spacing consistent */
        margin-bottom: 0.5rem;      /* add spacing below */
    }
/* Custom logout button styling */
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
.btn-logout {
    background-color: #c82333 !important;
    border-color: #c82333 !important;
    color: white !important;
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
.btn-logout:hover,
.btn-logout:focus {
    background-color: #a71e2a !important;
    border-color: #a71e2a !important;
    color: white !important;
    box-shadow: 0 0 0 0.2rem rgba(200, 35, 51, 0.25) !important;
}

.btn-logout:active {
    background-color: #a71e2a !important;
    border-color: #a71e2a !important;
    color: white !important;
}
</style>
