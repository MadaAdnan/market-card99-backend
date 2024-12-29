if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/OneSignalSDKWorker.js')
        .then(registration => {
            console.log('Service worker registered:', registration);
        })
        .catch(error => {
            console.log('Service worker registration failed:', error);
        });
}

self.addEventListener('install', event => {
    console.log('Service worker installed');
    event.waitUntil(
        caches.open('my-cache')
            .then(cache => cache.addAll([
                '/index.html',
                '/styles.css',
                '/script.js'
            ]))
    );
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    console.log('Service worker activated');
    event.waitUntil(
        caches.keys()
            .then(keys => Promise.all(keys.filter(key => key !== 'my-cache').map(key => caches.delete(key)))))
    self.clients.claim();
});
