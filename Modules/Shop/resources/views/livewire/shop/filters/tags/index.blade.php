<div class="text-left mw-shop-filter-tags">
    {{-- AI-67 / TICKET-ZZ (cycle-74 2026-05-08): bare div → h3 so
         AT users can jump to filter sections via heading nav. --}}
    <h3 class="mw-shop-filter-heading h6 mb-2">{{ __('Tags') }}</h3>
    <div x-data="{showMoreTags: false}">
        @php
            $limited = 5;
            $limitedAvailableTags = [];
            $moreAvailableTags = [];
            $moreAvailableTagsIndex = 0;
            foreach ($availableTags as $tagSlug=>$tagName) {
                if ($moreAvailableTagsIndex >= $limited) {
                    $moreAvailableTags[] = $tagSlug;
                    continue;
                }
                $limitedAvailableTags[$tagSlug] = $tagName;
                $moreAvailableTagsIndex++;
            }
        @endphp
        @foreach($limitedAvailableTags as $tagSlug=>$tagName)
            @include('modules.shop::livewire.shop.filters.tags.tag-button', ['tagSlug'=>$tagSlug, 'tagName'=>$tagName])
        @endforeach


        <div x-show="showMoreTags">
            @foreach($moreAvailableTags as $tagSlug=>$tagName)
                @include('modules.shop::livewire.shop.filters.tags.tag-button', ['tagSlug'=>$tagSlug, 'tagName'=>$tagName])
            @endforeach

                <button type="button" class="btn btn-outline-danger btn-sm mt-2"  x-on:click="showMoreTags = false">
                    {{ __('Hide tags') }}
                </button>
        </div>

        <button type="button" class="btn btn-outline-danger btn-sm mt-2" x-show="!showMoreTags" x-on:click="showMoreTags = true">
            {{ __('Load more tags') }}
        </button>

    </div>
    @if(!empty($filteredTags))
        <button type="button" wire:click="filterClearTags()" class="btn btn-outline-danger btn-sm mt-2">
            {{ __('Clear All') }}
        </button>
    @endif
</div>
