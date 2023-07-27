<div>
    <div class="mw-modal">
        <div class="mw-modal-dialog" role="document">
            <div class="mw-modal-content">
                <div class="mw-modal-header">
                    <h5 class="mw-modal-title">
                        {{_e('Contact Form Integrations')}}
                    </h5>
                    <button type="button" class="btn-close" wire:click="$emit('closeModal')"
                            aria-label="Close"></button>
                </div>
                <div class="mw-modal-body">
                    <module type="admin/mail_providers/show_all"/>
                </div>
            </div>
        </div>
    </div>
</div>
