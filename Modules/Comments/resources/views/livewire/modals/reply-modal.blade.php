<div tabindex="-1" id="comment-reply-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _e('Reply to comment') }}</h5>
                <button type="button" class="btn-close" @click="$dispatch('closeModal')" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($comment)
                    <div class="text-muted mb-3">
                        {{ _e('Replying to: ') }} {{ $comment->comment_body }}
                    </div>
                @endif

                <form wire:submit="save">
                    @if(!auth()->check() && module_option('comments', 'allow_guest_comments', config('modules.comments.allow_guest_comments')))
                        <div class="mb-3">
                            <input type="text"
                                   class="form-control"
                                   wire:model="state.comment_name"
                                   placeholder="{{ _e('Name') }}">
                        </div>

                        <div class="mb-3">
                            <input type="email"
                                   class="form-control"
                                   wire:model="state.comment_email"
                                   placeholder="{{ _e('Email') }}">
                        </div>
                    @endif

                    <div class="mb-3">
                        <textarea class="form-control"
                                  wire:model="state.comment_body"
                                  rows="4"
                                  placeholder="{{ _e('Write a reply...') }}"></textarea>
                    </div>

                    @if($enableCaptcha)
                        <div class="mb-3">
                            <input type="text"
                                   class="form-control"
                                   wire:model="captcha"
                                   placeholder="{{ _e('Enter captcha') }}">
                            <div class="mt-2">
                                {!! captcha_img() !!}
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
                            {{ _e('Reply') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
