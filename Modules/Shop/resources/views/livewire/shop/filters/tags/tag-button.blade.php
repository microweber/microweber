
<span class="btn-group mt-2" role="group" aria-label="{{ __('Tag :name', ['name' => $tagName]) }}">
    <button type="button"
            class="btn btn-outline-primary btn-sm"
            wire:click="filterTag('{{ $tagSlug }}')">
        {{ $tagName }}
    </button>
    @if(in_array($tagSlug, $filteredTags))
        <button type="button"
                class="btn btn-outline-primary btn-sm"
                aria-label="{{ __('Remove tag :name', ['name' => $tagName]) }}"
                wire:click="filterRemoveTag('{{ $tagSlug }}')">
            <span aria-hidden="true">&times;</span>
        </button>
    @endif
</span>
