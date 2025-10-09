const CACHE_NAME = 'lb-dairy-v4';
const urlsToCache = [
  // Optionally pre-cache the home page only
  '/'
];

// Install event - cache resources
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        // Precache whitelisted URLs
        return Promise.allSettled(
          urlsToCache.map(url => cache.add(url).catch(() => null))
        );
      })
  );
  // Activate new SW immediately
  self.skipWaiting();
});

// Fetch event - serve from cache when offline
self.addEventListener('fetch', event => {
  const req = event.request;
  const url = new URL(req.url);

  // Do not handle non-GET requests (allow POST/PUT/DELETE to hit network directly)
  if (req.method !== 'GET') {
    return event.respondWith(fetch(req));
  }

  // Bypass caching for API or dynamic endpoints
  const isAPI = url.pathname.startsWith('/calendar/') || url.pathname.startsWith('/api/');
  if (isAPI) {
    return event.respondWith(fetch(req).catch(() => caches.match(req)));
  }

  // For navigation requests, use network-first with cache fallback (avoid caching HTML aggressively)
  if (req.mode === 'navigate') {
    return event.respondWith(
      fetch(req).catch(() => caches.match(req))
    );
  }

  // Only cache static assets by extension
  const isStatic = /\.(?:css|js|png|jpg|jpeg|gif|svg|webp|ico|woff2?|ttf|eot|map)$/i.test(url.pathname);
  if (!isStatic) {
    // Default: try network, fallback to cache
    return event.respondWith(fetch(req).catch(() => caches.match(req)));
  }

  // Cache-first for static assets with background update (stale-while-revalidate)
  event.respondWith(
    caches.match(req).then(cached => {
      const fetchPromise = fetch(req).then(networkResp => {
        if (networkResp && networkResp.status === 200) {
          const clone = networkResp.clone();
          caches.open(CACHE_NAME).then(cache => cache.put(req, clone));
        }
        return networkResp;
      }).catch(() => cached);
      return cached || fetchPromise;
    })
  );
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  // Take control of clients immediately
  self.clients.claim();
});