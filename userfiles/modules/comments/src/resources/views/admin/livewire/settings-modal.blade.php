<div>

    <div class="modal-header bg-light border-0">
        <h5 class="modal-title">
            {{_e('Comments Settings')}}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="$emit('closeModal')"></button>

    </div>
    <div class="modal-body">

        <div class="mb-3">
            <label class="form-check form-switch" style="width: 500px;">
                <input wire:model="settings.allow_comments" class="form-check-input" type="checkbox">
                <span class="form-check-label"> {{_e('Allow people to post comments')}}</span>
            </label>
        </div>

        <div class="mb-3">
            <label class="form-check form-switch" style="width: 500px;">
                <input wire:model="settings.allow_anonymous_comments" class="form-check-input" type="checkbox">
                <span class="form-check-label"> {{_e('Allow Anonymous Comments')}}</span>
            </label>
        </div>

        <div class="mb-3">
            <label class="form-check form-switch" style="width: 500px;">
                <input wire:model="settings.requires_approval" class="form-check-input" type="checkbox">
                <span class="form-check-label"> {{_e('Comments require approval')}}</span>
            </label>
        </div>

        <div class="mb-3">
            <label class="form-check form-switch" style="width: 500px;">
                <input wire:model="settings.enable_captcha" class="form-check-input" type="checkbox">
                <span class="form-check-label"> {{_e('Enable Captcha')}}</span>
            </label>
        </div>

    </div>
    <div class="modal-footer bg-light border-0 d-flex justify-content-between align-items-center">

        <button type="button" class="btn btn-link" data-bs-dismiss="modal" aria-label="Close" wire:click="$emit('closeModal')">
            {{_e('Close')}}
        </button>

    </div>

</div>
