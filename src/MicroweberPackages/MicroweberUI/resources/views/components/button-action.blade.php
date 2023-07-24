@props(['tooltip' => ''])

<div class="form-control-live-edit-label-wrapper d-flex align-items-center">

    <button @if ($tooltip)  data-bs-toggle="tooltip" data-bs-placement="top" title="{{$tooltip}} " @endif {{ $attributes->merge(['type' => 'button', 'class' => 'mw-liveedit-button-actions-component']) }}>
       {{ $slot }}
    </button>
</div>
