<div class="two-factor-setup">
    @if ($successMessage)
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-md">
            <p class="text-green-700 text-sm">{{ $successMessage }}</p>
        </div>
    @endif

    @if ($errorMessage)
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-md">
            <p class="text-red-700 text-sm">{{ $errorMessage }}</p>
        </div>
    @endif

    <h3 class="text-lg font-medium mb-2">{{ __('Two Factor Authentication') }}</h3>
    <p class="text-sm text-gray-600 mb-4">
        {{ __('Add additional security to your account using two factor authentication.') }}
    </p>

    @if ($this->enabled)
        <div class="p-4 bg-green-50 border border-green-200 rounded-md mb-4">
            <p class="text-green-700 font-medium">✓ {{ __('Two factor authentication is enabled.') }}</p>
        </div>

        @if ($showingRecoveryCodes)
            <div class="mt-4">
                <p class="text-sm text-gray-600 font-medium mb-2">
                    {{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.') }}
                </p>
                <div class="p-4 bg-gray-100 rounded-md font-mono text-sm" id="recovery-codes-list">
                    @foreach ($this->recoveryCodes as $code)
                        <div class="py-1">{{ $code }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-4 flex flex-wrap gap-2">
            @if (!$showingRecoveryCodes)
                <button wire:click="showRecoveryCodes" type="button"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 text-sm">
                    {{ __('Show Recovery Codes') }}
                </button>
            @endif
            <button wire:click="regenerateRecoveryCodes" type="button"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 text-sm">
                {{ __('Regenerate Recovery Codes') }}
            </button>
            <button wire:click="disableTwoFactorAuthentication" type="button"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm">
                {{ __('Disable') }}
            </button>
        </div>

    @elseif ($this->pending || $showingQrCode)
        {{-- QR Code display --}}
        @if ($showingQrCode)
            <div class="mt-4">
                <p class="text-sm text-gray-600 font-medium mb-2">
                    {{ __('Scan the following QR code using your phone\'s authenticator application (Google Authenticator, Authy, etc).') }}
                </p>
                <div class="mt-3 p-4 bg-white inline-block rounded-md shadow" id="two-factor-qr-code">
                    {!! auth()->user()->twoFactorQrCodeSvg() !!}
                </div>

                @if (auth()->user()->getDecryptedTwoFactorSecret())
                    <div class="mt-3">
                        <p class="text-xs text-gray-500">{{ __('Or enter this secret key manually:') }}</p>
                        <code class="text-sm bg-gray-100 px-2 py-1 rounded" id="two-factor-secret-key">{{ auth()->user()->getDecryptedTwoFactorSecret() }}</code>
                    </div>
                @endif
            </div>
        @endif

        {{-- Confirmation form --}}
        @if ($showingConfirmation)
            <div class="mt-4">
                <label for="two-factor-code" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('Enter the 6-digit code from your authenticator app') }}
                </label>
                <div class="flex gap-2">
                    <input wire:model="code" type="text" id="two-factor-code" inputmode="numeric"
                           maxlength="6" pattern="[0-9]{6}" placeholder="000000"
                           class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-40" />
                    <button wire:click="confirmTwoFactorCode" type="button" id="confirm-2fa-code-btn"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        {{ __('Confirm') }}
                    </button>
                </div>
            </div>
        @endif

        @if ($showingRecoveryCodes)
            <div class="mt-4">
                <p class="text-sm text-gray-600 font-medium mb-2">
                    {{ __('Store these recovery codes in a secure password manager.') }}
                </p>
                <div class="p-4 bg-gray-100 rounded-md font-mono text-sm" id="recovery-codes-list">
                    @foreach ($this->recoveryCodes as $code)
                        <div class="py-1">{{ $code }}</div>
                    @endforeach
                </div>
            </div>
        @endif

    @else
        <button wire:click="enableTwoFactorAuthentication" type="button"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" id="enable-2fa-btn">
            <span wire:loading.remove wire:target="enableTwoFactorAuthentication">{{ __('Enable Two-Factor Authentication') }}</span>
            <span wire:loading wire:target="enableTwoFactorAuthentication">{{ __('Enabling...') }}</span>
        </button>
    @endif

    {{-- Password confirmation modal --}}
    @if ($confirmingPassword)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50" id="password-confirm-modal">
            <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
                <h3 class="text-lg font-medium mb-4">{{ __('Confirm Password') }}</h3>
                <p class="text-sm text-gray-600 mb-4">{{ __('For your security, please confirm your password to continue.') }}</p>

                <div class="mb-4">
                    <input wire:model="confirmablePassword" type="password" id="confirm-password-input"
                           placeholder="{{ __('Password') }}" autofocus
                           wire:keydown.enter="confirmPassword"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <div class="flex justify-end gap-2">
                    <button wire:click="cancelConfirmPassword" type="button"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                        {{ __('Cancel') }}
                    </button>
                    <button wire:click="confirmPassword" type="button"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        {{ __('Confirm') }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>