@extends(view()->exists('user::layout') ? 'user::layout' : 'microweber-fortify::layouts.app')

@section('content')
<div class="container mx-auto max-w-lg mt-10 p-6">
    <h2 class="text-xl font-bold mb-4">{{ __('Set Up Two-Factor Authentication') }}</h2>
    <p class="text-sm text-gray-600 mb-6">
        {{ __('Your organization requires two-factor authentication. Please set it up to continue.') }}
    </p>

    @livewire('two-factor-setup')
</div>
@endsection