<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" <?php print lang_attributes(); ?>>
<head>
    <title></title>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap">
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

    </style>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>

<script type="text/javascript">
    mw.require("<?php print(mw_includes_url()); ?>css/ui.css");
    mw.lib.require("bootstrap4");
</script>

@hasSection('checkout_sidebar')

@else

<nav class="navbar-expand-lg checkout-v2-navbar navbar-light d-lg-none d-block">
    <div class="row">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon float-left ml-3"></span>
        <?php $cart_totals = mw()->cart_manager->totals(); ?>
        <?php if ($cart_totals): ?>
        <?php $print_total = cart_total(); ?>
        <h4 class="checkout-modal-total-label float-right mr-3"><?php _lang("Total"); ?>:<?php print currency_format($print_total); ?></h4>
        <?php endif; ?>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">

        @hasSection('checkout_sidebar')
            @yield('checkout_sidebar')
        @else
            <div class="checkout-v2-sidebar right-column col-12">
                <div class="col-lg-10 col-12 checkout-v2-right-column float-lg-left p-xl-5 p-md-3 p-3">
                    <div class="text-left">
                        <h6 class="m-t-100"><?php _e("Your order"); ?></h6>
                        <small class="text-muted d-block mb-2"> <?php _e("List with products"); ?></small>
                    </div>

                    <div class="mt-3">
                        <module type="shop/cart" template="checkout_v2_sidebar" data-checkout-link-enabled="n"/>
                    </div>
                </div>
            </div>
        @endif
    </div>
    </div>
</nav>

@endif

<div class="row">
    <div class="col-lg-6 col-12 order-lg-0 order-1">
        <div class="col-lg-8 col checkout-v2-left-column float-lg-right p-xl-5 p-md-3 p-3">
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
                        <img src="{{ $logo }}"/>
                    </div>
                @endif

                    @yield('logo-right-link')
                    @hasSection('logo-right-link')
                @endif
            </div>

            @hasSection('content')
                @yield('content')
            @else

                @yield('checkout_sidebar_content')
            @endif
        </div>
    </div>

    @hasSection('checkout_sidebar')
        @yield('checkout_sidebar')
    @else
        <div class="checkout-v2-sidebar right-column col-6 d-lg-block d-none">
            <div class="col-lg-10 col-12 checkout-v2-right-column float-lg-left p-xl-5 p-md-3 p-3">
                <div class="text-left">
                    <h6 class="checkout-v2-right-column-title font-weight-bold"><?php _e("Your order"); ?></h6>
                    <small class="text-muted d-block mb-2"> <?php _e("List with products"); ?></small>
                </div>

                <div class="mt-3">
                    <module type="shop/cart" template="checkout_v2_sidebar" data-checkout-link-enabled="n"/>
                </div>
            </div>
        </div>
    @endif
</div>
</body>
</html>
