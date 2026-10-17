
@extends('user::layout')

@section('auth_form')
    <h2>{{ __('Reset link expired') }}</h2>

    <p class="mw-auth-error-body">
        {!! _e('Your password reset link has expired or is invalid. Reset links are valid for 60 minutes — request a new one.', true) !!}
    </p>

    <div class="mw-auth-actions">
        <a class="btn btn-primary" href="{{ route('password.request') }}">
            {{ __('Request a new reset link') }}
        </a>
    </div>
@endsection
