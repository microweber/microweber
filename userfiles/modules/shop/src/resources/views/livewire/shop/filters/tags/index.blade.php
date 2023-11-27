<div class="text-left">
    <div>Tags</div>
    <div>
        @foreach($availableTags as $tagSlug=>$tagName)
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
