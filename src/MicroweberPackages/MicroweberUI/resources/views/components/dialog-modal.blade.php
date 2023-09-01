@props(['id' => null, 'maxWidth' => 'sm'])

@php
$id = $id ?? md5($attributes->wire('model'));
@endphp

<x-microweber-ui::modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="mw-modal-content">
        <div class="mw-modal-header">
            <h5 class="mw-modal-title">{{ $title }}</h5>
            <button type="button" class="btn-close" id="js-close-modal-{{$id}}"></button>
        </div>
        <div class="mw-modal-body">
            {{ $content }}
        </div>
        <div class="mw-modal-footer d-flex justify-content-between align-items-center">
            {{ $footer }}
        </div>
    </div>
</x-microweber-ui::modal>
