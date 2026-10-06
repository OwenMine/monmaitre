/* ============================================
   MON MAÎTRE — SERVICE WORKER
   Version 1.0.0
   ============================================ */

const CACHE_NAME = "monmaitre-v1.0.1";
const OFFLINE_URL = "/offline";

// Ressources à mettre en cache immédiatement (au premier chargement)
const PRECACHE_URLS = [
    "/",
    "/offline",
    "/images/logo-full.png",
    "/images/logo-icon.png",
    "/images/pwa/icon-192.png",
    "/images/pwa/icon-512.png",
];

/* ============================================
   1. INSTALLATION
   ============================================ */
self.addEventListener("install", (event) => {
    console.log("[SW] Installation...");

    event.waitUntil(
        caches
            .open(CACHE_NAME)
            .then((cache) => {
                console.log("[SW] Mise en cache des ressources initiales");
                return cache.addAll(PRECACHE_URLS).catch((err) => {
                    console.warn(
                        "[SW] Certaines ressources n'ont pas pu être mises en cache :",
                        err,
                    );
                });
            })
            .then(() => self.skipWaiting()),
    );
});

/* ============================================
   2. ACTIVATION
   ============================================ */
self.addEventListener("activate", (event) => {
    console.log("[SW] Activation...");

    event.waitUntil(
        caches
            .keys()
            .then((cacheNames) => {
                return Promise.all(
                    cacheNames
                        .filter((name) => name !== CACHE_NAME)
                        .map((name) => {
                            console.log(
                                "[SW] Suppression ancien cache :",
                                name,
                            );
                            return caches.delete(name);
                        }),
                );
            })
            .then(() => self.clients.claim()),
    );
});

/* ============================================
   3. INTERCEPTION DES REQUÊTES (FETCH)
   ============================================ */
self.addEventListener("fetch", (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Ignorer les requêtes non-GET, cross-origin, et les routes API/admin
    if (request.method !== "GET") return;
    if (url.origin !== location.origin) return;
    if (url.pathname.startsWith("/api/")) return;
    if (url.pathname.startsWith("/admin/")) return;
    if (url.pathname.startsWith("/storage/")) return;

    // STRATÉGIE : Network First, fallback sur cache, fallback sur page offline
    event.respondWith(
        fetch(request)
            .then((response) => {
                // Mettre en cache les réponses réussies (sauf si 404, 500, etc.)
                if (response.ok && response.status === 200) {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(request, responseClone);
                    });
                }
                return response;
            })
            .catch(() => {
                // Si le réseau échoue, chercher dans le cache
                return caches.match(request).then((cachedResponse) => {
                    if (cachedResponse) {
                        return cachedResponse;
                    }

                    // Si la page demandée est une page HTML → servir la page offline
                    if (request.headers.get("accept")?.includes("text/html")) {
                        return caches.match(OFFLINE_URL);
                    }

                    // Sinon retourner une erreur
                    return new Response("Ressource indisponible", {
                        status: 503,
                        statusText: "Service Unavailable",
                    });
                });
            }),
    );
});

/* ============================================
   4. RÉCEPTION DES NOTIFICATIONS PUSH
   ============================================ */
self.addEventListener("push", (event) => {
    console.log("[SW] Notification push reçue");

    let data = {
        title: "MON MAÎTRE",
        body: "Vous avez une nouvelle notification",
        icon: "/images/pwa/icon-192.png",
        badge: "/images/pwa/icon-72.png",
        url: "/",
        tag: "default",
    };

    // Parser les données reçues
    if (event.data) {
        try {
            const parsed = event.data.json();
            data = { ...data, ...parsed };
        } catch (e) {
            data.body = event.data.text();
        }
    }

    const options = {
        body: data.body,
        icon: data.icon,
        badge: data.badge,
        tag: data.tag,
        data: {
            url: data.url,
        },
        vibrate: [200, 100, 200],
        requireInteraction: false,
        actions: [
            { action: "open", title: "Ouvrir" },
            { action: "close", title: "Ignorer" },
        ],
    };

    event.waitUntil(self.registration.showNotification(data.title, options));
});

/* ============================================
   5. CLIC SUR LA NOTIFICATION
   ============================================ */
self.addEventListener("notificationclick", (event) => {
    console.log("[SW] Clic sur notification");

    event.notification.close();

    // Si l'utilisateur a cliqué sur "Ignorer"
    if (event.action === "close") {
        return;
    }

    // Récupérer l'URL à ouvrir
    const urlToOpen = event.notification.data?.url || "/";

    // Vérifier si une fenêtre de l'app est déjà ouverte
    event.waitUntil(
        clients
            .matchAll({
                type: "window",
                includeUncontrolled: true,
            })
            .then((windowClients) => {
                // Si une fenêtre existe déjà → la focus
                for (let i = 0; i < windowClients.length; i++) {
                    const client = windowClients[i];
                    if (
                        client.url.includes(self.location.origin) &&
                        "focus" in client
                    ) {
                        client.focus();
                        client.navigate(urlToOpen);
                        return;
                    }
                }
                // Sinon ouvrir une nouvelle fenêtre
                if (clients.openWindow) {
                    return clients.openWindow(urlToOpen);
                }
            }),
    );
});

/* ============================================
   6. MESSAGE DEPUIS LA PAGE PRINCIPALE
   ============================================ */
self.addEventListener("message", (event) => {
    console.log("[SW] Message reçu :", event.data);

    if (event.data?.type === "SKIP_WAITING") {
        self.skipWaiting();
    }
});
