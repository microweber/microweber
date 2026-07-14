<x-filament-panels::page>
    <div class="space-y-6">
        @if(! auth()->user()->two_factor_secret)
            {{-- Enable 2FA --}}
            <x-filament::section>
                <x-slot name="heading">{{ __('Two Factor Authentication') }}</x-slot>
                <x-slot name="description">{{ __('Add additional security to your account using two factor authentication.') }}</x-slot>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s Google Authenticator application.') }}
                </p>

                <div class="mt-4">
                    <x-filament::button wire:click="enableTwoFactorAuthentication" wire:loading.attr="disabled" color="primary" id="enable-2fa-btn">
                        <span wire:loading.remove wire:target="enableTwoFactorAuthentication">{{ __('Enable') }}</span>
                        <span wire:loading wire:target="enableTwoFactorAuthentication">{{ __('Enabling...') }}</span>
                    </x-filament::button>
                </div>
            </x-filament::section>
        @else
            {{-- 2FA is enabled or pending confirmation --}}
            <x-filament::section>
                <x-slot name="heading">{{ __('Two Factor Authentication') }}</x-slot>

                @if(auth()->user()->two_factor_confirmed_at)
                    <div class="p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg">
                        <p class="text-green-700 dark:text-green-300 font-medium">✓ {{ __('Two factor authentication is enabled.') }}</p>
                    </div>
                @else
                    <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg">
                        <p class="text-yellow-700 dark:text-yellow-300 text-sm">
                            {{ __('Two factor authentication is enabled but not yet confirmed. Please scan the QR code and enter a code to confirm.') }}
                        </p>
                    </div>
                @endif

                @if ($showingQrCode)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Scan the following QR code using your phone\'s authenticator application (Google Authenticator, Authy, etc).') }}
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
                                <x-filament::button type="submit" id="confirm-2fa-code-btn">
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
            </x-filament::section>
        @endif

        {{-- Password confirmation overlay --}}
        @if ($this->confirmingPassword)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 dark:bg-gray-900/75" id="password-confirm-modal">
                <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl dark:bg-gray-800">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Confirm Password') }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('For your security, please confirm your password to continue.') }}</p>

                    <form wire:submit.prevent="confirmPassword" class="mt-4">
                        <div>
                            <input type="password" wire:model="confirmablePassword"
                                   required autocomplete="current-password"
                                   placeholder="{{ __('Password') }}" id="confirm-password-input"
                                   class="fi-input block w-full rounded-lg border-gray-300 shadow-sm outline-none transition duration-75 focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm" />
                        </div>
                        <div class="mt-4 flex justify-end gap-x-3">
                            <x-filament::button wire:click="stopConfirmingPassword" color="gray">{{ __('Cancel') }}</x-filament::button>
                            <x-filament::button type="submit" color="primary">
                                <span wire:loading.remove wire:target="confirmPassword">{{ __('Confirm') }}</span>
                                <span wire:loading wire:target="confirmPassword">{{ __('Confirming...') }}</span>
                            </x-filament::button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>