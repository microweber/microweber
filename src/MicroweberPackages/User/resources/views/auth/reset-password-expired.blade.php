{{--
    task-2026-05-17-06892a / AI-794b — CHANGE 2 absorbed.
    Chrome-wrapped error page for the /reset-password expired/invalid-
    token path. Pre-CHANGE the controller's 3 `abort(response("Password
    reset link is expired", 401))` calls returned bare text on a blank
    page — losing the AI-794 auth chrome at the exact moment a user is
    actively trying to recover access. Same propagation-without-renderer-
    update family as AI-735→AI-793 (admin-404).

    Routes through user::layout so the AI-794 chrome (active template
    master extension + .mw-auth-card + brand logo + footer + AI-794a
    brand-blue CTA) renders consistently with the forgot-password +
    reset-password form surfaces.

    User-facing messaging per designer spec:
      Heading: "Reset link expired"
      Body:    "Your password reset link has expired or is invalid.
                Reset links are valid for 60 minutes — request a new one."
      CTA:     "Request a new reset link" → route('password.request')
--}}
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
