{{-- Bootstrap Dropdown Pagination --}}
@if ($paginator->hasPages())
<nav aria-label="Pagination" class="{{ $paginator->resolveClass('wrapper') }}">
    <div class="d-flex align-items-center gap-2">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <button class="btn btn-outline-secondary {{ $paginator->sizeClass() === 'pagination-sm' ? 'btn-sm' : ($paginator->sizeClass() === 'pagination-lg' ? 'btn-lg' : '') }}" disabled>&lsaquo;</button>
        @else
            <a class="btn btn-outline-secondary {{ $paginator->sizeClass() === 'pagination-sm' ? 'btn-sm' : ($paginator->sizeClass() === 'pagination-lg' ? 'btn-lg' : '') }}" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a>
        @endif

        {{-- Dropdown --}}
        <div class="dropdown">
            <button class="btn btn-outline-primary dropdown-toggle {{ $paginator->sizeClass() === 'pagination-sm' ? 'btn-sm' : ($paginator->sizeClass() === 'pagination-lg' ? 'btn-lg' : '') }}" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Page {{ $paginator->getCurrentPage() }} of {{ $paginator->getLastPage() }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end" style="max-height: 300px; overflow-y: auto;">
                @foreach ($paginator->elements() as $element)
                    @if ($element['type'] === 'page')
                        <li>
                            <a class="dropdown-item {{ $element['active'] ? ($paginator->resolveClass('active') ?: 'active') : '' }}" href="{{ $element['url'] }}">
                                Page {{ $element['page'] }}
                            </a>
                        </li>
                    @elseif ($element['type'] === 'dots')
                        <li><hr class="dropdown-divider"></li>
                    @endif
                @endforeach
            </ul>
        </div>

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a class="btn btn-outline-secondary {{ $paginator->sizeClass() === 'pagination-sm' ? 'btn-sm' : ($paginator->sizeClass() === 'pagination-lg' ? 'btn-lg' : '') }}" href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a>
        @else
            <button class="btn btn-outline-secondary {{ $paginator->sizeClass() === 'pagination-sm' ? 'btn-sm' : ($paginator->sizeClass() === 'pagination-lg' ? 'btn-lg' : '') }}" disabled>&rsaquo;</button>
        @endif
    </div>
</nav>
@endif