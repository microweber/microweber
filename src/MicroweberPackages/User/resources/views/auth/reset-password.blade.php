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


                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <form role="form" method="POST" action="{{ route('password.update') }}">
                    <h3>Reset Password</h3>

                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    Email: {{$email}}

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        @if ($errors->has('password'))
                            <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                        @endif
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password"/>
                    </div>

                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        @if ($errors->has('password_confirmation'))
                            <span class="help-block"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
                        @endif
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password"/>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-default submit">Reset Password</button>
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
