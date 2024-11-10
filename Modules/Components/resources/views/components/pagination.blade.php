@if ($items->hasPages())
    <nav>
        <ul class="pagination{{ $size ? ' pagination-' . $size : '' }}">
            {{-- Previous Page Link --}}
            <li class="page-item{{ $items->onFirstPage() ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $items->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
            </li>

            {{-- Pagination Elements --}}
            @foreach ($items->getUrlRange(1, $items->lastPage()) as $page => $url)
                <li class="page-item{{ $page == $items->currentPage() ? ' active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            {{-- Next Page Link --}}
            <li class="page-item{{ !$items->hasMorePages() ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $items->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
            </li>
        </ul>
    </nav>
@endif
