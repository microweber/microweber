{{-- Tailwind Default Pagination --}}
@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination" class="{{ $paginator->resolveClass('wrapper') }}">
    <div class="{{ $paginator->resolveClass('list') }} {{ $paginator->sizeClass() }}">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="{{ $paginator->resolveClass('disabled') }} rounded-l-md" aria-disabled="true" aria-label="Previous">&lsaquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="{{ $paginator->resolveClass('link') }} rounded-l-md" rel="prev" aria-label="Previous">&lsaquo;</a>
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
            <a href="{{ $paginator->nextPageUrl() }}" class="{{ $paginator->resolveClass('link') }} rounded-r-md" rel="next" aria-label="Next">&rsaquo;</a>
        @else
            <span class="{{ $paginator->resolveClass('disabled') }} rounded-r-md" aria-disabled="true" aria-label="Next">&rsaquo;</span>
        @endif
    </div>
</nav>
@endif