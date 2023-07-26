@props(['id' => null, 'maxWidth' => null])

<x-microweber-ui::modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ $title }}</h5>
            <button type="button" class="btn-close" id="js-close-modal-{{$id}}"></button>
        </div>
        <div class="modal-body">
            {{ $content }}
        </div>
        <div class="modal-footer bg-azure-lt">
            {{ $footer }}
        </div>
    </div>
</x-microweber-ui::modal>
