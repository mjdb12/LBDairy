@push('meta')
    <meta name="theme-color" content="#ffffff">
    <link rel="manifest" href="/manifest.json">
@endpush

@push('scripts')
<script>
console.log('Service worker component loaded!');
if ('serviceWorker' in navigator) {
    console.log('Service Worker API is supported');
    window.addEventListener('load', () => {
        console.log('Page loaded, attempting to register service worker...');
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('SW registered successfully: ', registration);
                
                // Check for updates
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                    newWorker.addEventListener('statechange', () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            // New content is available, show update notification
                            if (confirm('New version available! Reload to update?')) {
                                window.location.reload();
                            }
                        }
                    });
                });
            })
            .catch(registrationError => {
                console.log('SW registration failed: ', registrationError);
                console.error('Registration error details:', registrationError);
            });
    });
} else {
    console.log('Service Worker API is NOT supported');
}
</script>
@endpush
