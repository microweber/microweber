<x-filament-panels::page>
    <div>
        @if(! auth()->user()->two_factor_secret)
            {{-- Enable 2FA --}}
            <div class="mt-4">
                <h2 class="text-lg font-medium">{{ __('Two Factor Authentication') }}</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Add additional security to your account using two factor authentication.') }}
                </p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s Google Authenticator application.') }}
                </p>
                <div class="mt-5">
                    <x-filament::button wire:click="enableTwoFactorAuthentication" wire:loading.attr="disabled" color="primary" id="enable-2fa-btn">
                        <span wire:loading.remove>{{ __('Enable') }}</span>
                        <span wire:loading>{{ __('Enabling...') }}</span>
                    </x-filament::button>
                </div>
            </div>
        @else
            {{-- 2FA is enabled or pending confirmation --}}
            <div class="mt-4">
                <h2 class="text-lg font-medium">{{ __('Two Factor Authentication') }}</h2>

                @if(auth()->user()->two_factor_confirmed_at)
                    <div class="mt-2 p-3 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg">
                        <p class="text-green-700 dark:text-green-300 font-medium">✓ {{ __('Two factor authentication is enabled.') }}</p>
                    </div>
                @else
                    <p class="mt-1 text-sm text-yellow-600 dark:text-yellow-400">
                        {{ __('Two factor authentication is enabled but not yet confirmed. Please scan the QR code and enter a code to confirm.') }}
                    </p>
                @endif

                @if ($showingQrCode)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Scan the following QR code using your phone\'s authenticator application.') }}
                        </p>
                        <div class="mt-4 p-4 bg-white dark:bg-gray-900 inline-block rounded-lg shadow" id="two-factor-qr-code">
                            {!! auth()->user()->twoFactorQrCodeSvg() !!}
                        </div>

                        @php
                            $secret = null;
                            if (method_exists(auth()->user(), 'getDecryptedTwoFactorSecret')) {
                                $secret = auth()->user()->getDecryptedTwoFactorSecret();
                            }
                        @endphp
                        @if ($secret)
                            <div class="mt-3">
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Or enter this secret key manually:') }}</p>
                                <code class="text-sm bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded" id="two-factor-secret-key">{{ $secret }}</code>
                            </div>
                        @endif
                    </div>
                @endif

                @if ($showingConfirmation)
                    <div class="mt-4">
                        <form wire:submit.prevent="confirmTwoFactorAuthentication">
                            {{ $this->form }}
                            <div class="mt-4">
                                <x-filament::button type="submit">
                                    {{ __('Confirm') }}
                                </x-filament::button>
                            </div>
                        </form>
                    </div>
                @endif

                @if ($showingRecoveryCodes)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">
                            {{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.') }}
                        </p>
                        <div class="mt-2 p-4 bg-gray-100 dark:bg-gray-800 rounded-lg font-mono text-sm" id="recovery-codes-list">
                            @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
                                <div class="py-0.5">{{ $code }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mt-5 flex flex-wrap gap-2">
                    @if(! $showingRecoveryCodes && auth()->user()->two_factor_confirmed_at)
                        <x-filament::button wire:click="showRecoveryCodes" wire:loading.attr="disabled">
                            {{ __('Show Recovery Codes') }}
                        </x-filament::button>
                    @endif

                    @if(auth()->user()->two_factor_confirmed_at)
                        <x-filament::button wire:click="regenerateRecoveryCodes" wire:loading.attr="disabled">
                            {{ __('Regenerate Recovery Codes') }}
                        </x-filament::button>
                    @endif

                    <x-filament::button wire:click="disableTwoFactorAuthentication" wire:loading.attr="disabled" color="danger">
                        {{ __('Disable') }}
                    </x-filament::button>
                </div>
            </div>
        @endif

        {{-- Password confirmation modal --}}
        @if ($this->confirmingPassword)
            <x-filament::modal id="confirm-password" wire:model="confirmingPassword"
                               :heading="__('Confirm Password')"
                               :description="__('For your security, please confirm your password to continue.')">
                <form wire:submit.prevent="confirmPassword">
                    <div>
                        <x-filament::input.wrapper>
                            <x-filament::input type="password" wire:model="confirmablePassword"
                                               required autocomplete="current-password"
                                               placeholder="{{ __('Password') }}" id="confirm-password-input" />
                        </x-filament::input.wrapper>
                    </div>
                    <x-slot name="footer">
                        <div class="flex justify-end gap-x-4">
                            <x-filament::button wire:click="stopConfirmingPassword">{{ __('Cancel') }}</x-filament::button>
                            <x-filament::button type="submit" color="primary">
                                <span wire:loading.remove wire:target="confirmPassword">{{ __('Confirm') }}</span>
                                <span wire:loading wire:target="confirmPassword">{{ __('Confirming...') }}</span>
                            </x-filament::button>
                        </div>
                    </x-slot>
                </form>
            </x-filament::modal>
        @endif
    </div>
</x-filament-panels::page>