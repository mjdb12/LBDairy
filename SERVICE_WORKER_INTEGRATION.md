# Service Worker Integration Guide for LB Dairy Laravel System

## Overview
This guide explains how service workers are integrated into the Laravel system to provide Progressive Web App (PWA) capabilities, offline functionality, and improved performance.

## Files Created/Modified

### 1. Service Worker Files
- `public/sw.js` - Main service worker file (direct approach)
- `resources/js/sw.js` - Service worker for Vite build process
- `public/offline.html` - Offline fallback page
- `public/manifest.json` - Web app manifest for PWA

### 2. Laravel Integration
- `resources/js/app.js` - Service worker registration
- `resources/views/components/service-worker.blade.php` - Blade component
- `app/Console/Commands/ServiceWorkerCommand.php` - Artisan command
- `vite.config.js` - Updated for service worker build

## Installation & Setup

### Method 1: Using Artisan Command (Recommended)
```bash
# Generate service worker and manifest
php artisan sw:generate

# Force regenerate (overwrites existing)
php artisan sw:generate --force
```

### Method 2: Manual Setup
1. Build assets with Vite:
```bash
npm run build
```

2. Include the service worker component in your layout:
```blade
@include('components.service-worker')
```

## Usage in Blade Templates

### Basic Layout Integration
```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LB Dairy</title>
    
    @stack('meta')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Your content here -->
    
    @stack('scripts')
</body>
</html>
```

### Include Service Worker Component
```blade
@extends('layouts.app')

@section('content')
    <!-- Your page content -->
@endsection

@include('components.service-worker')
```

## Features Provided

### 1. Offline Functionality
- Caches essential resources (CSS, JS, HTML)
- Serves cached content when offline
- Shows offline page for uncached navigation requests

### 2. Performance Improvements
- Faster loading through caching
- Reduced server requests
- Background resource preloading

### 3. PWA Capabilities
- Installable as a mobile app
- App-like experience
- Background sync (when implemented)

### 4. Update Management
- Automatic cache versioning
- Update notifications to users
- Graceful cache cleanup

## Customization

### Modifying Cached Resources
Edit the `urlsToCache` array in `public/sw.js`:
```javascript
const urlsToCache = [
  '/',
  '/css/app.css',
  '/js/app.js',
  '/offline.html',
  '/api/essential-data',  // Add your API endpoints
  '/images/logo.png'      // Add important images
];
```

### Custom Caching Strategy
Modify the fetch event handler in `public/sw.js`:
```javascript
self.addEventListener('fetch', event => {
  // Network first for API calls
  if (event.request.url.includes('/api/')) {
    event.respondWith(
      fetch(event.request)
        .then(response => {
          // Cache successful responses
          if (response.status === 200) {
            const responseClone = response.clone();
            caches.open(CACHE_NAME).then(cache => {
              cache.put(event.request, responseClone);
            });
          }
          return response;
        })
        .catch(() => {
          return caches.match(event.request);
        })
    );
  } else {
    // Cache first for static resources
    event.respondWith(
      caches.match(event.request)
        .then(response => response || fetch(event.request))
    );
  }
});
```

### Adding Push Notifications
```javascript
// In your service worker
self.addEventListener('push', event => {
  const options = {
    body: event.data.text(),
    icon: '/img/icon-192x192.png',
    badge: '/img/badge-72x72.png'
  };

  event.waitUntil(
    self.registration.showNotification('LB Dairy', options)
  );
});
```

## Testing

### Development Testing
1. Open Chrome DevTools
2. Go to Application tab
3. Check Service Workers section
4. Test offline functionality using Network tab

### Production Testing
```bash
# Build for production
npm run build

# Test service worker
php artisan serve
```

## Troubleshooting

### Common Issues

1. **Service Worker Not Registering**
   - Check browser console for errors
   - Ensure HTTPS (except localhost)
   - Verify file paths are correct

2. **Cache Not Updating**
   - Increment cache version in service worker
   - Use `php artisan sw:generate --force`
   - Clear browser cache

3. **Offline Page Not Showing**
   - Verify `/offline.html` exists
   - Check service worker fetch event handler
   - Test with network disconnected

### Debug Commands
```bash
# Check service worker status
php artisan sw:generate --force

# Clear Laravel cache
php artisan cache:clear

# Rebuild assets
npm run build
```

## Security Considerations

1. **HTTPS Required**: Service workers only work over HTTPS (except localhost)
2. **Same Origin**: Service workers can only control pages from the same origin
3. **Content Security Policy**: Ensure CSP allows service worker execution

## Performance Monitoring

Monitor service worker performance using:
- Chrome DevTools Application tab
- Lighthouse PWA audit
- Real User Monitoring (RUM) data

## Next Steps

1. Add push notification functionality
2. Implement background sync for offline data
3. Add more sophisticated caching strategies
4. Create custom offline experiences for different pages
5. Add service worker analytics and monitoring

## Support

For issues or questions:
1. Check browser console for errors
2. Review service worker lifecycle events
3. Test in different browsers
4. Verify HTTPS configuration in production
