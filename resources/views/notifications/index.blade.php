@php
    $layout = 'layouts.parent';
    if (auth()->user()->isAdmin()) {
        $layout = 'layouts.admin';
    } elseif (auth()->user()->isRepetiteur()) {
        $layout = 'layouts.repetiteur';
    }
@endphp

@extends($layout)

@section('title', 'Notifications')

@section('content')

    {{-- En-tête --}}
    <div class="mb-8 flex items-start justify-between flex-wrap gap-4 animate-on-scroll">
        <div>
            <h2 class="text-3xl font-bold text-mm-navy tracking-tight">Mes notifications</h2>
            <p class="text-gray-500 mt-1">
                @if($nonLues > 0)
                    Vous avez <span class="font-semibold text-mm-navy">{{ $nonLues }}</span> notification(s) non lue(s).
                @else
                    Toutes vos notifications ont été lues.
                @endif
            </p>
        </div>

        @if($nonLues > 0)
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-2 border border-gray-200 text-gray-700 text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-gray-50 hover:-translate-y-0.5 transition-all duration-300">
                    <x-icons.check-badge class="w-4 h-4" />
                    Tout marquer comme lu
                </button>
            </form>
        @endif
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm animate-on-scroll">
            {{ session('success') }}
        </div>
    @endif

    {{-- Onglets de filtre --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-2 mb-6 flex flex-wrap gap-2 animate-on-scroll">
        <a href="{{ route('notifications.index', ['filtre' => 'toutes']) }}"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ $filtre === 'toutes' ? 'bg-mm-navy text-white' : 'text-gray-600 hover:bg-gray-50' }}">
            Toutes
            <span class="ml-1 text-xs opacity-75">({{ $counts['toutes'] }})</span>
        </a>
        <a href="{{ route('notifications.index', ['filtre' => 'non_lues']) }}"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ $filtre === 'non_lues' ? 'bg-mm-accent text-mm-navy' : 'text-gray-600 hover:bg-gray-50' }}">
            Non lues
            <span class="ml-1 text-xs opacity-75">({{ $counts['non_lues'] }})</span>
        </a>
        <a href="{{ route('notifications.index', ['filtre' => 'lues']) }}"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ $filtre === 'lues' ? 'bg-mm-secondary text-white' : 'text-gray-600 hover:bg-gray-50' }}">
            Lues
            <span class="ml-1 text-xs opacity-75">({{ $counts['lues'] }})</span>
        </a>
    </div>

    {{-- Liste --}}
    @if ($notifications->count() > 0)
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden animate-on-scroll">
            <div class="divide-y divide-gray-100">
                @foreach ($notifications as $notification)
                    @php
                        $isUnread = is_null($notification->read_at);
                        $data = $notification->data;
                        $icone = $data['icone'] ?? 'bell';
                    @endphp

                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-6 py-5 flex items-start gap-4 transition {{ $isUnread ? 'bg-mm-accent/5 hover:bg-mm-accent/10' : 'hover:bg-gray-50' }}">

                            {{-- Icône --}}
                            <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center
                                {{ $isUnread ? 'bg-mm-accent text-mm-navy' : 'bg-gray-100 text-gray-500' }}">
                                <x-dynamic-component :component="'icons.' . $icone" class="w-5 h-5" />
                            </div>

                            {{-- Contenu --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3">
                                    <p class="font-semibold text-mm-navy text-sm {{ $isUnread ? 'font-bold' : '' }}">
                                        {{ $data['titre'] ?? 'Notification' }}
                                    </p>
                                    @if($isUnread)
                                        <span class="inline-flex w-2 h-2 rounded-full bg-mm-accent flex-shrink-0 mt-1.5"></span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 mt-1 leading-relaxed">
                                    {{ $data['message'] ?? '' }}
                                </p>
                                <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                                    <x-icons.clock class="w-3 h-3" />
                                    {{ $notification->created_at->diffForHumans() }}
                                    @if(!$isUnread)
                                        <span class="ml-2 text-green-600 font-semibold">✓ Lue</span>
                                    @endif
                                </p>
                            </div>

                            {{-- Flèche --}}
                            <div class="flex-shrink-0 self-center">
                                <x-icons.arrow-right class="w-4 h-4 text-gray-400" />
                            </div>
                        </button>
                    </form>
                @endforeach
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $notifications->links() }}
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 py-16 text-center animate-on-scroll">
            <x-icons.bell class="w-12 h-12 text-gray-300 mx-auto mb-3" />
            <p class="text-gray-500 font-medium">
                @if($filtre === 'non_lues')
                    Aucune notification non lue.
                @elseif($filtre === 'lues')
                    Aucune notification lue.
                @else
                    Aucune notification pour le moment.
                @endif
            </p>
            <p class="text-sm text-gray-400 mt-1">
                Vous serez notifié dès qu'un événement vous concernant se produira.
            </p>
        </div>
    @endif

@endsection