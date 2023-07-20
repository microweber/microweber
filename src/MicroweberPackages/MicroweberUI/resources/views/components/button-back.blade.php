@props(['tooltip' => ''])


<div class="form-control-live-edit-label-wrapper ms-0 d-flex align-items-center">

    <button {{ $attributes->merge(['type' => 'button', 'class' => 'mw-liveedit-button-actions-component']) }}>

            <span class="font-weight-bold">
                {{ $slot }}
                @lang ('Back')
            </span>
    </button>
</div>
