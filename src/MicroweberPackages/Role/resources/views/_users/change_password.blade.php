<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ trans('admin.change_password') }} &nbsp;&nbsp; {{ option('web_name', config('app.name')) }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="{{ asset(mix('css/vendor.css', 'vendor/simple-admin')) }}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="javascript:void(0);"><b>@lang('admin.change_password')</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            @isset($tip)
            <p class="login-box-msg"><small>{{ $tip }}</small></p>
            @endisset

            <form action="{{ route('admin.users.change_password') }}" method="post">
                {{ csrf_field() }}
                <div class="input-group mb-3">
                    <input type="password" name="old_password" class="form-control @if($errors->has('old_password')) is-invalid @endif" placeholder="@lang('admin.old_password')">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    <span class="error invalid-feedback">{{$errors->first('old_password')}}</span>
                </div>
                <p class="login-box-msg"><small>{{ config('admin.auth.password.tip') }}</small></p>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control @if($errors->has('password')) is-invalid @endif" placeholder="@lang('admin.password')">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    <span class="error invalid-feedback">{{$errors->first('password')}}</span>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password_confirmation" class="form-control @if($errors->has('password_confirmation')) is-invalid @endif" placeholder="@lang('admin.password_confirmation')">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    <span class="error invalid-feedback">{{$errors->first('password_confirmation')}}</span>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">@lang('admin.confirm')</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
</html>
