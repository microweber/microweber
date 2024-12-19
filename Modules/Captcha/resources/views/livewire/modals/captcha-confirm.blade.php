<div>
    <div>
        <div class="modal-header">
            <h5 class="modal-title">
                Confirm you are not a robot
            </h5>
            <button type="button" class="btn-close mw-process-close-modal-button"
                    @click="$dispatch('closeModal')"></button>
        </div>
        <div class="modal-body text-center">

            <div>
                <div>
                    @php
                        $randId = time().rand(1000,9000);
                    @endphp

                    @script
                    <script id="js-captcha-module-script">
                        window.captchaResponse = function (value) {
                            let captchaDataElement = document.getElementById('js-captcha-value');
                            if (captchaDataElement) {
                                captchaDataElement.value = value;
                                captchaDataElement.dispatchEvent(new Event('input'));
                            }

                        }
                    </script>
                    @endscript
                    <div>
                        <module type="captcha" id="js-captcha-module"
                                data-callback="captchaResponse"
                        />
                    </div>
                    <input type="hidden" id="js-captcha-value" wire:model.defer="captcha"/>
                </div>
            </div>

            <div class="mt-4">
                <button type="button" wire:click="confirm" class="btn btn-outline-primary">
                    {{_e('Confirm')}}
                </button>
                <button type="button" @click="$dispatch('closeModal')" class="btn btn-outline-primary">
                    {{_e('Cancel')}}
                </button>
            </div>

        </div>
    </div>
</div>
