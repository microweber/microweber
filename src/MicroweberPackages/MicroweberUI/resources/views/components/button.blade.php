<div class="form-control-live-edit-label-wrapper">

    <button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-dark  ']) }}>
        {{ $slot }}
    </button>
</div>
