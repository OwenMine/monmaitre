/* ============================================
   MON MAÎTRE — APP.JS
   ============================================ */

/* ---------- 1. FONCTIONS PUSH (AVANT ALPINE) ---------- */

/**
 * Convertit une clé VAPID base64url en Uint8Array.
 */
function urlBase64ToUint8Array(base64String) {
    const padding = "=".repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, "+")
        .replace(/_/g, "/");
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

async function isSubscribed() {
    if (!("serviceWorker" in navigator) || !("PushManager" in window)) {
        return false;
    }
    try {
        const registration = await navigator.serviceWorker.ready;
        const subscription = await registration.pushManager.getSubscription();
        return subscription !== null;
    } catch (error) {
        console.error("Erreur vérification abonnement :", error);
        return false;
    }
}

async function enablePushNotifications() {
    if (!("serviceWorker" in navigator) || !("PushManager" in window)) {
        alert("Votre navigateur ne supporte pas les notifications push.");
        return false;
    }
    try {
        const permission = await Notification.requestPermission();
        if (permission !== "granted") {
            alert("Vous devez autoriser les notifications pour les recevoir.");
            return false;
        }
        const response = await fetch("/push/public-key", {
            headers: {
                "X-CSRF-TOKEN":
                    document.querySelector('meta[name="csrf-token"]')
                        ?.content || "",
                Accept: "application/json",
            },
        });
        const data = await response.json();
        const applicationServerKey = urlBase64ToUint8Array(data.publicKey);
        const registration = await navigator.serviceWorker.ready;
        const subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: applicationServerKey,
        });
        const subscribeResponse = await fetch("/push/subscribe", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN":
                    document.querySelector('meta[name="csrf-token"]')
                        ?.content || "",
                Accept: "application/json",
            },
            body: JSON.stringify(subscription),
        });
        if (!subscribeResponse.ok) {
            throw new Error("Erreur lors de l'enregistrement côté serveur.");
        }
        console.log("✅ Notifications push activées");
        return true;
    } catch (error) {
        console.error("❌ Erreur activation push :", error);
        alert("Erreur lors de l'activation : " + error.message);
        return false;
    }
}

async function disablePushNotifications() {
    try {
        const registration = await navigator.serviceWorker.ready;
        const subscription = await registration.pushManager.getSubscription();
        if (!subscription) return true;
        await fetch("/push/unsubscribe", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN":
                    document.querySelector('meta[name="csrf-token"]')
                        ?.content || "",
                Accept: "application/json",
            },
            body: JSON.stringify({ endpoint: subscription.endpoint }),
        });
        await subscription.unsubscribe();
        console.log("✅ Notifications push désactivées");
        return true;
    } catch (error) {
        console.error("❌ Erreur désactivation push :", error);
        return false;
    }
}

// ⚠️ EXPOSER GLOBALEMENT AVANT Alpine.start()
window.isSubscribed = isSubscribed;
window.enablePushNotifications = enablePushNotifications;
window.disablePushNotifications = disablePushNotifications;

/* ---------- 2. SERVICE WORKER ---------- */

if ("serviceWorker" in navigator) {
    window.addEventListener("load", () => {
        navigator.serviceWorker
            .register("/service-worker.js")
            .then((registration) => {
                console.log(
                    "✅ Service Worker enregistré :",
                    registration.scope,
                );
            })
            .catch((error) => {
                console.error("❌ Erreur Service Worker :", error);
            });
    });
}

/* ---------- 3. ALPINE.JS ---------- */

import Alpine from "alpinejs";
window.Alpine = Alpine;
import collapse from "@alpinejs/collapse";
Alpine.plugin(collapse);
Alpine.start();

/* ---------- 4. ANIMATIONS AU SCROLL ---------- */

document.addEventListener("DOMContentLoaded", () => {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("is-visible");
                    observer.unobserve(entry.target);
                }
            });
        },
        {
            threshold: 0.05, // Au lieu de 0.1 → moins de recalculs
            rootMargin: "0px 0px -100px 0px", // Moins de marge
        },
    );

    document.querySelectorAll(".animate-on-scroll").forEach((el) => {
        observer.observe(el);
    });
});

/* ---------- 5. NAVBAR SCROLL EFFECT ---------- */

document.addEventListener("DOMContentLoaded", () => {
    const header = document.querySelector("header[data-scroll-header]");
    if (header) {
        const handleScroll = () => {
            if (window.scrollY > 20) {
                header.classList.add("shadow-lg");
            } else {
                header.classList.remove("shadow-lg");
            }
        };
        window.addEventListener("scroll", handleScroll, { passive: true });
    }
});

import Chart from "chart.js/auto";
window.Chart = Chart;
