<div class="text-left">
    <div>Tags</div>
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
            @include('microweber-module-shop::livewire.shop.filters.tags.tag-button', ['tagSlug'=>$tagSlug, 'tagName'=>$tagName])
        @endforeach


        <div x-show="showMoreTags">
            @foreach($moreAvailableTags as $tagSlug=>$tagName)
                @include('microweber-module-shop::livewire.shop.filters.tags.tag-button', ['tagSlug'=>$tagSlug, 'tagName'=>$tagName])
            @endforeach

                <button type="button" class="btn btn-outline-danger btn-sm mt-2"  x-on:click="showMoreTags = false">
                    Hide tags
                </button>
        </div>

        <button type="button" class="btn btn-outline-danger btn-sm mt-2" x-show="!showMoreTags" x-on:click="showMoreTags = true">
            Load more tags
        </button>

    </div>
    @if(!empty($filteredTags))
        <button type="button" wire:click="filterClearTags()" class="btn btn-outline-danger btn-sm mt-2">
            Clear All
        </button>
    @endif
</div>
