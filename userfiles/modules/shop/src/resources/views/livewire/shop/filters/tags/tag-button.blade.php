<button type="button" class="btn btn-outline-primary btn-sm mt-2">
    <span wire:click="filterTag('{{$tagSlug}}')"> {{$tagName}}</span>
    @if(in_array($tagSlug, $filteredTags))
        <span wire:click="filterRemoveTag('{{$tagSlug}}')">
                                X
                    </span>
    @endif
</button>
