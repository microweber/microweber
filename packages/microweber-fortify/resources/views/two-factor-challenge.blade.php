@extends(view()->exists('user::layout') ? 'user::layout' : 'microweber-fortify::layouts.app')

@section('content')
<div class="container mx-auto max-w-md mt-10 p-6">
    <div x-data="{ recovery: false }">
        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400" x-show="! recovery">
            {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
        </div>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400" x-cloak x-show="recovery">
            {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded">
                @foreach ($errors->all() as $error)
                    <p class="text-red-600 dark:text-red-400 text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('two-factor.login') }}">
            @csrf

            <div class="mt-4" x-show="! recovery">
                <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Code') }}</label>
                <input id="code" type="text" inputmode="numeric" name="code" autofocus x-ref="code"
                       autocomplete="one-time-code" maxlength="6"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
            </div>

            <div class="mt-4" x-cloak x-show="recovery">
                <label for="recovery_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Recovery Code') }}</label>
                <input id="recovery_code" type="text" name="recovery_code" x-ref="recovery_code"
                       autocomplete="one-time-code"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
            </div>

            <div class="flex items-center justify-between mt-4">
                <button type="button"
                        x-show="! recovery"
                        x-on:click="recovery = true; $nextTick(() => { $refs.recovery_code.focus() })"
                        class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                    {{ __('Use a recovery code') }}
                </button>

                <button type="button"
                        x-cloak x-show="recovery"
                        x-on:click="recovery = false; $nextTick(() => { $refs.code.focus() })"
                        class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                    {{ __('Use an authentication code') }}
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection