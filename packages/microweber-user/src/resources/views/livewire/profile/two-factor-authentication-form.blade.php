<div class="mt-2 mb-2">

    <button type="button" class="btn btn-outline-primary " wire:click="toggleForm()">
        Two Factor Authentication
    </button>

    <div class="p-3 mt-2" style="@if(!$showForm) display: none; @endif border: 1px solid #cfcfcf;border-radius: 6px;background: #f2f2f2;">

        {{-- This view drives the modern Fortify TwoFactorSetupComponent (the
             parent of this deprecated component): enable/disable/recovery +
             a password-confirmation modal via confirmPassword/cancelConfirmPassword.
             It no longer uses <x-user::confirms-password>, which called the
             Jetstream ConfirmsPasswords trait methods the Fortify component does
             not have (threw "stopConfirmingPassword not found"). --}}

        @if ($successMessage)
            <div class="alert alert-success py-2 mb-2">{{ $successMessage }}</div>
        @endif
        @if ($errorMessage)
            <div class="alert alert-danger py-2 mb-2">{{ $errorMessage }}</div>
        @endif

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
            @if ($showingRecoveryCodes)
                <p class="mt-3">
                    {{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.') }}
                </p>
                <div class="bg-light rounded p-3" id="recovery-codes-list">
                    @foreach ($this->recoveryCodes as $recoveryCode)
                        <div class="font-monospace">{{ $recoveryCode }}</div>
                    @endforeach
                </div>
            @endif

            <div class="mt-3 d-flex flex-wrap gap-2">
                @if (! $showingRecoveryCodes)
                    <x-user::secondary-button wire:click="showRecoveryCodes" wire:loading.attr="disabled">
                        {{ __('Show Recovery Codes') }}
                    </x-user::secondary-button>
                @endif
                <x-user::secondary-button wire:click="regenerateRecoveryCodes" wire:loading.attr="disabled">
                    {{ __('Regenerate Recovery Codes') }}
                </x-user::secondary-button>
                <x-user::danger-button wire:click="disableTwoFactorAuthentication" wire:loading.attr="disabled">
                    {{ __('Disable') }}
                </x-user::danger-button>
            </div>

        @elseif ($this->pending || $showingQrCode)
            @if ($showingQrCode)
                <p class="mt-3">
                    {{ __('Scan the following QR code using your phone\'s authenticator application, then enter the 6-digit code to finish enabling two factor authentication.') }}
                </p>
                <div class="mt-3" id="two-factor-qr-code">
                    {!! auth()->user()->twoFactorQrCodeSvg() !!}
                </div>
            @endif

            @if ($showingConfirmation)
                <div class="mt-3">
                    <label for="two-factor-code" class="form-label">
                        {{ __('Enter the 6-digit code from your authenticator app') }}
                    </label>
                    <div class="d-flex gap-2">
                        <input wire:model="code" type="text" id="two-factor-code" inputmode="numeric" maxlength="6"
                               placeholder="000000" class="form-control" style="max-width: 160px;" />
                        <x-user::button wire:click="confirmTwoFactorCode" id="confirm-2fa-code-btn" wire:loading.attr="disabled">
                            {{ __('Confirm') }}
                        </x-user::button>
                    </div>
                </div>
            @endif

        @else
            <div class="mt-3">
                <x-user::button wire:click="enableTwoFactorAuthentication" id="enable-2fa-btn" wire:loading.attr="disabled">
                    {{ __('Enable') }}
                </x-user::button>
            </div>
        @endif

        {{-- Password confirmation modal — a plain Livewire-conditional overlay
             (like the parent Fortify view) so it works reliably in every context
             without depending on Bootstrap JS or surviving Livewire DOM morphs. --}}
        @if ($confirmingPassword)
            <div class="mw-tfa-pwd-modal" id="tfa-password-modal"
                 style="position:fixed; inset:0; z-index:1100; display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,.5); padding:1rem;"
                 x-data x-on:keydown.escape.window="$wire.cancelConfirmPassword()">
                <div style="background:#fff; border-radius:10px; box-shadow:0 10px 40px rgba(0,0,0,.25); width:100%; max-width:460px; overflow:hidden;">
                    <div style="padding:1rem 1.25rem; border-bottom:1px solid #eee; font-weight:600;">{{ __('Confirm Password') }}</div>
                    <div style="padding:1.25rem;">
                        <p class="mb-2">{{ __('For your security, please confirm your password to continue.') }}</p>
                        @if ($errorMessage)
                            <div class="text-danger mb-2">{{ $errorMessage }}</div>
                        @endif
                        <input type="password" class="form-control" placeholder="{{ __('Password') }}"
                               id="tfa-confirm-password"
                               wire:model="confirmablePassword"
                               wire:keydown.enter="confirmPassword"
                               x-init="$nextTick(() => $el.focus())" />
                    </div>
                    <div style="padding:1rem 1.25rem; border-top:1px solid #eee; display:flex; justify-content:flex-end; gap:.5rem;">
                        <button type="button" class="btn btn-outline-secondary" wire:click="cancelConfirmPassword" wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </button>
                        <button type="button" class="btn btn-primary" id="tfa-confirm-password-btn" wire:click="confirmPassword" wire:loading.attr="disabled">
                            {{ __('Confirm') }}
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
