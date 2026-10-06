@props(['theme' => 'light'])
{{-- theme : 'light' (header blanc) ou 'dark' (header navy) --}}

<div class="relative" x-data="{ notifOpen: false }" @click.outside="notifOpen = false">

    {{-- Bouton cloche --}}
    <button
        type="button"
        @click="notifOpen = !notifOpen"
        class="relative w-10 h-10 rounded-lg flex items-center justify-center transition
            {{ $theme === 'dark' ? 'text-white/80 hover:text-mm-accent hover:bg-white/10' : 'text-gray-600 hover:text-mm-navy hover:bg-gray-100' }}">

        <x-icons.bell class="w-5 h-5" />

        {{-- Badge compteur --}}
        @if($globalNotifsCount > 0)
            <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-mm-accent text-mm-navy text-[10px] font-bold flex items-center justify-center border-2
                {{ $theme === 'dark' ? 'border-mm-navy' : 'border-white' }}">
                {{ $globalNotifsCount > 9 ? '9+' : $globalNotifsCount }}
            </span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div
        x-show="notifOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
        class="fixed sm:absolute left-4 right-4 sm:left-auto sm:right-0 mt-2 sm:w-80 md:w-96 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50"
        style="display: none;">

        {{-- En-tête --}}
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-mm-navy text-sm">Notifications</h3>
            @if($globalNotifsCount > 0)
                <span class="text-xs font-semibold px-2 py-0.5 rounded-md bg-mm-accent/20 text-mm-navy">
                    {{ $globalNotifsCount }} non lue(s)
                </span>
            @endif
        </div>

        {{-- Liste --}}
        <div class="max-h-96 overflow-y-auto">
            @if($globalNotifs->count() > 0)
                @foreach($globalNotifs as $notification)
                    @php
                        $isUnread = is_null($notification->read_at);
                        $data = $notification->data;
                        $icone = $data['icone'] ?? 'bell';
                    @endphp

                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-3 flex items-start gap-3 transition border-b border-gray-50 last:border-b-0
                                {{ $isUnread ? 'bg-mm-accent/5 hover:bg-mm-accent/10' : 'hover:bg-gray-50' }}">

                            <div class="flex-shrink-0 w-9 h-9 rounded-lg flex items-center justify-center
                                {{ $isUnread ? 'bg-mm-accent text-mm-navy' : 'bg-gray-100 text-gray-500' }}">
                                <x-dynamic-component :component="'icons.' . $icone" class="w-4 h-4" />
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-start gap-2">
                                    <p class="text-sm {{ $isUnread ? 'font-bold text-mm-navy' : 'font-medium text-gray-700' }} leading-tight">
                                        {{ $data['titre'] ?? 'Notification' }}
                                    </p>
                                    @if($isUnread)
                                        <span class="inline-flex w-1.5 h-1.5 rounded-full bg-mm-accent flex-shrink-0 mt-1.5"></span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-1 leading-snug line-clamp-2">
                                    {{ $data['message'] ?? '' }}
                                </p>
                                <p class="text-[10px] text-gray-400 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </button>
                    </form>
                @endforeach
            @else
                <div class="py-12 text-center">
                    <x-icons.bell class="w-10 h-10 text-gray-300 mx-auto mb-2" />
                    <p class="text-sm text-gray-500">Aucune notification</p>
                </div>
            @endif
        </div>

        {{-- Pied : lien "Voir tout" --}}
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
            <a href="{{ route('notifications.index') }}"
               class="text-sm font-semibold text-mm-secondary hover:text-mm-navy transition inline-flex items-center gap-1">
                Voir toutes les notifications
                <x-icons.arrow-right class="w-3.5 h-3.5" />
            </a>

            @if($globalNotifsCount > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="text-xs text-gray-500 hover:text-mm-navy transition font-medium">
                        Tout marquer lu
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>