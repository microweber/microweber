{{-- task-2026-05-17-f141ff / AI-794 — forgot-password form, now wrapped in mw-auth-card chrome via user::layout --}}
@extends('user::layout')

@section('auth_form')
<form class="mw-auth-form" role="form" method="POST" action="{{ route('password.email') }}">
    <h2>{{ _e('Reset your password', true) }}</h2>

    @csrf

    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label class="form-label" for="email">{{ _e('Email', true) }}</label>
        <input type="email"
               class="form-control"
               id="email"
               name="email"
               value="{{ old('email') }}"
               autocomplete="username"
               inputmode="email"
               placeholder="{{ _e('your@email.com', true) }}"
               autofocus
               required/>
        @if ($errors->has('email'))
            <div class="text-danger"><strong>{{ $errors->first('email') }}</strong></div>
        @endif
    </div>

    @if (get_option('captcha_disabled', 'users') !== 'y')
        <div class="form-group">
            @if ($errors->has('captcha'))
                <div class="text-danger"><strong>{{ $errors->first('captcha') }}</strong></div>
            @endif
            <module type="captcha"/>
        </div>
    @endif

    <div class="mw-auth-actions">
        <a class="btn btn-link" href="{{ route('login') }}">{{ _e('Back to login', true) }}</a>
        <button type="submit" class="btn btn-primary submit">{{ _e('Send reset link', true) }}</button>
    </div>
</form>
@endsection
