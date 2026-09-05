self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open('pos-umkm-v1').then((cache) => cache.addAll([
      '/',
      '/login',
      '/images/lg.png',
      '/images/pos-umkm-hero.svg'
    ]))
  );
});
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then(keys => Promise.all(keys.filter(k => k !== 'pos-umkm-v1').map(k => caches.delete(k))))
  );
});
self.addEventListener('fetch', (event) => {
  const req = event.request;
  event.respondWith(
    caches.match(req).then((cached) => cached || fetch(req).catch(() => caches.match('/')))
  );
});
