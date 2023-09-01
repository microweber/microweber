@props(['tooltip' => ''])


<div class="form-control-live-edit-label-wrapper ms-0 d-flex align-items-center">

    <button class="col btn btn-link mw-live-edit-toolbar-link mw-live-edit-toolbar-link--arrowed text-start text-start" {{ $attributes->merge(['type' => 'button']) }}>

        <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10"><circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle><path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path></g></svg>


        <span class="ms-1 font-weight-bold">
                {{ $slot }}
                @lang ('Back')
            </span>
    </button>
</div>
