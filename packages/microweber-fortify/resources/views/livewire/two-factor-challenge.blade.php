<div>
    <div class="mb-4">
        @if (!$recovery)
            <p class="text-sm text-gray-600">
                {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
            </p>
        @else
            <p class="text-sm text-gray-600">
                {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
            </p>
        @endif
    </div>

    <form method="POST" action="{{ route('two-factor.login') }}">
        @csrf

        @if (!$recovery)
            <div class="mb-4">
                <label for="code" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Code') }}</label>
                <input wire:model="code" id="code" type="text" inputmode="numeric" name="code" autofocus
                       autocomplete="one-time-code" maxlength="6"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" />
            </div>
        @else
            <div class="mb-4">
                <label for="recovery_code" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Recovery Code') }}</label>
                <input wire:model="recovery_code" id="recovery_code" type="text" name="recovery_code"
                       autocomplete="one-time-code"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" />
            </div>
        @endif

        <div class="flex items-center justify-between">
            <button wire:click.prevent="toggleRecovery" type="button" class="text-sm text-blue-600 hover:text-blue-800">
                {{ $recovery ? __('Use an authentication code') : __('Use a recovery code') }}
            </button>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</div>