@props(['id' => null, 'maxWidth' => null])

<x-user::modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="mw-modal-content">
        <div class="mw-modal-header">
            <h5 class="mw-modal-title">{{ $title }}</h5>
            <button type="button" class="btn-close"  aria-label="Close"></button>
        </div>
        <div class="mw-modal-body">
            {{ $content }}
        </div>
        <div class="mw-modal-footer">
            {{ $footer }}
        </div>
    </div>
</x-user::modal>
