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

    <style>
        html, body, section, .row {
            height: 100%;
            min-height: 100%;
        }

        .checkout-v2-sidebar {
            background-color: #f5f5f5;
        }

        input[type=radio] {
            width: 20px;
            height: 20px;
        }

        i.shipping-icons-checkout-v2.mdi{
            font-size: 36px;
        }

        .checkout-v2-logo {
            width: 35%;
        }

        checkout-v2-remove-icon .a:hover {
            text-decoration: none;
        }
        a:active {
            text-decoration: none;
        }

        .checkout-v2-remove-icon:hover {
            color: black!important;
        }


    </style>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

</head>
<body>

<section>
    <div class="row">
        <div class="col-6">
            <div class="col-7 checkout-v2-left-column float-right p-5">
                <div class="d-flex">
                    @php
                        $logo = get_option('logo', 'website');
                    @endphp
                    @if(empty($logo))
                        <h1 class="text-uppercase">
                            <a href="{{ site_url() }}">{{get_option('website_title', 'website')}}</a>
                        </h1>
                    @else
                       <div class="checkout-v2-logo">
                           <img src="{{ $logo }}" />
                       </div>
                    @endif

                    @hasSection('logo-right-link')
                        @yield('logo-right-link')
                    @endif
                </div>

                @hasSection('content')
                    @yield('content')
                @endif
            </div>
        </div>

        <div class="checkout-v2-sidebar col-6">
            <div class="col-9 checkout-v2-right-column float-left p-5">

                @hasSection('checkout_sidebar')
                    @yield('checkout_sidebar')
                @else
                <div class="text-left">
                    <h6 class="m-t-80"><?php _e("Your order"); ?></h6>
                    <small class="text-muted d-block mb-2"> <?php _e("List with products"); ?></small>
                </div>

                <div class="pt-3">
                    <module type="shop/cart" template="checkout_v2_sidebar" data-checkout-link-enabled="n" />
                </div>
             @endif

            </div>
        </div>
    </div>

</section>

</body>
</html>


