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

    <style>
        html, body, section, .row {
            height: 100%;
            min-height: 100%;
        }

        .checkout-v2-sidebar {
            background-color: #f5f5f5;
        }

        .checkout-v2-radio input[type=radio] {
            height: 20px;
        }

        i.shipping-icons-checkout-v2.mdi{
            font-size: 36px;
        }

        i.checkout-v2-finish-icon.mdi{
            font-size: 60px;
            color: #dedede;
            display: flex;
            justify-content: center;
            flex-direction: column;
        }

        .checkout-v2-logo {
            width: 35%;
        }

        .checkout-v2-remove-icon a:hover {
            text-decoration: none;
        }
        .checkout-v2-remove-icon a:active {
            text-decoration: none;
        }

        .checkout-v2-remove-icon:hover {
            color: black!important;
        }

        .checkout-v2-shipping-pickup.card-body {
            background: #f5f5f5;
        }

        .checkout-v2-navbar.navbar-light .navbar-toggler {
            height: 50px;
            width: 100%;
            background: #f5f5f5;
        }

        .mw-order-custom-fields ul {
            list-style-type: none;
            margin-bottom: 0.4rem;
        }

        .mw-order-custom-fields ul li {
            margin-bottom: 5px;
        }

        .checkout-v2-right-column-title {
            margin-top: 7rem;
        }


         .checkout-modal-products-wrapper .checkout-modal-product-list-item {
            font-size: 14px;
            border-bottom: 1px solid rgba(0,0,0,.125);
            padding: 10px 0;
            align-items: center;
            justify-content: center;
        }


        .checkout-modal-products-wrapper .products {
            max-height: 400px;
            overflow: auto;
            overflow-x: hidden;
        }

         .checkout-modal-products-wrapper .checkout-modal-product-list-item .ul {
            list-style-type: none;
            margin-bottom: 0.4rem;
        }

        @media screen and (max-width: 767px) {
             .checkout-modal-products-wrapper .checkout-modal-product-list-item img {
                max-width: 200px;
                margin: 0 auto;
                display: block
            }
        }

         .checkout-modal-products-wrapper .checkout-modal-product-list-item:hover {
            background: #fff
        }

         .checkout-modal-products-wrapper .checkout-modal-product-list-item .checkout-modal-product-list-item-action {
            padding-left: 0;
            padding-right: 0
        }

         .checkout-modal-products-wrapper .checkout-modal-product-list-item .checkout-modal-product-list-item-action a {
            color: #282627;
            opacity: 1;
            display: inline-block
        }

         .checkout-modal-products-wrapper .checkout-modal-product-list-item .checkout-modal-product-list-item-qty {
            padding-left: 0;
            padding-right: 0;
            text-align: center
        }

        .checkout-modal-products-wrapper .checkout-modal-product-list-item .checkout-modal-product-list-item-qty select {
            margin-top: 25px
        }

         .checkout-modal-products-wrapper .checkout-modal-product-list-item .checkout-modal-product-list-item-action .tooltip {
            margin-top: 5px
        }

         .checkout-modal-products-wrapper .checkout-modal-product-list-item .checkout-modal-product-list-item-action, .checkout-modal-products-wrapper .checkout-modal-product-list-item .checkout-modal-product-list-item-price, .checkout-modal-products-wrapper .checkout-modal-product-list-item .checkout-modal-product-list-item-title {
            /*padding-top: 35px;*/
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

         .checkout-modal-products-wrapper .checkout-modal-product-list-item .checkout-modal-product-list-item-action, .checkout-modal-products-wrapper .checkout-modal-product-list-item .checkout-modal-product-list-item-price {
            text-align: center
        }

         .checkout-modal-amount-holder .checkout-modal-promocode-holder, .checkout-modal-amount-holder .checkout-modal-total-holder {
            /*padding-top: 35px*/
        }


         .checkout-modal-amount-holder .checkout-modal-promocode-holder p, .checkout-modal-amount-holder .checkout-modal-total-holder p {
            margin-bottom: 20px
        }

         .checkout-modal-amount-holder .checkout-modal-promocode-holder .form-control {
            margin: 0
        }

         .checkout-modal-amount-holder .checkout-modal-total-holder, .js-step-content {
            font-size: 16px
        }


        .checkout-modal-total-price {
            font-weight: bold;
        }


        .checkout-modal-products-wrapper .table *{
            vertical-align:middle;
        }
        .checkout-modal-products-wrapper .table .td-title{
            max-width: 140px;
        }
        /* End of */


        /* End of .modal-content */


        @media screen and (max-width: 767px) {
            .checkout-modal-products-wrapper .checkout-modal-product-list-item .checkout-modal-product-list-item-title {
                text-align: center
            }
        }

        @media (min-width: 1450px) {
            .modal-dialog {
                width: 700px
            }
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
    mw.lib.require("bootstrap5");
</script>

@hasSection('checkout_sidebar')

@else

<nav class="navbar-expand-lg checkout-v2-navbar navbar-light d-lg-none d-block">
    <div class="row">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon float-left ml-3"></span>
            <?php $cart_totals = mw()->cart_manager->totals(); ?>
            <?php if ($cart_totals): ?>
            <?php $print_total = cart_total(); ?>
            <h4 class="checkout-modal-total-label float-right mr-3"><?php _lang("Total"); ?>:<?php print currency_format($print_total); ?></h4>
            <?php endif; ?>
        </button>

        <div class="collapse navbar-collapse px-0" id="navbarSupportedContent">

            @hasSection('checkout_sidebar')
                @yield('checkout_sidebar')
            @else
                <div class="checkout-v2-sidebar right-column col-12">
                    <div class="col-lg-10 col-12 checkout-v2-right-column float-lg-left p-xl-5 p-md-3 p-3">
                        <div class="text-start text-left">
                            <h6 class="m-t-100"><?php _e("Your order"); ?></h6>
                            <small class="text-muted d-block mb-2"> <?php _e("List with products"); ?></small>
                        </div>

                        <div class="mt-3">
                            <module type="shop/cart" class="no-settings" template="checkout_v2_sidebar" data-checkout-link-enabled="n"/>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</nav>

@endif

<div class="row">

    @include('checkout::steps_layout')

    @hasSection('content')
        @yield('content')
    @endif

    @hasSection('checkout_sidebar')
        @yield('checkout_sidebar')
    @else
        <div class="checkout-v2-sidebar right-column col-6 d-lg-block d-none">
            <div class="col-lg-10 col-12 checkout-v2-right-column float-lg-left p-xl-5 p-md-3 p-3">
                <div class="text-start text-left">
                    <h6 class="checkout-v2-right-column-title font-weight-bold"><?php _e("Your order"); ?></h6>
                    <small class="text-muted d-block mb-2"> <?php _e("List with products"); ?></small>
                </div>

                <div class="mt-3">
                    <module type="shop/cart" class="no-settings" template="checkout_v2_sidebar" data-checkout-link-enabled="n"/>
                </div>
            </div>
        </div>
    @endif
</div>
</body>
</html>
