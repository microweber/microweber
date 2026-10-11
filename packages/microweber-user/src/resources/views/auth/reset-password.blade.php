{{-- task-2026-05-17-f141ff / AI-794 — reset-password form, now wrapped in mw-auth-card chrome via user::layout (was a standalone bare DOCTYPE page that loaded no template CSS) --}}
@extends('user::layout')

@section('auth_form')
<form class="mw-auth-form" role="form" method="POST" action="{{ route('password.update') }}">
    <h2>{{ _e('Set a new password', true) }}</h2>

    @csrf

    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email }}">

    <div class="form-group">
        <label class="form-label">{{ _e('Email', true) }}</label>
        <p class="mw-auth-readonly-email" style="margin: 0; color: #6b7280; font-size: 14px;">{{ $email }}</p>
    </div>

    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <label class="form-label" for="password">{{ _e('New password', true) }}</label>
        <input type="password"
               class="form-control"
               id="password"
               name="password"
               autocomplete="new-password"
               placeholder="{{ _e('New password', true) }}"
               required/>
        @if ($errors->has('password'))
            <div class="text-danger"><strong>{{ $errors->first('password') }}</strong></div>
        @endif
    </div>

    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
        <label class="form-label" for="password_confirmation">{{ _e('Confirm new password', true) }}</label>
        <input type="password"
               class="form-control"
               id="password_confirmation"
               name="password_confirmation"
               autocomplete="new-password"
               placeholder="{{ _e('Confirm new password', true) }}"
               required/>
        @if ($errors->has('password_confirmation'))
            <div class="text-danger"><strong>{{ $errors->first('password_confirmation') }}</strong></div>
        @endif
    </div>

    <div>
        <button type="submit" class="btn btn-primary js-submit-change-password">{{ _e('Change password', true) }}</button>
    </div>
</form>
@endsection
