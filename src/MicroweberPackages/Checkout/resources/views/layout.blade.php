<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" <?php print lang_attributes(); ?>>
<head>
    <title></title>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap">
    <script>
        mw.require('icon_selector.js');
        mw.lib.require('bootstrap4');
        mw.lib.require('bootstrap_select');

        mw.iconLoader()
            .addIconSet('materialDesignIcons')
            .addIconSet('fontAwesome')
            .addIconSet('iconsMindLine')
            .addIconSet('iconsMindSolid')
            .addIconSet('mwIcons')
            .addIconSet('materialIcons');
    </script>

    <script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker();
        });
    </script>


    <?php print get_template_stylesheet(); ?>

    <link href="<?php print template_url(); ?>dist/main.min.css" rel="stylesheet"/>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

</head>
<body>

<section>
    <div class="container">
        <div class="row">

            <div class="col-12">
                <module type="logo" logo-name="header-logo" style="margin-top:50px;margin-bottom:50px;" />
            </div>

            @if (isset($errors))
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors as $fields)
                            @foreach ($fields as $field)
                            <li>{!! $field !!}</li>
                            @endforeach
                        @endforeach
                    </ul>
                </div>
            @endif

            @hasSection('content')
                @yield('content')
            @endif

        </div>
    </div>
</section>

</body>
</html>
