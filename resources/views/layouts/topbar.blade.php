<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-light topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
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
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name ?? 'User' }}</span>
                <img class="img-profile rounded-circle" src="{{ asset('img/' . (Auth::user()->profile_image ?? 'ronaldo.png')) }}?t={{ time() }}" alt="Profile Picture">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Activity Log
                </a>
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

@if(Auth::user() && Auth::user()->role === 'superadmin')
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
    if (suppressNotifications) {
        updateNotificationCount(0);
        updateNotificationsList([]);
        return;
    }
    
    // Show loading state
    const container = document.getElementById('notificationsList');
    container.innerHTML = '<div class="dropdown-item text-center small text-gray-500"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
    
    // Create a timeout promise
    const timeoutPromise = new Promise((_, reject) => {
        setTimeout(() => reject(new Error('Request timeout')), 10000); // 10 second timeout
    });

    // Race between fetch and timeout
    Promise.race([
        fetch('/superadmin/notifications', {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'Cache-Control': 'no-cache'
            }
        }),
        timeoutPromise
    ])
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const newCount = data.unread_count;
                
                // Only update if count has changed
                if (newCount !== lastNotificationCount) {
                    updateNotificationCount(newCount);
                    updateNotificationsList(data.notifications);
                    lastNotificationCount = newCount;
                    
                    // Log for debugging
                    console.log('Notifications updated:', {
                        count: newCount,
                        notifications: data.notifications.length,
                        timestamp: new Date().toISOString()
                    });
                }
            } else {
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
    const container = document.getElementById('notificationsList');
    
    if (notifications.length === 0) {
        container.innerHTML = '<div class="dropdown-item text-center small text-gray-500"><i class="fas fa-check-circle text-success"></i> No new notifications</div>';
        return;
    }
    
    let html = '';
    notifications.forEach(notification => {
        const typeClass = getNotificationTypeClass(notification.type);
        const iconClass = notification.icon;
        
        html += `
            <a class="dropdown-item d-flex align-items-center notification-item" href="${notification.action_url}" onclick="markNotificationAsRead('${notification.id}')">
                <div class="mr-3">
                    <div class="icon-circle ${typeClass}">
                        <i class="${iconClass} text-white"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="small text-gray-500">${notification.time}</div>
                    <span class="font-weight-bold">${notification.title}</span>
                    <div class="small text-gray-600">${notification.message}</div>
                </div>
                <div class="ml-2">
                    <i class="fas fa-chevron-right text-gray-400 fa-xs"></i>
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
    
    fetch('/superadmin/notifications/mark-read', {
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
    
    fetch('/superadmin/notifications/mark-all-read', {
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
</style>
@endif
