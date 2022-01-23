<!DOCTYPE html>
<html <?php print lang_attributes(); ?>>
<head>
    <title><?php _e('Resend'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex">
    <?php get_favicon_tag(); ?>

    <link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>default.css"/>
    <link type="text/css" rel="stylesheet" media="all"
          href="<?php print(mw()->template->get_admin_system_ui_css_url()); ?>"/>

    <script src="<?php print(mw()->template->get_apijs_combined_url()); ?>"></script>

</head>

<body>

<main class="w-100 h-100vh ">
    <div class="row my-5 d-flex align-items-center ">
        <div class="col-12 col-sm-9 col-md-7 col-lg-5 col-xl-4 mx-auto">


            <div class="card">
                <div class="card-body py-4">


                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

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

                </div>
            </div>

        </div>
    </div>


</main>
</body>
</html>
