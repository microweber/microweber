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

                    @hasSection('content')
                        @yield('content')
                    @endif


                </div>
            </div>

        </div>
    </div>


</main>
</body>
</html>
