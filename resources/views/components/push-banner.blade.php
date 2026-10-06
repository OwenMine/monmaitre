@props(['theme' => 'dark'])

<div
    x-data="{
        show: false,
        loading: false,
        async init() {
            // Vérifier si l'utilisateur est déjà abonné
            if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
                return;
            }

            // Vérifier si l'utilisateur a déjà snoozé le bandeau
            const snoozeUntil = localStorage.getItem('push_banner_snoozed_until');
            if (snoozeUntil && parseInt(snoozeUntil) > Date.now()) {
                return;
            }

            // Vérifier si déjà abonné
            const subscribed = await window.isSubscribed();
            if (subscribed) {
                return;
            }

            // Attendre 5 secondes avant d'afficher le bandeau (UX)
            setTimeout(() => {
                this.show = true;
            }, 5000);
        },
        async enable() {
            this.loading = true;
            const ok = await window.enablePushNotifications();
            if (ok) {
                this.show = false;
            }
            this.loading = false;
        },
        snooze() {
            // Cacher 7 jours
            const sevenDaysFromNow = Date.now() + (7 * 24 * 60 * 60 * 1000);
            localStorage.setItem('push_banner_snoozed_until', sevenDaysFromNow);
            this.show = false;
        }
    }"
    x-init="init()"
    x-show="show"
    x-cloak
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 translate-y-8"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-8"
    class="fixed bottom-6 left-4 right-4 md:left-6 md:right-6 lg:left-auto lg:right-6 lg:max-w-md z-40"
    style="display: none;">

    <div class="bg-mm-navy rounded-2xl shadow-2xl shadow-mm-navy/40 border border-white/10 overflow-hidden">

        {{-- Contenu --}}
        <div class="p-5">

            {{-- En-tête --}}
            <div class="flex items-start gap-3 mb-4">
                <div class="flex-shrink-0 w-11 h-11 rounded-xl bg-mm-accent flex items-center justify-center">
                    <x-icons.bell class="w-5 h-5 text-mm-navy" />
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-white text-sm mb-1">
                        Activez les notifications
                    </h3>
                    <p class="text-xs text-gray-300 leading-relaxed">
                        Restez informé des nouveaux événements (propositions, placements, séances) en temps réel.
                    </p>
                </div>
                <button
                    type="button"
                    @click="snooze()"
                    class="flex-shrink-0 w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition"
                    title="Fermer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-2">
                <button
                    type="button"
                    @click="enable()"
                    :disabled="loading"
                    class="flex-1 inline-flex items-center justify-center gap-2 bg-mm-accent text-mm-navy text-sm font-semibold px-4 py-2.5 rounded-lg hover:bg-mm-accent-hover hover:-translate-y-0.5 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!loading" class="inline-flex items-center gap-2">
                        <x-icons.bell class="w-4 h-4" />
                        Activer les notifications
                    </span>
                    <span x-show="loading" x-cloak class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Activation...
                    </span>
                </button>
                <button
                    type="button"
                    @click="snooze()"
                    class="sm:w-auto inline-flex items-center justify-center gap-2 bg-white/5 border border-white/10 text-white text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-white/10 transition">
                    Plus tard
                </button>
            </div>
        </div>

        {{-- Barre décorative en bas --}}
        <div class="h-1 bg-gradient-to-r from-mm-accent via-mm-secondary to-mm-accent"></div>
    </div>
</div>