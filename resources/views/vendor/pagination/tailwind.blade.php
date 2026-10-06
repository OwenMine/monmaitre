@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-between flex-wrap gap-4">

        {{-- Info : X à Y sur Z --}}
        <p class="text-xs text-gray-500">
            Affichage de
            <span class="font-semibold text-mm-navy">{{ $paginator->firstItem() }}</span>
            à
            <span class="font-semibold text-mm-navy">{{ $paginator->lastItem() }}</span>
            sur
            <span class="font-semibold text-mm-navy">{{ $paginator->total() }}</span>
            résultat(s)
        </p>

        {{-- Boutons --}}
        <div class="flex items-center gap-1">

            {{-- Précédent --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-300 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:bg-mm-secondary/10 hover:text-mm-secondary transition"
                   aria-label="Page précédente">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            @endif

            {{-- Pages --}}
            @foreach ($elements as $element)
                {{-- Ellipsis --}}
                @if (is_string($element))
                    <span class="inline-flex items-center justify-center w-9 h-9 text-sm text-gray-400">
                        {{ $element }}
                    </span>
                @endif

                {{-- Pages --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-mm-navy text-white text-sm font-semibold shadow-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-600 hover:bg-mm-secondary/10 hover:text-mm-secondary text-sm font-medium transition">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Suivant --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:bg-mm-secondary/10 hover:text-mm-secondary transition"
                   aria-label="Page suivante">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-300 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            @endif

        </div>
    </nav>
@endif