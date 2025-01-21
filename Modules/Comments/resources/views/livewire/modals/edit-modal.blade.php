<div tabindex="-1" id="comment-edit-modal">
    <div class="modal-dialog p-3">
        <div class="modal-content">
            <div class="modal-header justify-content-between d-flex align-items-center">
                <h5 class="modal-title mb-2">{{ _e('Edit comment') }}</h5>
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

                    <div class="d-flex justify-content-end gap-3">
                        <button type="button"
                                class="btn btn-secondary btn-sm"
                                @click="$dispatch('closeModal')">
                            {{ _e('Cancel') }}
                        </button>
                        <button type="submit"
                                class="btn btn-primary btn-sm">
                            {{ _e('Save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
