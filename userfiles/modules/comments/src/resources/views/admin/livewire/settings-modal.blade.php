<div>
    <div class="mw-modal">
        <div class="mw-modal-dialog">
            <div class="mw-modal-content">
    <div class="mw-modal-header">
        <h5 class="modal-title">
            {{_e('Comments Settings')}}
        </h5>
        <button type="button" class="btn-close"  aria-label="Close" wire:click="$emit('closeModal')"></button>
    </div>

    <div class="mw-modal-body">

        <div class="mb-3">
            <label class="form-check form-switch" style="width: 500px;">
                <input @if(isset($settings['allow_comments']) && $settings['allow_comments'] == 'n') @else checked="checked" @endif wire:change="toggleSettings('allow_comments')" class="form-check-input" type="checkbox">
                <span class="form-check-label"> {{_e('Allow people to post comments')}}</span>
            </label>
        </div>

        <div class="mb-3">
            <label class="form-check form-switch" style="width: 500px;">
                <input @if(isset($settings['allow_anonymous_comments']) && $settings['allow_anonymous_comments'] == 'n') @else checked="checked" @endif wire:change="toggleSettings('allow_anonymous_comments')" class="form-check-input" type="checkbox">
                <span class="form-check-label"> {{_e('Allow Anonymous Comments')}}</span>
            </label>
        </div>

        <div class="mb-3">
            <label class="form-check form-switch" style="width: 500px;">
                <input @if(isset($settings['requires_approval']) && $settings['requires_approval'] == 'n') @else checked="checked" @endif wire:change="toggleSettings('requires_approval')" class="form-check-input" type="checkbox">
                <span class="form-check-label"> {{_e('Comments require approval')}}</span>
            </label>
        </div>

        <div class="mb-3">
            <label class="form-check form-switch" style="width: 500px;">
                <input @if(isset($settings['enable_captcha']) && $settings['enable_captcha'] == 'n') @else checked="checked" @endif wire:change="toggleSettings('enable_captcha')" class="form-check-input" type="checkbox">
                <span class="form-check-label"> {{_e('Enable Captcha')}}</span>
            </label>
        </div>

    </div>
    <div class="mw-modal-footer d-flex justify-content-between align-items-center">

        <button type="button" class="btn btn-link"   aria-label="Close" wire:click="$emit('closeModal')">
            {{_e('Close')}}
        </button>

    </div>

</div>
</div>
</div>
</div>
