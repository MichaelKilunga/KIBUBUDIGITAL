const CACHE_NAME = 'kibubu-v2';
const urlsToCache = [
  '/',
  '/css/style.css',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
  'https://code.jquery.com/jquery-3.6.0.min.js',
  'https://cdn.jsdelivr.net/npm/sweetalert2@11'
];

self.addEventListener('install', event => {
  self.skipWaiting(); // Force the waiting service worker to become the active service worker
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(urlsToCache))
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName); // Delete old caches
          }
        })
      );
    }).then(() => self.clients.claim()) // Claim clients immediately
  );
});

self.addEventListener('fetch', event => {
  // Network-First Strategy for all requests
  event.respondWith(
    fetch(event.request).then(response => {
      // If network works, update the cache and return the network response
      const responseClone = response.clone();
      if (event.request.method === 'GET') {
          caches.open(CACHE_NAME).then(cache => {
            cache.put(event.request, responseClone);
          });
      }
      return response;
    }).catch(() => {
      // Offline fallback: try to serve from cache
      return caches.match(event.request);
    })
  );
});
