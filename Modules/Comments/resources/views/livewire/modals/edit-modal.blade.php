<div tabindex="-1" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _e('Edit comment') }}</h5>
                <button type="button" class="btn-close" @click="$dispatch('closeModal')" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form wire:submit="save">
                    <div class="mb-3">
                        <textarea class="form-control"
                                  wire:model="state.comment_body"
                                  rows="4"
                                  placeholder="{{ _e('Edit your comment...') }}"></textarea>
                    </div>

                    @if($enableCaptcha)
                        <div class="mb-3">

                            <div class="mt-2">
                                <module type="captcha" id="js-captcha-module-edit" />
                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button"
                                class="btn btn-secondary"
                                @click="$dispatch('closeModal')">
                            {{ _e('Cancel') }}
                        </button>
                        <button type="submit"
                                class="btn btn-primary">
                            {{ _e('Save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
