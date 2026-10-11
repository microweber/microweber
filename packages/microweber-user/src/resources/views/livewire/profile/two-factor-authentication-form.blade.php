<div class="mt-2 mb-2">

    <button type="button" class="btn btn-outline-primary " wire:click="showForm()">
        Two Factor Authentication
    </button>

    <div class="p-3 mt-2" style="@if(!$showForm) display: none; @endif border: 1px solid #cfcfcf;border-radius: 6px;background: #f2f2f2;">

    {{ __('Two Factor Authentication') }}
    {{ __('Add additional security to your account using two factor authentication.') }}

    <div>
        <h3 class="h5 font-weight-bold">
            @if ($this->enabled)
                {{ __('You have enabled two factor authentication.') }}
            @else
                {{ __('You have not enabled two factor authentication.') }}
            @endif
        </h3>

        <p class="mt-3">
            {{ __('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s Google Authenticator application.') }}
        </p>

        @if ($this->enabled)
            @if ($showingQrCode)
                <p class="mt-3">
                    {{ __('Two factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application.') }}
                </p>

                <div class="mt-3">
                    {!! $this->user->twoFactorQrCodeSvg() !!}
                </div>
            @endif

            @if ($showingRecoveryCodes)
                <p class="mt-3">
                    {{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.') }}
                </p>

                <div class="bg-azure-lt rounded p-3">
                    @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                        <div>{{ $code }}</div>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="mt-3">
            @if (! $this->enabled)
                <x-user::confirms-password wire:then="enableTwoFactorAuthentication">
                    <x-user::button type="button" wire:loading.attr="disabled">
                        {{ __('Enable') }}
                    </x-user::button>
                </x-user::confirms-password>
            @else
                @if ($showingRecoveryCodes)
                    <x-user::confirms-password wire:then="regenerateRecoveryCodes">
                        <x-user::secondary-button class="me-3">
                            <div wire:loading wire:target="regenerateRecoveryCodes" class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>

                            {{ __('Regenerate Recovery Codes') }}
                        </x-user::secondary-button>
                    </x-user::confirms-password>
                @else
                    <x-user::confirms-password wire:then="showRecoveryCodes">
                        <x-user::secondary-button class="me-3">
                            <div wire:loading wire:target="showRecoveryCodes" class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>

                            {{ __('Show Recovery Codes') }}
                        </x-user::secondary-button>
                    </x-user::confirms-password>
                @endif

                <x-user::confirms-password wire:then="disableTwoFactorAuthentication">
                    <x-user::danger-button wire:loading.attr="disabled">
                        <div wire:loading wire:target="disableTwoFactorAuthentication" class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>

                        {{ __('Disable') }}
                    </x-user::danger-button>
                </x-user::confirms-password>
            @endif
        </div>
    </div>
</div>
</div>
