<div class="text-left">
    <div>Tags</div>
    <div>
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
            <button type="button" class="btn btn-outline-primary btn-sm mt-2">
                <span wire:click="filterTag('{{$tagSlug}}')"> {{$tagName}}</span>
                @if(in_array($tagSlug, $filteredTags))
                    <span wire:click="filterRemoveTag('{{$tagSlug}}')">
                                X
                    </span>
                @endif
            </button>
        @endforeach
    </div>
    @if(!empty($filteredTags))
    <button type="button" wire:click="filterClearTags()" class="btn btn-outline-danger btn-sm mt-2">
        Clear All
    </button>
    @endif
</div>
