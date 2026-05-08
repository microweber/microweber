{{-- AI-67 / TICKET-ZZ (cycle-74 2026-05-08): the prior shape was
     `<button>` wrapping `<span wire:click>` — keyboard activation
     fired the parent button (no handler, no-op) instead of the
     inner span's click. Two real <button>s in a tight inline
     group: the toggle button carries the wire:click, the X
     button removes the tag and gets a proper aria-label. The
     visual btn-group keeps the rounded-pill appearance. --}}
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
