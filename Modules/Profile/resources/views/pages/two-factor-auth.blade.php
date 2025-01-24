<x-filament-panels::page>
    <div>
        @if(! auth()->user()->two_factor_secret)
            {{-- Enable 2FA Section --}}
            <div class="mt-4">
                    <h2 class="text-lg font-medium">
                        {{ __('Two Factor Authentication') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Add additional security to your account using two factor authentication.') }}
                    </p>

                    <div class="mt-5">
                        <x-filament::button
                            wire:click="enableTwoFactorAuthentication"
                            wire:loading.attr="disabled"
                            color="primary"
                        >
                            <span wire:loading.remove>{{ __('Enable') }}</span>
                            <span wire:loading>{{ __('Enabling...') }}</span>
                        </x-filament::button>
                    </div>
            </div>
        @else
            {{-- Disable 2FA Section --}}
            <div class="mt-4">
                    <h2 class="text-lg font-medium">
                        {{ __('Two Factor Authentication') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('You have enabled two factor authentication.') }}
                    </p>

                    @if ($showingQrCode)
                        <div class="mt-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Two factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application.') }}
                            </p>

                            <div class="mt-4">
                                {!! auth()->user()->twoFactorQrCodeSvg() !!}
                            </div>
                        </div>
                    @endif

                    @if ($showingRecoveryCodes)
                        <div class="mt-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.') }}
                            </p>

                            <div class="mt-4 p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
                                @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
                                    <div>{{ $code }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($showingConfirmation)
                        <div class="mt-4">
                            <x-filament-panels::form wire:submit.prevent="confirmTwoFactorAuthentication">
                                {{ $this->form }}

                                <div class="mt-4">
                                    <x-filament::button type="submit">
                                        {{ __('Confirm') }}
                                    </x-filament::button>
                                </div>
                            </x-filament-panels::form>
                        </div>
                    @endif

                    <div class="mt-5 space-y-2">
                        @if(! $showingRecoveryCodes)
                            <x-filament::button
                                wire:click="showRecoveryCodes"
                                wire:loading.attr="disabled"
                            >
                                {{ __('Show Recovery Codes') }}
                            </x-filament::button>
                        @endif

                        <x-filament::button
                            wire:click="regenerateRecoveryCodes"
                            wire:loading.attr="disabled"
                        >
                            {{ __('Regenerate Recovery Codes') }}
                        </x-filament::button>

                        <x-filament::button
                            wire:click="disableTwoFactorAuthentication"
                            wire:loading.attr="disabled"
                            color="danger"
                        >
                            {{ __('Disable') }}
                        </x-filament::button>
                    </div>
            </div>
        @endif

        @if ($this->confirmingPassword)
            <x-filament::modal
                id="confirm-password"
                wire:model="confirmingPassword"
                :heading="__('Confirm Password')"
                :description="__('For your security, please confirm your password to continue.')"
            >
                <form wire:submit.prevent="confirmPassword">
                    <div class="space-y-4">
                        <x-filament::input.wrapper>
                            <x-filament::input
                                type="password"
                                wire:model.defer="data.confirmablePassword"
                                required
                                autocomplete="current-password"
                                placeholder="{{ __('Password') }}"
                            />
                        </x-filament::input.wrapper>
                    </div>

                    <x-slot name="footer">
                        <div class="flex justify-end gap-x-4">
                            <x-filament::button wire:click="stopConfirmingPassword">
                                {{ __('Cancel') }}
                            </x-filament::button>

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
