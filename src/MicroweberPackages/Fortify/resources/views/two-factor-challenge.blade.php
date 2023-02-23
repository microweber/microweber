


      {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}

        {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}



      @php
      dump(Session::get('status'));
      @endphp

    <form method="POST" action="{{ route('two-factor.login') }}">
        @csrf

        <div class="mb-3">
            <label value="{{ __('Code') }}" />
            <input class="{{ $errors->has('code') ? 'is-invalid' : '' }}"
                   type="text" inputmode="numeric" name="code" autofocus autocomplete="one-time-code" />

        </div>

     {{--   <div class="mb-3">
            <label value="{{ __('Recovery Code') }}" />
            <input class="{{ $errors->has('recovery_code') ? 'is-invalid' : '' }}" type="text" name="recovery_code" autocomplete="one-time-code" />

        </div>
--}}
        <div class="d-flex justify-content-end mt-3">
          {{--  <button type="button" class="btn btn-outline-secondary">
                {{ __('Use a recovery code') }}
            </button>

            <button type="button" class="btn btn-outline-secondary">
                {{ __('Use an authentication code') }}
            </button>--}}

            <button type="submit">
                {{ __('Log in') }}
            </button>

        </div>
    </form>
