<div class="form-control-live-edit-label-wrapper">

    <button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-dark text-uppercase']) }}>
        {{ $slot }}
    </button>
</div>
