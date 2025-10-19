<!-- Topbar -->
 <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow fixed-top" style="background-color: #ffffffff !important; border-bottom: 2px solid #18375d !important;">
<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3 ">
                        <i class="fa fa-bars"></i>
                    </button>
    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter" id="notificationCount">0</span>
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    System Notifications
                </h6>
                <div id="notificationsList">
                    <!-- Notifications will be loaded here -->
                </div>
                <a class="dropdown-item text-center small text-gray-500" href="#" id="markAllRead">
                    Mark All as Read
                </a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.5rem 1rem; min-height: 4.375rem;">
                <span class="mr-3 d-none d-lg-inline text-gray-600 small font-weight-bold">{{ Auth::user()->name ?? 'User' }}</span>
                <img class="img-profile rounded-circle navbar-profile-img" src="{{ asset('img/' . (Auth::user()->profile_image ?? 'ronaldo.png')) }}?t={{ time() }}" alt="Profile Picture">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                @if(Auth::user()->role === 'superadmin')
                    <a class="dropdown-item" href="{{ route('superadmin.profile') }}">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>
                @elseif(Auth::user()->role === 'admin')
                    <a class="dropdown-item" href="{{ route('admin.profile') }}">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>
                @elseif(Auth::user()->role === 'farmer')
                    <a class="dropdown-item" href="{{ route('farmer.profile') }}">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>
                @else
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>
                @endif
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>
<!-- End of Topbar -->

@if(Auth::user() && in_array(Auth::user()->role, ['superadmin', 'admin']))
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load notifications on page load
    loadNotifications();
    
    // Refresh notifications every 30 seconds
    setInterval(loadNotifications, 30000);
    
    // Update topbar profile picture with user's actual profile image
    updateTopbarProfilePicture();
    
    // Mark all as read functionality
    document.getElementById('markAllRead').addEventListener('click', function(e) {
        e.preventDefault();
        markAllAsRead();
    });
    
    // Hide notification count when dropdown is opened
    const alertsDropdown = document.getElementById('alertsDropdown');
    const notificationCount = document.getElementById('notificationCount');
    
    alertsDropdown.addEventListener('show.bs.dropdown', function() {
        // Hide the notification count when dropdown opens
        notificationCount.style.display = 'none';
    });
    
    alertsDropdown.addEventListener('hide.bs.dropdown', function() {
        // Show the notification count again when dropdown closes
        if (parseInt(notificationCount.textContent) > 0) {
            notificationCount.style.display = 'inline';
        }
    });
});

let suppressNotifications = false;
let lastNotificationCount = 0;

function loadNotifications() {
    // Debug logging
    console.log('loadNotifications called, suppressNotifications:', suppressNotifications);
    
    if (suppressNotifications) {
        console.log('Notifications suppressed, returning early');
        updateNotificationCount(0);
        updateNotificationsList([]);
        return;
    }
    
    // Show loading state
    const container = document.getElementById('notificationsList');
    if (!container) {
        console.error('notificationsList container not found');
        return;
    }
    container.innerHTML = '<div class="dropdown-item text-center small text-gray-500"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
    
    console.log('Starting notifications fetch request...');
    
    // Create a timeout promise
    const timeoutPromise = new Promise((_, reject) => {
        setTimeout(() => reject(new Error('Request timeout')), 10000); // 10 second timeout
    });

    // Race between fetch and timeout
    const notificationUrl = '{{ Auth::user()->role === "superadmin" ? "/superadmin/notifications" : "/admin/notifications" }}';
    Promise.race([
        fetch(notificationUrl, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'Cache-Control': 'no-cache',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }),
        timeoutPromise
    ])
        .then(response => {
            console.log('Notifications response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Notifications response data:', data);
            
            if (data.success) {
                const newCount = data.unread_count || 0;
                
                console.log('Processing notifications:', {
                    newCount: newCount,
                    lastCount: lastNotificationCount,
                    notifications: data.notifications
                });
                
                // Always update to ensure display is correct
                updateNotificationCount(newCount);
                updateNotificationsList(data.notifications || []);
                lastNotificationCount = newCount;
                
                // Log for debugging
                console.log('Notifications updated:', {
                    count: newCount,
                    notifications: (data.notifications || []).length,
                    timestamp: new Date().toISOString()
                });
            } else {
                console.error('Notifications response not successful:', data);
                throw new Error(data.error || 'Failed to load notifications');
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            
            // Show error message with retry option
            container.innerHTML = `
                <div class="dropdown-item text-center small text-gray-500">
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                    <div>Failed to load notifications</div>
                    <small class="text-muted">Click to retry</small>
                </div>
            `;
            
            // Make it clickable to retry
            container.querySelector('.dropdown-item').addEventListener('click', loadNotifications);
            
            // Update notification count to 0 to prevent showing loading state
            updateNotificationCount(0);
        });
}

function updateNotificationCount(count) {
    const badge = document.getElementById('notificationCount');
    if (count > 0) {
        badge.textContent = count > 99 ? '99+' : count;
        badge.style.display = 'inline';
        
        // Add pulse animation for new notifications
        badge.classList.add('badge-pulse');
    } else {
        badge.textContent = '0';
        badge.style.display = 'none';
        badge.classList.remove('badge-pulse');
    }
}

function updateNotificationsList(notifications) {
    console.log('updateNotificationsList called with:', notifications);
    const container = document.getElementById('notificationsList');
    
    if (!container) {
        console.error('notificationsList container not found in updateNotificationsList');
        return;
    }
    
    if (!notifications || notifications.length === 0) {
        console.log('No notifications to display');
        container.innerHTML = '<div class="dropdown-item text-center small text-gray-500"><i class="fas fa-check-circle text-success"></i> No new notifications</div>';
        return;
    }
    
    let html = '';
    notifications.forEach(notification => {
        const typeClass = getNotificationTypeClass(notification.type);
        const iconClass = notification.icon;
        
        // Check if this is a message notification
        const isMessage = notification.action_url === '#' || (notification.title && notification.title.includes('Message from'));
        const clickHandler = isMessage 
            ? `onclick="showMessageModal('${notification.id}', '${escapeHtml(notification.title)}', '${escapeHtml(notification.message)}'); markNotificationAsRead('${notification.id}'); return false;"` 
            : `onclick="markNotificationAsRead('${notification.id}')" href="${notification.action_url}"`;
        
        html += `
            <a class="dropdown-item d-flex align-items-center notification-item" ${clickHandler}>
                <div class="mr-3">
                    <div class="icon-circle ${typeClass}">
                        <i class="${iconClass} text-white fw-light" style="color: white !important;"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="small text-gray-500">${notification.time}</div>
                    <span class="font-weight-bold">${notification.title}</span>
                    <div class="small text-gray-600">${notification.message}</div>
                </div>
                <div class="ml-2">
                    <i class="fas fa-${isMessage ? 'envelope' : 'chevron-right'} text-gray-400 fa-xs"></i>
                </div>
            </a>
        `;
    });
    
    container.innerHTML = html;
    
    // Add hover effects
    const notificationItems = container.querySelectorAll('.notification-item');
    notificationItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fc';
        });
        item.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

function showMessageModal(notificationId, title, message) {
    // Parse the message to separate subject and content
    const parts = message.split(' - ');
    const subject = parts[0] || 'No Subject';
    const content = parts.slice(1).join(' - ') || 'No message content';
    
    // Create modal HTML
    const modalHtml = `
        <!-- Smart Detail Modal -->
<div class="modal fade admin-modal" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content smart-detail p-4">

        <!-- Icon + Header -->
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="icon-circle">
                    <i class="fas fa-envelope fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1">${title}</h5>
            </div>

      <!-- Body -->
<div class="modal-body" style="padding: 1.5rem; background-color: #f9fafc;">
  <!-- Subject Section -->
  <div class="mb-4">
    <h6 style="color: #18375d; font-weight: 600; border-bottom: 2px solid #e3e6f0; padding-bottom: 0.5rem; margin-bottom: 0.75rem; ">
      Subject
    </h6>
    <p style="margin: 0; color: #333; font-weight: 500; font-size: 1rem; text-align: justify;">
      ${subject}
    </p>
  </div>

  <!-- Message Section -->
  <div class="mb-2">
    <h6 style="color: #18375d; font-weight: 600; border-bottom: 2px solid #e3e6f0; padding-bottom: 0.5rem; margin-bottom: 0.75rem;">
      Message
    </h6>
    <p style="margin: 0; color: #333; line-height: 1.7; font-size: 0.95rem; text-align: justify;">
      ${content}
    </p>
  </div>
</div>


      <!-- Footer -->

        <div class="modal-footer d-flex justify-content-center align-items-center flex-nowrap gap-2 mt-4">
            <button type="button" class="btn-modern btn-cancel" data-dismiss="modal">Close</button>
        </div>

    </div>
  </div>
</div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('messageModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Show modal
    $('#messageModal').modal('show');
    
    // Clean up modal when hidden
    $('#messageModal').on('hidden.bs.modal', function () {
        this.remove();
    });
}

function getNotificationTypeClass(type) {
    switch(type) {
        case 'critical':
        case 'danger':
            return 'bg-danger';
        case 'warning':
            return 'bg-warning';
        case 'info':
            return 'bg-info';
        case 'success':
            return 'bg-success';
        default:
            return 'bg-primary';
    }
}

function markNotificationAsRead(notificationId) {
    // Optimistic update - hide the notification immediately
    const notificationItem = document.querySelector(`[onclick*="${notificationId}"]`);
    if (notificationItem) {
        notificationItem.style.opacity = '0.5';
        notificationItem.style.pointerEvents = 'none';
    }
    
    const markReadUrl = '{{ Auth::user()->role === "superadmin" ? "/superadmin/notifications/mark-read" : "/admin/notifications/mark-read" }}';
    fetch(markReadUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        credentials: 'same-origin',
        body: JSON.stringify({
            notification_id: parseInt(notificationId)
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Remove the notification from the list immediately
            if (notificationItem) {
                notificationItem.remove();
            }
            
            // Reload notifications to update the count
            loadNotifications();
        } else {
            throw new Error(data.error || 'Failed to mark notification as read');
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
        
        // Restore the notification if marking as read failed
        if (notificationItem) {
            notificationItem.style.opacity = '1';
            notificationItem.style.pointerEvents = 'auto';
        }
        
        // Show error message
        showNotificationError('Failed to mark notification as read');
    });
}

function markAllAsRead() {
    const markAllBtn = document.getElementById('markAllRead');
    const originalText = markAllBtn.innerHTML;
    
    // Show loading state
    markAllBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Marking...';
    markAllBtn.style.pointerEvents = 'none';
    
    const markAllReadUrl = '{{ Auth::user()->role === "superadmin" ? "/superadmin/notifications/mark-all-read" : "/admin/notifications/mark-all-read" }}';
    fetch(markAllReadUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Clear all notifications from the list
            const notificationsList = document.getElementById('notificationsList');
            notificationsList.innerHTML = '<div class="dropdown-item text-center small text-gray-500"><i class="fas fa-check-circle text-success"></i> All notifications marked as read</div>';
            
            // Update notification count
            updateNotificationCount(0);
            
            // Show success feedback
            markAllBtn.innerHTML = '<i class="fas fa-check"></i> Marked!';
            markAllBtn.classList.add('text-success');
            
            setTimeout(() => {
                markAllBtn.innerHTML = originalText;
                markAllBtn.classList.remove('text-success');
                markAllBtn.style.pointerEvents = 'auto';
            }, 2000);
        } else {
            throw new Error(data.error || 'Failed to mark all notifications as read');
        }
    })
    .catch(error => {
        console.error('Error marking all notifications as read:', error);
        
        // Restore button state
        markAllBtn.innerHTML = originalText;
        markAllBtn.style.pointerEvents = 'auto';
        
        // Show error message
        showNotificationError('Failed to mark all notifications as read');
    });
}

function updateTopbarProfilePicture() {
    // The profile picture is already set correctly in the blade template
    // This function is kept for future use if needed
    console.log('Topbar profile picture updated');
}

function showNotificationError(message) {
    // Create a temporary error notification
    const errorDiv = document.createElement('div');
    errorDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed';
    errorDiv.style.cssText = 'top: 100px; right: 20px; z-index: 9999; min-width: 300px;';
    errorDiv.innerHTML = `
        <i class="fas fa-exclamation-triangle"></i>
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;
    
    document.body.appendChild(errorDiv);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (errorDiv.parentNode) {
            errorDiv.remove();
        }
    }, 5000);
}

// Function to test notification system and show real-time stats
function testNotificationSystem() {
    fetch('/superadmin/notifications/user-stats', {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'Cache-Control': 'no-cache'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Real-time User Statistics:', data.data);
            
            // Show a test notification with current stats
            const stats = data.data;
            const testNotification = {
                id: 'test_stats',
                type: 'info',
                icon: 'fas fa-chart-bar',
                title: 'Real-time User Statistics',
                message: `Total: ${stats.total_users}, New (24h): ${stats.new_users_24h}, Pending Admins: ${stats.pending_admins}`,
                time: 'Just now',
                action_url: '#',
                is_read: false
            };
            
            updateNotificationCount(1);
            updateNotificationsList([testNotification]);
        }
    })
    .catch(error => {
        console.error('Error testing notification system:', error);
    });
}
</script>

<style>
.badge-pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

.notification-item {
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
}

.notification-item:hover {
    border-left-color: var(--primary-color);
    transform: translateX(2px);
}

.icon-circle {
    transition: all 0.2s ease;
}

.notification-item:hover .icon-circle {
    transform: scale(1.1);
}

.bg-info {
    background-color: #18375d !important;
}
.bg-sucsess {
    background-color: #387057 !important;
}
.bg-warning {
    background-color: #fca700 !important;
}
.bg-info {
    background-color: #dc3545 !important;
}

.icon-circle.bg-info {
    box-shadow: #18375d;
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

</style>
@endif

