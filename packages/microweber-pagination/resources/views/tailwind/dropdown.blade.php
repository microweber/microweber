{{-- Tailwind Dropdown Pagination --}}
@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination" class="{{ $paginator->resolveClass('wrapper') }}">
    <div class="inline-flex items-center gap-2 {{ $paginator->sizeClass() }}">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-gray-300 cursor-not-allowed">&lsaquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50" rel="prev">&lsaquo;</a>
        @endif

        {{-- Dropdown --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false" type="button"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Page {{ $paginator->getCurrentPage() }} of {{ $paginator->getLastPage() }}
                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>

            <div x-show="open" x-transition
                 class="absolute z-50 mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 max-h-60 overflow-y-auto">
                <div class="py-1">
                    @foreach ($paginator->elements() as $element)
                        @if ($element['type'] === 'page')
                            <a href="{{ $element['url'] }}"
                               class="block px-4 py-2 text-sm {{ $element['active'] ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                                Page {{ $element['page'] }}
                            </a>
                        @elseif ($element['type'] === 'dots')
                            <span class="block px-4 py-2 text-sm text-gray-400">&hellip;</span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50" rel="next">&rsaquo;</a>
        @else
            <span class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-gray-300 cursor-not-allowed">&rsaquo;</span>
        @endif
    </div>
</nav>
@endif