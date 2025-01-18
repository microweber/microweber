<div tabindex="-1" id="comment-delete-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _e('Delete Comment') }}</h5>
                <button type="button" class="btn-close" @click="$dispatch('closeModal')" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">
                    {{ _e('Are you sure you want to delete this comment?') }}
                    {{ _e('This action cannot be undone.') }}
                </p>

                <div class="d-flex justify-content-end gap-2">
                    <button type="button"
                            class="btn btn-secondary"
                            @click="$dispatch('closeModal')">
                        {{ _e('Cancel') }}
                    </button>
                    <button type="button"
                            wire:click="delete"
                            class="btn btn-danger">
                        {{ _e('Delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
