<!DOCTYPE html>
<html <?php print lang_attributes(); ?>>
<head>
    <title><?php echo _e('Resend'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex">

    <link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>default.css"/>
    <link type="text/css" rel="stylesheet" media="all"
          href="<?php print(mw()->template->get_admin_system_ui_css_url()); ?> "/>

    <script type="text/javascript" src="<?php print(mw()->template->get_apijs_combined_url()); ?>"></script>

</head>

<body>


<div class="container">
    <div class="row clearfix mt-20">
        <div class="col-md-6 col-md-offset-3 column">


            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">
                <h1>Password Reset</h1>
                @csrf

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    @if ($errors->has('email'))
                        <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                    @endif
                    <input type="text" class="form-control" id="email" name="email" placeholder="Email"/>
                </div>

                <div>
                    <button type="submit" class="btn btn-default submit">Send Password Reset Link</button>
                    <a class="reset_pass" href="{{route('user.login')}}">Login</a>
                </div>

                <div class="clearfix"></div>

                <div class="separator">

                    <div class="clearfix"></div>
                    <br />


                </div>
            </form>


        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12 column">
        </div>
    </div>
</div>


</body>
</html>
