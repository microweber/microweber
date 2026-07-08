{{-- Tailwind Flex Pagination --}}
@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination" class="flex items-center justify-between">
    <div class="flex justify-between flex-1 sm:hidden">
        @if ($paginator->onFirstPage())
            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-300 cursor-not-allowed rounded-md {{ $paginator->sizeClass() }}">Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:text-gray-500 {{ $paginator->sizeClass() }}">Previous</a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:text-gray-500 {{ $paginator->sizeClass() }}">Next</a>
        @else
            <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-300 bg-white border border-gray-300 cursor-not-allowed rounded-md {{ $paginator->sizeClass() }}">Next</span>
        @endif
    </div>

    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-center">
        <div>
            <span class="{{ $paginator->resolveClass('list') }} {{ $paginator->sizeClass() }}">
                {{-- First Page --}}
                @if (!$paginator->onFirstPage())
                    <a href="{{ $paginator->firstPageUrl() }}" class="{{ $paginator->resolveClass('link') }} rounded-l-md" aria-label="First">&laquo;</a>
                @endif

                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="{{ $paginator->resolveClass('disabled') }}" aria-disabled="true">&lsaquo;</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="{{ $paginator->resolveClass('link') }}" rel="prev">&lsaquo;</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($paginator->elements() as $element)
                    @if ($element['type'] === 'dots')
                        <span class="{{ $paginator->resolveClass('dots') }}" aria-disabled="true">&hellip;</span>
                    @else
                        @if ($element['active'])
                            <span class="{{ $paginator->resolveClass('active') }}" aria-current="page">{{ $element['page'] }}</span>
                        @else
                            <a href="{{ $element['url'] }}" class="{{ $paginator->resolveClass('link') }}">{{ $element['page'] }}</a>
                        @endif
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="{{ $paginator->resolveClass('link') }}" rel="next">&rsaquo;</a>
                @else
                    <span class="{{ $paginator->resolveClass('disabled') }}" aria-disabled="true">&rsaquo;</span>
                @endif

                {{-- Last Page --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->lastPageUrl() }}" class="{{ $paginator->resolveClass('link') }} rounded-r-md" aria-label="Last">&raquo;</a>
                @endif
            </span>
        </div>
    </div>
</nav>
@endif