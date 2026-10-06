@props(['theme' => 'light'])
{{-- theme : 'light' (header blanc) ou 'dark' (header navy) --}}

<div
    x-data="{
        subscribed: false,
        loading: true,
        async init() {
            this.subscribed = await window.isSubscribed();
            this.loading = false;
        },
        async toggle() {
            this.loading = true;
            if (this.subscribed) {
                const ok = await window.disablePushNotifications();
                if (ok) this.subscribed = false;
            } else {
                const ok = await window.enablePushNotifications();
                if (ok) this.subscribed = true;
            }
            this.loading = false;
        }
    }"
    x-init="init()"
    class="relative">

    <button
        type="button"
        @click="toggle()"
        :disabled="loading"
        class="relative w-10 h-10 rounded-lg flex items-center justify-center transition
            {{ $theme === 'dark' ? 'text-white/80 hover:text-mm-accent hover:bg-white/10' : 'text-gray-600 hover:text-mm-navy hover:bg-gray-100' }}"
        :class="subscribed ? '{{ $theme === 'dark' ? 'text-mm-accent' : 'text-mm-secondary' }}' : ''"
        :title="subscribed ? 'Désactiver les notifications push' : 'Activer les notifications push'">

        {{-- Icône haut-parleur ON --}}
        <svg x-show="subscribed && !loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
        </svg>

        {{-- Icône haut-parleur OFF --}}
        <svg x-show="!subscribed && !loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
        </svg>

        {{-- Spinner de chargement --}}
        <svg x-show="loading" x-cloak class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>

        {{-- Badge "ON" quand abonné --}}
        <span
            x-show="subscribed && !loading"
            x-cloak
            class="absolute -top-0.5 -right-0.5 w-3 h-3 rounded-full bg-green-500 border-2
                {{ $theme === 'dark' ? 'border-mm-navy' : 'border-white' }}">
        </span>
    </button>
</div>