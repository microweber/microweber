<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation">
            <span>
                @if ($paginator->onFirstPage())
                    <span>Previous</span>
                @else
                    <button class="btn btn-outline-primary" wire:click="previousPage" wire:loading.attr="disabled" rel="prev">
                        Previous
                    </button>
                @endif
            </span>

            <span>
                @if ($paginator->onLastPage())
                    <span>Next</span>
                @else
                    <button class="btn btn-outline-primary" wire:click="nextPage" wire:loading.attr="disabled" rel="next">Next</button>
                @endif
            </span>
        </nav>
    @endif
</div>
