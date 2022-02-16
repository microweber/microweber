@extends('user::layout')

@section('content')
<form class="form-horizontal" role="form" method="POST"
      action="{{ route('password.email') }}">
    <h2>Password Reset</h2>
    @csrf

    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">


        <label class="control-label">Enter your email</label>

        <input type="text" class="form-control" id="email" name="email"
               placeholder="Email"/>


        @if ($errors->has('email'))

            <div class="help-block text-danger"><strong>{{ $errors->first('email') }}</strong></div>

        @endif


        @if (get_option('captcha_disabled', 'users') !== 'y')


            @if ($errors->has('captcha'))

                <div class="help-block text-danger"><strong>{{ $errors->first('captcha') }}</strong>
                </div>

            @endif

            <module type="captcha"/>

        @endif


    </div>

    <div class="d-flex justify-content-between align-items-center">

        <a class="btn btn-link" class="reset_pass" href="{{route('login')}}">Login</a>

        <button type="submit" class="btn btn-primary submit">Send Password
            Reset Link
        </button>


    </div>

    <div class="clearfix"></div>


</form>
@endsection
