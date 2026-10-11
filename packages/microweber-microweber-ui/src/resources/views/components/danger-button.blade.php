<div class="form-control-live-edit-label-wrapper">

    <button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-danger text-white  ']) }}>
        {{ $slot }}
    </button>
</div>
