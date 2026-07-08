{{-- Bootstrap Flex Pagination --}}
@if ($paginator->hasPages())
<nav aria-label="Pagination">
    <ul class="{{ $paginator->resolveClass('list') }} {{ $paginator->sizeClass() }} flex-wrap justify-content-center">
        {{-- First Page Link --}}
        @if (!$paginator->onFirstPage())
            <li class="{{ $paginator->resolveClass('item') }}">
                <a class="{{ $paginator->resolveClass('link') }}" href="{{ $paginator->firstPageUrl() }}" aria-label="First">&laquo;</a>
            </li>
        @endif

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="{{ $paginator->resolveClass('item') }} {{ $paginator->resolveClass('disabled') }}" aria-disabled="true">
                <span class="{{ $paginator->resolveClass('link') }}" aria-hidden="true">&lsaquo;</span>
            </li>
        @else
            <li class="{{ $paginator->resolveClass('item') }}">
                <a class="{{ $paginator->resolveClass('link') }}" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">&lsaquo;</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($paginator->elements() as $element)
            @if ($element['type'] === 'dots')
                <li class="{{ $paginator->resolveClass('item') }} {{ $paginator->resolveClass('disabled') }}" aria-disabled="true">
                    <span class="{{ $paginator->resolveClass('link') }}">&hellip;</span>
                </li>
            @else
                @if ($element['active'])
                    <li class="{{ $paginator->resolveClass('item') }} {{ $paginator->resolveClass('active') }}" aria-current="page">
                        <span class="{{ $paginator->resolveClass('link') }}">{{ $element['page'] }}</span>
                    </li>
                @else
                    <li class="{{ $paginator->resolveClass('item') }}">
                        <a class="{{ $paginator->resolveClass('link') }}" href="{{ $element['url'] }}">{{ $element['page'] }}</a>
                    </li>
                @endif
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="{{ $paginator->resolveClass('item') }}">
                <a class="{{ $paginator->resolveClass('link') }}" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">&rsaquo;</a>
            </li>
        @else
            <li class="{{ $paginator->resolveClass('item') }} {{ $paginator->resolveClass('disabled') }}" aria-disabled="true">
                <span class="{{ $paginator->resolveClass('link') }}" aria-hidden="true">&rsaquo;</span>
            </li>
        @endif

        {{-- Last Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="{{ $paginator->resolveClass('item') }}">
                <a class="{{ $paginator->resolveClass('link') }}" href="{{ $paginator->lastPageUrl() }}" aria-label="Last">&raquo;</a>
            </li>
        @endif
    </ul>
</nav>
@endif