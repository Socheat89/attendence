const CACHE_NAME = 'mekonghrm-v1';

const STATIC_ASSETS = [
    '/',
    '/offline.html',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(STATIC_ASSETS);
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k))
            );
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    // Only handle GET requests
    if (event.request.method !== 'GET') return;

    // Navigation requests: network first, fallback to offline page
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
                .catch(() => caches.match('/offline.html'))
        );
        return;
    }

    // Static assets: cache first
    event.respondWith(
        caches.match(event.request).then((cached) => {
            return cached || fetch(event.request);
        })
    );
});

self.addEventListener('push', function (e) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    if (e.data) {
        var msg = e.data.json();
        e.waitUntil(self.registration.showNotification(msg.title, {
            body: msg.body,
            icon: msg.icon || '/images/icons/icon-192x192.png',
            badge: '/images/icons/icon-72x72.png',
            vibrate: [200, 100, 200, 100, 200],
            data: msg.action || '/'
        }));
    }
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    
    // Open the app to the specified URL when notification is clicked
    const targetUrl = event.notification.data || '/';
    
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(windowClients => {
            // Check if there is already a window/tab open with the target URL
            for (let i = 0; i < windowClients.length; i++) {
                const client = windowClients[i];
                if (client.url === targetUrl && 'focus' in client) {
                    return client.focus();
                }
            }
            // If not, open a new window/tab
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
