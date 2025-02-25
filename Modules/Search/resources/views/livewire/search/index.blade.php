<div class="module-search-livewire">
    <div class="search-form mb-4">
        <div class="input-group">
            <input
                type="text"
                wire:model.live.debounce.300ms="searchQuery"
                class="form-control"
                placeholder="{{ $placeholder }}"
                autocomplete="off"
            >
            @if($searchQuery)
                <button
                    class="btn btn-outline-secondary"
                    wire:click="clearSearch"
                    type="button">
                    <i class="fa fa-times"></i>
                </button>
            @endif
        </div>
    </div>

    @if($isLoading)
        <div class="text-center my-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    @endif

    @if($searchQuery && !empty($results) && !$isLoading)
        <div class="search-results">
            <h5 class="mb-3">{{ _e('Search Results') }}</h5>
            <div class="list-group">
                @foreach($results as $result)
                    <a href="{{ content_link($result['id']) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">{{ $result['title'] }}</h5>
                            @if(isset($result['created_at']))
                                <small>{{ date('M d, Y', strtotime($result['created_at'])) }}</small>
                            @endif
                        </div>
                        @if(isset($result['description']))
                            <p class="mb-1">{{ str_limit(strip_tags($result['description']), 150) }}</p>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    @elseif($searchQuery && empty($results) && !$isLoading)
        <div class="alert alert-info">
            {{ _e('No results found for') }} "{{ $searchQuery }}"
        </div>
    @endif
</div>


