<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn-action btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn-action btn-action-deletes">
    <i class="fas fa-sign-out-alt mr-2"></i>Logout
</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom logout button styling */
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
