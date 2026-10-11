<x-microweber-ui::action-section>
    <x-slot name="title">
        <?php _e('Two Factor Authentication');?>
    </x-slot>

    <x-slot name="description">
        <?php _e('Add additional security to your account using two factor authentication');?>.
    </x-slot>

    <x-slot name="content">
        <h3 class="form-label">
            @if ($this->enabled)
                <?php _e('You have enabled two factor authentication');?>.
            @else
                <?php _e('You have not enabled two factor authentication');?>.
            @endif
        </h3>

        <div class="mt-3 max-w-xl text-sm text-gray-600">
            <p>
                <?php _e('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phones Google Authenticator application');?>.
            </p>
        </div>

        @if ($this->enabled)
            @if ($showingQrCode)
                <div class="mt-4 max-w-xl text-sm text-gray-600">
                    <p class="font-semibold">
                        <?php _e('Two factor authentication is now enabled. Scan the following QR code using your phones authenticator application');?>.
                    </p>
                </div>

                <div class="mt-4">
                    {!! $this->user->twoFactorQrCodeSvg() !!}
                </div>
            @endif

            @if ($showingRecoveryCodes)
                <div class="mt-4 max-w-xl text-sm text-gray-600">
                    <p class="font-semibold">
                        <?php _e('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost');?>.
                    </p>
                </div>
                <div>
<textarea rows="9" class="form-control" id="recovery_codes">
@foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
{{ $code }}
@endforeach
</textarea>
                </div>


                    <div wire:ignore>
                        <script type="text/javascript">

                            window.copy_recovery_codes = function (){
                                var recovery_codes =  $('#recovery_codes').val();
                                mw.tools.copy(recovery_codes);
                            }
                        </script>

                        <a href="javascript:;" onclick="window.copy_recovery_codes()" class="btn btn-outline btn-sm btn-primary mt-2">
                            Copy
                        </a>

                    </div>





            @endif
        @endif

        <div class="mt-2">
            @if (! $this->enabled)
                <x-microweber-ui::button type="button" wire:click="enableTwoFactorAuthentication" wire:loading.attr="disabled">
                    <?php _e('Enable');?>
                </x-microweber-ui::button>
            @else
                @if ($showingRecoveryCodes)
                    <x-microweber-ui::secondary-button class="mr-3" wire:click="regenerateRecoveryCodes">
                        <?php _e('Regenerate Recovery Codes');?>
                    </x-microweber-ui::secondary-button>
                @else
                    <x-microweber-ui::secondary-button class="mr-3" wire:click="$toggle('showingRecoveryCodes')">
                        <?php _e('Show Recovery Codes');?>
                    </x-microweber-ui::secondary-button>
                @endif

                <x-microweber-ui::danger-button wire:click="disableTwoFactorAuthentication" wire:loading.attr="disabled">
                    <?php _e('Disable');?>
                </x-microweber-ui::danger-button>
            @endif
        </div>
    </x-slot>
</x-microweber-ui::action-section>
