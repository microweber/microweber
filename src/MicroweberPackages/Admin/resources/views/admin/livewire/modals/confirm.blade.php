<div>
    <div class="mw-modal">
        <div class="mw-modal-dialog">
            <div class="mw-modal-content">
                <div class="mw-modal-header">
                    <h5 class="mw-modal-title">
                        {{$title}}
                    </h5>
                    <button type="button" class="btn-close mw-process-close-modal-button"
                            wire:click="$emit('closeModal')"></button>
                </div>
                <div class="mw-modal-body text-center">

                    <div>
                        {{$body}}
                    </div>

                    <div class="mt-4">

                        <x-microweber-ui::button-animation class="text-red" wire:click="confirm">
                            {{$button_text}}
                        </x-microweber-ui::button-animation>

                        &nbsp;
                        &nbsp;
                        &nbsp;
                        <x-microweber-ui::button-animation wire:click="$emit('closeModal')">
                            {{_e('Cancel')}}
                        </x-microweber-ui::button-animation>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
