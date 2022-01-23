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


<div class="container">
    <div class="row clearfix mt-20">
        <div class="col-md-6 col-md-offset-3 column">
            <form role="form" action="{{route('verification.send',[
            'id'=>$id,
            'hash'=>$hash,
        ]    )}}" method="post">


                @csrf
                <h1>Resend email verification</h1>


                <button type="submit" class="btn btn-primary">Submit</button>
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
