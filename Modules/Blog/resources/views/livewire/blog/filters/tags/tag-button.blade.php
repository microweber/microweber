<button 
    wire:click="toggleTag({{ $tag->id }})"
    class="btn {{ in_array($tag->id, $selectedTags) ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
    {{ $tag->name }}
    @if($tag->posts_count)
        <span class="badge bg-{{ in_array($tag->id, $selectedTags) ? 'light text-primary' : 'primary' }} ms-1">
            {{ $tag->posts_count }}
        </span>
    @endif
</button>
