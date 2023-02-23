@extends('user::layout')

@section('content')
    
<div>

    <b>
        Please confirm access to your account by entering the authentication code provided by your authenticator application.

        {{--  <br />
         Please confirm access to your account by entering one of your emergency recovery codes.
          --}}
        <br />
        <br />
    </b>

    <form method="POST" action="{{ route('two-factor.login') }}">
        @csrf

        <div>
            <label>Code</label>
            <input class="form-control" type="text" name="code" autofocus autocomplete="one-time-code" />
        </div>

     {{--   <div class="mb-3">
            <label value="{{ __('Recovery Code') }}" />
            <input class="{{ $errors->has('recovery_code') ? 'is-invalid' : '' }}" type="text" name="recovery_code" autocomplete="one-time-code" />

        </div>
--}}
        <div class="d-flex mt-3">
          {{--  <button type="button" class="btn btn-outline-secondary">
                {{ __('Use a recovery code') }}
            </button>

            <button type="button" class="btn btn-outline-secondary">
                {{ __('Use an authentication code') }}
            </button>--}}

            <button class="btn btn-outline-primary" type="submit">
                {{ __('Log in') }}
            </button>

        </div>
    </form>
</div>
@endsection
