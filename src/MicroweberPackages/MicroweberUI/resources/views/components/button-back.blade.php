@props(['tooltip' => ''])


<div class="form-control-live-edit-label-wrapper d-flex align-items-center">

    <button {{ $attributes->merge(['type' => 'button', 'class' => 'mw-live-edit-toolbar-link mw-live-edit-toolbar-link--arrowed mw-liveedit-button-actions-component']) }}>

            <span class="ms-1 font-weight-bold">
                {{ $slot }}
                @lang ('Back')
            </span>
    </button>
</div>
