@props(['type' => 'submit', 'class' => 'btn btn-dark '])

<div class="form-control-live-edit-label-wrapper">

    <button {{ $attributes->merge(['type' => $type, 'class' => $class]) }}>
        {{ $slot }}
    </button>
</div>
