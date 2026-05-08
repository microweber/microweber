@if(isset($category))
    <div class="mw-shop-filter-category-row">
        {{-- AI-67 / TICKET-ZZ (cycle-74 2026-05-08): see categories/
             index.blade.php for the full rationale on why <span
             wire:click> is replaced with a real <button>. The
             aria-current attribute mirrors the visual `.active`
             class so AT users hear the current category. --}}
        <button type="button"
                role="listitem"
                class="list-group-item list-group-item-action @if($category->id == $filteredCategory) active @endif"
                @if($category->id == $filteredCategory) aria-current="true" @endif
                wire:click="filterCategory('{{$category->id}}')">
            {{ $category->title }}
        </button>

        @if(!empty($category->children))
            <div class="mw-shop-filter-category-children">
                @foreach($category->children as $category)
                    @include('modules.shop::livewire.shop.filters.categories.category-child', ['category' => $category])
                @endforeach
            </div>
        @endif
    </div>
@endif
