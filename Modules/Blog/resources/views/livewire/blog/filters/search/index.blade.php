<div class="mb-4">
    <div class="card">
        <div class="card-body">
            <div class="input-group">
                <input 
                    type="text" 
                    wire:model.live="search" 
                    class="form-control" 
                    placeholder="{{ _e('Search posts...') }}">
                @if($search)
                    <button 
                        class="btn btn-outline-secondary" 
                        wire:click="$set('search', '')" 
                        type="button">
                        <i class="fa fa-times"></i>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

@if(!empty($activeFilters))
    <div class="mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">{{ _e('Active Filters') }}</h5>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($activeFilters as $filter)
                        <span class="badge bg-primary">
                            {{ $filter }}
                            <button wire:click="removeFilter('{{ $filter }}')" class="btn-close btn-close-white ms-2"></button>
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
