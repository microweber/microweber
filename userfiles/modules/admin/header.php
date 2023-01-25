<?php

    // file is moved to src/MicroweberPackages/Admin/resources/views/layouts/partials/header.blade.php


?><!DOCTYPE html>
<html <?php print lang_attributes(); ?>>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex">
    <script>
        mwAdmin = true;
        admin_url = '<?php print admin_url(); ?>';
    </script>

    <?php print \MicroweberPackages\Admin\Facades\AdminManager::headTags();    ?>

    <script type="text/javascript">
        mw.lib.require('jqueryui');
        mw.require("<?php print mw_includes_url(); ?>api/libs/jquery_slimscroll/jquery.slimscroll.min.js");
        mw.require("liveadmin.js");
        mw.require("<?php print mw_includes_url(); ?>css/wysiwyg.css");
        mw.require("<?php print mw_includes_url(); ?>css/components.css");
        mw.require("wysiwyg.js");
        mw.require("url.js");
        mw.require("options.js");
        mw.require("events.js");
        mw.require("admin.js");
        mw.require("editor_externals.js");
        mw.require("keys.js");
        mw.require("css_parser.js");
        mw.require("custom_fields.js");
        mw.require("session.js");
        mw.require("content.js");
        mw.require("upgrades.js");
        mw.require("tree.js");

        mw.lib.require('mwui');
        mw.lib.require('mwui_init');
        mw.lib.require('flag_icons', true);
        mw.require("<?php print mw_includes_url(); ?>css/admin.css", true);

        <?php /*  mw.require("<?php print mw_includes_url(); ?>css/helpinfo.css");
        mw.require("helpinfo.js");*/ ?>
        <?php if(_lang_is_rtl()){ ?>
        mw.require("<?php print mw_includes_url(); ?>css/rtl.css");
        <?php } ?>
    </script>
    <?php if (!isset($_REQUEST['no_toolbar'])): ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $('.mw-lazy-load-module').reload_module();

                if (self === top) {
                    window.onhashchange = function () {
                        mw.cookie.set('back_to_admin', location.href);
                    }
                    mw.cookie.set('back_to_admin', location.href);
                }

                mw.$("#mw-quick-content,#mw_edit_pages_content,#mw-admin-content").click(function () {
                    if (mw.helpinfo != undefined) {
                        mw.cookie.set('helpinfo', false, 4380);
                        $(".helpinfo_helper").fadeOut();
                    }
                });
            });
            // mw.require("<?php print mw_includes_url(); ?>css/ui.css");
            mw.require("fonts.js");


        </script>
    <?php endif; ?>
    <?php event_trigger('admin_head'); ?>

    <?php print \Livewire\Livewire::scripts();    ?>
    <?php print \Livewire\Livewire::styles();    ?>

    <!-- Alpine v3 -->
    <script defer src="<?php print mw_includes_url(); ?>api/libs/alpine/alpine.min.js"></script>
    <!-- Livewire sortable -->
    <script defer src="<?php print mw_includes_url(); ?>api/libs/livewire-sortable/livewire-sortable.js"></script>

    <?php if (config('app.debug') and is_logged()) { ?>

        <script type="text/javascript">
            window.__onerror_alert_shown = false;
            window.onerror = function (msg, url, lineNo, columnNo, error) {
                if ((typeof(msg) != 'undefined')  && !window.__onerror_alert_shown) {
                    var string = msg;
                    var message = [
                        'Message: ' + msg,
                        'URL: ' + url,
                        'Line: ' + lineNo,
                        'Column: ' + columnNo,
                        'Error object: ' + JSON.stringify(error)
                    ].join(' \n ');

                    console.log(message);
                    mw.notification.error(msg,10000);

                    return false;
                }
                window.__onerror_alert_shown = true;


            };

        </script>
    <?php } ?>




</head>



<body class="is_admin loading view-<?php print mw()->url_manager->param('view'); ?> action-<?php print mw()->url_manager->param('action'); ?>">

<div>
    <div>
        @livewire('livewire-ui-modal')
    </div>
</div>


<?php

// must be moved to ajax and to the notification class because its too slow to load
//$new_version_notifications = mw()->notifications_manager->get('rel_type=update_check&rel_id=updates');

$new_version_notifications = 0;
?>

<?php
$past_page = site_url();

$last_page_front = session_get('last_content_id');
if ($last_page_front == false) {
    if (isset($_COOKIE['last_page'])) {
        $last_page_front = $_COOKIE['last_page'];
    }
}

if ($last_page_front != false) {
    $cont_by_url = mw()->content_manager->get_by_id($last_page_front, true);
    if (isset($cont_by_url) and $cont_by_url == false) {
        $past_page = mw()->content_manager->get("order_by=updated_at desc&limit=1");
        if (isset($past_page[0])) {
            $past_page = mw()->content_manager->link($past_page[0]['id']);
        }
    } else {
        $past_page = mw()->content_manager->link($last_page_front);
    }
} else {
    $past_page = mw()->content_manager->get("order_by=updated_at desc&limit=1");
    if (isset($past_page[0])) {
        $past_page = mw()->content_manager->link($past_page[0]['id']);

    } else {
        $past_page = site_url();
    }
}
if(!$past_page){
 $past_page = site_url();
}

$shop_disabled = get_option('shop_disabled', 'website') == 'y';

if (!$shop_disabled) {
    if (!mw()->module_manager->is_installed('shop')) {
        $shop_disabled = true;
    }
}

if (!user_can_view_module(['module' => 'shop'])) {
    $shop_disabled = true;
}
?>

<script>
    $(document).ready(function () {
        $(".mw-admin-mobile-admin-sidebar-toggle").on('click', function () {
            $("#main-bar").toggleClass('mobile-active')
        })
        $("body").on('click', function (e) {
            if (!mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['mw-admin-mobile-admin-sidebar-toggle'])) {
                $("#main-bar").removeClass('mobile-active')
            }

        })
    })


    function mw_admin_add_order_popup(ord_id) {
        var contentHolder = document.createElement('div');
        contentHolder.style.padding = '25px';
        var dlg = mw.dialog({
            content: contentHolder,
            title: !!ord_id ? '<?php _ejs('Edit order'); ?>' : '<?php _ejs('Add order'); ?>',
            width: 900
        });
        mw.spinner({element: contentHolder, size: 32})
        mw.load_module('shop/orders/admin/add_order', contentHolder, function (){
            contentHolder.style.padding = '0';
            mw.spinner({element: contentHolder, size: 32}).remove()
            dlg.center()
        }, { order_id: ord_id });
    }

</script>


<?php
if (!is_logged()) {
    return;
}
?>


<?php
$order_notif_html = false;
$new_orders_count = false;



//$shop_disabled = get_option('shop_disabled', 'website') == 'y';
//
//if (!$shop_disabled) {
//    $new_orders_count = mw()->order_manager->get_count_of_new_orders();
//
//    if ($new_orders_count) {
//        $order_notif_html = '<span class="badge badge-success badge-pill mr-1 lh-0 d-inline-flex justify-content-center align-items-center" style="font-size: 11px; width: 20px; height:20px;">' . $new_orders_count . '</span>';
//    }
//}

$comments_notif_html = false;
//$new_comments_count = Auth::user()->unreadNotifications()->where('type', 'like', '%Comment%')->count();
//if ($new_comments_count) {
//    $comments_notif_html = '<span class="badge badge-success badge-pill mr-1 lh-0 d-inline-flex justify-content-center align-items-center" style="font-size: 11px; width: 20px; height:20px;">' . $new_comments_count . '</span>';
//}

$notif_html = '';
//$notif_count = Auth::user()->unreadNotifications()->count();
//if ($notif_count > 0) {
//    $notif_html = '<span class="badge badge-success badge-pill mr-1 lh-0 d-inline-flex justify-content-center align-items-center" style="font-size: 11px; width: 20px; height:20px;">' . $notif_count . '</span>';
//}
?>

<?php
$user_id = user_id();
$user = get_user_by_id($user_id);
?>




<div id="mw-admin-container">
    <header class="position-sticky sticky-top bg-white admin-navigation-colorscheme">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center py-1">

                <ul class="nav" id="admin-header-logo-nav">
                    <li id="admin-logo-nav-toggle">
                        <span class="js-toggle-mobile-nav">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </li>
                    <li id="admin-logo">
                        <?php
                            if (mw()->ui->admin_logo != false):
                                $logo = mw()->ui->admin_logo;
                            elseif (mw()->ui->admin_logo_login() != false):
                                $logo = mw()->ui->admin_logo_login();
                            else:
                                $logo = modules_url() . 'microweber/api/libs/mw-ui/assets/img/logo.svg';
                            endif;
                        ?>
                        <a href="<?php print admin_url('view:dashboard'); ?>">
                            <img alt="" src="<?php print $logo; ?>">
                        </a>
                    </li>

                    <?php
                    if (user_can_access('module.content.edit')):
                        ?>
                        <li class="mx-1 d-none d-md-block">
                            <button type="button" class="btn btn-success btn-rounded btn-sm-only-icon " data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-plus"></i> <span class="d-none d-md-block"><?php _e("Add New"); ?></span>
                            </button>
                            <div class="dropdown-menu ">
                                <?php $custom_view = url_param('view'); ?>
                                <?php $custom_action = url_param('action'); ?>
                                <?php event_trigger('content.create.menu'); ?>
                                <?php $create_content_menu = mw()->module_manager->ui('content.create.menu'); ?>
                                <?php if (!empty($create_content_menu)): ?>
                                    <?php foreach ($create_content_menu as $type => $item): ?>
                                        <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                                        <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                                        <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                                        <?php $type = (isset($item['content_type'])) ? ($item['content_type']) : false; ?>
                                        <?php $subtype = (isset($item['subtype'])) ? ($item['subtype']) : false; ?>
                                        <?php $base_url = (isset($item['base_url'])) ? ($item['base_url']) : false; ?>
                                        <?php
                                        $base_url = route('admin.content.create');
                                        if (Route::has('admin.'.$item['content_type'].'.create')) {
                                            $base_url = route('admin.' . $item['content_type'] . '.create');
                                        }
                                        ?>
                                        <a class="dropdown-item" href="<?php print $base_url; ?>"><span class="<?php print $class; ?>"></span> <?php print $title; ?></a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endif; ?>

                </ul>

                <div class="mw-lazy-load-module module" id="admin-header-notification" type="admin/header_notifications"></div>


                <?php event_trigger('mw.admin.header.toolbar'); ?>

                <ul class="nav">
                    <?php if (user_can_access('module.content.edit')): ?>
                        <li class="mx-1">
                            <a href="<?php print $past_page ?>?editmode=n" class="btn btn-outline-success btn-rounded btn-sm-only-icon go-live-edit-href-set go-live-edit-href-set-view">
                                <i class="mdi mdi-earth"></i><span class="d-none d-md-block ml-1"><?php _e("Website"); ?></span>
                            </a>
                        </li>
                        <li class="mx-1">
                            <a href="<?php print $past_page ?>?editmode=y" class="btn btn-primary btn-rounded btn-sm-only-icon go-live-edit-href-set">
                                <i class="mdi mdi-eye-outline"></i><span class="d-none d-md-block ml-1"><?php _e("Live Edit"); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php event_trigger('mw.admin.header.toolbar.ul'); ?>
                </ul>
            </div>
        </div>
    </header>
    <?php if (url_param('view')): ?>
        <script>
            $(document).ready(function () {
                if ($('body').find('.main-toolbar').length == 0) {
                    $('main').prepend('<div id="info-toolbar" type="admin/modules/info" history_back="true"></div>');
                    mw.reload_module('#info-toolbar');
                }
            })
        </script>
    <?php endif; ?>

    <div class="main container my-3">
        <aside>
            <?php $view = url_param('view'); ?>
            <?php $action = url_param('action'); ?>
            <?php $load_module = url_param('load_module'); ?>

            <?php
            if (empty($view)) {
                $view = Request::segment(2);
            }

            $routeName = Route::currentRouteName();
            if ($routeName == 'admin.post.create' || $routeName == 'admin.post.edit') {
                $action = 'posts';
                $view = 'content';
            }
            if ($routeName == 'admin.category.create' || $routeName == 'admin.category.edit') {
                $action = 'categories';
                $view = 'content';
            }
            if ($routeName == 'admin.page.create' || $routeName == 'admin.page.edit') {
                $action = 'pages';
                $view = 'content';
            }if ($routeName == 'admin.product.index' || $routeName == 'admin.product.create' || $routeName == 'admin.product.edit') {
                $action = 'products';
                $view = 'shop';
            }
            if ($routeName == 'admin.shop.category.index' || $routeName == 'admin.shop.category.create' || $routeName == 'admin.shop.category.edit') {
                $action = 'shop_category';
                $view = 'shop';
            }
            if ($routeName == 'admin.shop.dashboard') {
                $action = 'dashboard';
                $view = 'shop';
            }
            ?>

            <?php
            $website_class = '';
            if ($view == 'content' and $action == false) {
                $website_class = 'active';
            } else if ($view == 'content' and $action != false) {
                $website_class = 'active';
            }
            if ($routeName == 'admin.post.index') {
                $website_class = "active";
                $action = 'posts';
            }
            if ($routeName == 'admin.page.index') {
                $website_class = "active";
                $action = 'pages';
            }
            if ($routeName == 'admin.content.index') {
                $website_class = "active";
                $action = 'content';
            }

            $shop_class = '';
            if ($view == 'shop' and $action == false) {
                $shop_class = "active";
            } elseif ($view == 'shop' and $action != false) {
                $shop_class = "active";
            } elseif ($view == 'modules' and $load_module == 'shop__coupons') {
                $shop_class = "active";
            } elseif ($view == 'shop' AND $action == 'products' OR $action == 'orders' OR $action == 'clients' OR $action == 'options') {
                $shop_class = "active";
            } elseif ($view == 'invoices') {
                $shop_class = "active";
            } elseif ($view == 'customers') {
                $shop_class = "active";
            } elseif ($view == 'order') {
                $shop_class = "active";
            }
            if ($routeName == 'admin.shop.dashboard') {
                $shop_class = "active";
            }
            ?>



            <ul class="nav flex-column" id="mw-admin-main-navigation">
                <li class="nav-item">
                    <a href="<?php print admin_url(); ?>" class="nav-link <?php if (!$view): ?> active <?php endif; ?>">
                        <i class="mdi mdi-view-dashboard"></i> <?php _e("Dashboard"); ?>
                    </a>
                </li>

                <?php event_trigger('mw.admin.sidebar.li.first'); ?>

                <?php if (user_can_view_module(['module' => 'content'])): ?>
                    <li class="nav-item dropdown-no-js <?php echo $website_class; ?>">

                        <a href="<?php echo route('admin.content.index'); ?>" class="nav-link dropdown-toggle  <?php echo $website_class; ?>">
                            <i class="mdi mdi-earth"></i>
                            <span class="badge-holder"><?php _e("Website"); ?></span>
                        </a>

                        <div class="dropdown-menu">
                            <a href="<?php echo route('admin.page.index'); ?>" class="dropdown-item <?php if ($action == 'pages'): ?> active <?php endif; ?>">
                                <?php _e("Pages"); ?>
                                <span class="btn btn-success btn-rounded btn-icon btn-sm add-new" data-bs-toggle="tooltip" title="<?php _e("Add new page") ?>" data-href="<?php print route('admin.page.create'); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24"><path fill="white" d="M24 10h-10v-10h-4v10h-10v4h10v10h4v-10h10z"/></svg></span>
                            </a>

                            <a class="dropdown-item <?php if ($action == 'posts'): ?> active <?php endif; ?>" href="<?php echo route('admin.post.index'); ?>">
                                <?php _e("Posts"); ?>
                                <span class="btn btn-success btn-rounded btn-icon btn-sm add-new" data-bs-toggle="tooltip" title="<?php _e("Add new post") ?>" data-href="<?php print route('admin.post.create'); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24"><path fill="white" d="M24 10h-10v-10h-4v10h-10v4h10v10h4v-10h10z"/></svg></span>
                            </a>

                            <a class="dropdown-item <?php if ($action == 'categories'): ?> active <?php endif; ?>" href="<?php print admin_url(); ?>view:content/action:categories">
                                <?php _e("Categories"); ?>
                                <span class="btn btn-success btn-rounded btn-icon btn-sm add-new" data-href="<?php print route('admin.category.create'); ?>" data-bs-toggle="tooltip" title="<?php _e("Add new category") ?>"><svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24"><path fill="white" d="M24 10h-10v-10h-4v10h-10v4h10v10h4v-10h10z"/></svg></span>
                            </a>
                            <?php if (user_can_view_module(['module' => 'shop.products'])): ?>
                                <a href="<?php print route('admin.product.index'); ?>" class="dropdown-item <?php if ($action == 'products'): ?> active <?php endif; ?>">
                                    <?php _e("Products"); ?>
                                    <span data-href="<?php print route('admin.product.create'); ?>" class="btn btn-success btn-rounded btn-icon btn-sm add-new" data-bs-toggle="tooltip" title="<?php _e("Add new product") ?>"><i class="mdi mdi-plus"></i></span>
                                </a>
                            <?php endif; ?>
                            <a class="dropdown-item <?php if ($action == 'settings'): ?> active <?php endif; ?>" href="<?php print admin_url(); ?>view:content/action:settings">
                                <?php _e("Settings"); ?>
                            </a>
                        </div>
                    </li>
                <?php endif; ?>

                <?php if ($shop_disabled == false AND mw()->module_manager->is_installed('shop') == true): ?>
                    <li class="nav-item dropdown-no-js <?php echo $shop_class; ?>">
                        <a href="<?php print route('admin.product.index'); ?>" class="nav-link dropdown-toggle <?php echo $shop_class; ?>">
                            <i class="mdi mdi-shopping"></i>
                            <span class="badge-holder"><?php _e("Shop"); ?><?php if ($order_notif_html): ?><?php print $order_notif_html; ?><?php endif; ?></span>
                        </a>
                        <div class="dropdown-menu">

                           <!-- <a href="<?php /*print route('admin.shop.dashboard'); */?>" class="dropdown-item <?php /*if ($action == 'dashboard'): */?> active <?php /*endif; */?>">
                                <?php /*_e("Dashboard"); */?>
                            </a>-->

                         <!--   <?php /*if (user_can_view_module(['module' => 'shop.products'])): */?>
                                <a href="<?php /*print admin_url(); */?>view:shop/action:products" class="dropdown-item <?php /*if ($action == 'products'): */?> active <?php /*endif; */?>">
                                    <?php /*_e("Products"); */?>
                                    <span data-href="<?php /*print route('admin.product.create'); */?>" class="btn btn-success btn-rounded btn-icon btn-sm add-new" data-bs-toggle="tooltip" title="<?php /*_e("Add new product") */?>"><i class="mdi mdi-plus"></i></span>
                                </a>
                            --><?php
/*                            endif;
                            */?>

                            <?php if (user_can_view_module(['module' => 'shop.products'])): ?>
                                <a href="<?php print route('admin.product.index'); ?>" class="dropdown-item <?php if ($action == 'products'): ?> active <?php endif; ?>">
                                    <?php _e("Products"); ?>
                                    <span data-href="<?php print route('admin.product.create'); ?>" class="btn btn-success btn-rounded btn-icon btn-sm add-new" data-bs-toggle="tooltip" title="<?php _e("Add new product") ?>"><i class="mdi mdi-plus"></i></span>
                                </a>
                            <?php
                            endif;
                            ?>

                            <?php if (user_can_view_module(['module' => 'order.index'])): ?>
                                <a href="<?php echo route('admin.order.index'); ?>" class="dropdown-item <?php if($view == 'order'): ?>active<?php endif;?>">
                                    <?php _e("Orders"); ?>
                                    <?php if ($order_notif_html): ?><?php print $order_notif_html; ?><?php endif; ?>
                                    <span data-href="javascript:mw_admin_add_order_popup()" class="btn btn-success btn-rounded btn-icon btn-sm add-new"
                                          data-bs-toggle="tooltip" title="<?php _e("Add order") ?>"><i class="mdi mdi-plus"></i></span>
                                </a>
                            <?php endif; ?>


                            <?php if (user_can_view_module(['module' => 'shop.category'])): ?>

                                <a href="<?php print route('admin.shop.category.index'); ?>" class="dropdown-item <?php if ($action == 'shop_category'): ?> active <?php endif; ?>">
                                    <?php _e("Categories"); ?>
                                    <span data-href="<?php echo route('admin.shop.category.create'); ?>" class="btn btn-success btn-rounded btn-icon btn-sm add-new" data-bs-toggle="tooltip" title="<?php _e("Add new category") ?>"><i class="mdi mdi-plus"></i></span>
                                </a>

                            <?php
                            endif;
                            ?>

                            <?php if (user_can_view_module(['module' => 'shop.customers'])): ?>
                                <a href="<?php  print admin_url();  ?>customers" class="dropdown-item <?php if (url_segment(1) == 'customers'):  ?> active <?php endif;  ?>">
                                    <?php  _e("Clients");   ?>
                                </a>

                                <?php

                                /*
                                <a href="<?php echo route('admin.customers.index'); ?>" class="dropdown-item <?php if ($view == 'customers'): ?> active <?php endif; ?>">
                                    <?php _e("Clients"); ?>
                                </a>*/
                                ?>
                            <?php endif; ?>

                            <?php if (user_can_view_module(['module' => 'invoices']) && Route::has('admin.invoices.index') && mw()->module_manager->is_installed('invoice')): ?>
                                <a href="<?php echo route('admin.invoices.index'); ?>" class="dropdown-item <?php if ($view == 'invoices'): ?> active <?php endif; ?>">
                                    <?php _e("Invoices"); ?>
                                </a>
                            <?php endif; ?>

                            <a href="<?php print admin_url(); ?>view:shop/action:options" class="dropdown-item <?php if ($action == 'options'): ?> active <?php endif; ?>">
                                <?php _e("Settings"); ?>
                            </a>


                        </div>
                    </li>
                <?php endif; ?>

                <?php if (user_can_access('module.modules.index')): ?>
                    <li class="nav-item">
                        <?php
                        if (($view == 'modules' AND $load_module != 'users' AND $load_module != 'shop__coupons')) {
                            $modules_class = 'active';
                        } else {
                            $modules_class = '';
                        }
                        ?>
                        <a href="<?php print admin_url(); ?>view:modules" class="nav-link <?php echo $modules_class; ?>"><i class="mdi mdi-view-grid-plus"></i> <?php _e("Modules"); ?> </a>
                    </li>
                <?php endif; ?>

                <?php if (user_can_access('module.marketplace.index')): ?>
                    <?php if (mw()->ui->disable_marketplace != true): ?>

                        <?php
                        $composerClient = new \MicroweberPackages\Package\MicroweberComposerClient();
                        $countNewUpdates = $composerClient->countNewUpdatesCached();
                        ?>

                        <li class="nav-item">
                            <a href="<?php print admin_url(); ?>view:packages" class="nav-link <?php if ($view == 'packages'): ?>active<?php endif; ?>">
                                <i class="mdi mdi-fruit-cherries"></i> <?php _e("Marketplace"); ?>
                                <?php
                                if ($countNewUpdates > 0):
                                ?>
                                <span class="badge-holder">
                                    <span class="badge badge-success badge-pill mr-1 lh-0 d-inline-flex justify-content-center align-items-center" style="font-size: 11px; width: 20px; height:20px;"><?php echo $countNewUpdates; ?></span>
                                </span>
                                <?php
                                endif;
                                ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <li class="nav-item dropdown">
                    <a class="nav-link  <?php if (  ($view == 'settings')): ?> active <?php endif; ?>" href="<?php print admin_url(); ?>view:settings#option_group=all">
                        <i class="mdi mdi-cog"></i>
                        <span class="badge-holder"><?php _e("Settings"); ?></span>
                    </a>

                </li>





                <?php /*
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php if (!url_param('has_core_update') and ($view == 'settings')): ?> active <?php endif; ?>" href="<?php print admin_url(); ?>view:settings#option_group=website">
                        <i class="mdi mdi-cog"></i>
                        <span class="badge-holder"><?php _e("Settings"); ?></span>
                    </a>
                    <div class="dropdown-menu">

                        <a class="item-website dropdown-item" href="<?php print admin_url(); ?>view:settings#option_group=website">
                            <span class="mai-website"></span><strong><?php _e("Website"); ?></strong>
                        </a>

                        <a class="item-template dropdown-item" href="<?php print admin_url(); ?>view:settings#option_group=template">
                            <span class="mai-templates"></span><strong><?php _e("Template"); ?></strong>
                        </a>

                        <a class="item-users dropdown-item" href="<?php print admin_url(); ?>view:settings#option_group=users">
                            <span class="mai-login"></span><strong><?php _e("Login & Register"); ?></strong>
                        </a>

                        <a class="item-email dropdown-item" href="<?php print admin_url(); ?>view:settings#option_group=email">
                            <span class="mai-mail"></span><strong><?php _e("Email"); ?></strong>
                        </a>


                        <?php event_trigger('mw_admin_settings_menu'); ?>
                        <?php $settings_menu = mw()->module_manager->ui('admin.settings.menu'); ?>
                        <?php if (is_array($settings_menu) and !empty($settings_menu)): ?>
                            <?php foreach ($settings_menu as $item): ?>
                                <?php $module = (isset($item['module'])) ? module_name_encode($item['module']) : false; ?>
                                <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                                <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                                <?php if ($module != 'admin') { ?>
                                    <a onclick="mw.url.windowHashParam('option_group', '<?php print $module ?>');return false;" class="dropdown-item <?php print $class ?>" href="#option_group=<?php print $module ?>">
                                        <span class="<?php print isset($item['icon']) ? $item['icon'] : ''; ?>"></span>
                                        <strong><?php print $title ?></strong>
                                    </a>
                                <?php } ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <a onclick="mw.url.windowHashParam('option_group', 'advanced');return false;" class="dropdown-item item-advanced" href="#option_group=advanced">
                            <span class="mai-options"></span>
                            <stong><?php _e("Advanced"); ?></stong>
                        </a>

                        <a onclick="mw.url.windowHashParam('option_group', 'language');return false;" class="dropdown-item item-language" href="#option_group=language">
                            <span class="mai-languages"></span>
                            <strong><?php _e("Language"); ?></strong>
                        </a>
                    </div>
                </li>
                */ ?>

                <?php $load_module = url_param('load_module'); ?>
                <li <?php print 'class="nav-item dropdown ' . ($load_module == 'users' ? 'active' : '') . '"'; ?>>
                    <a class="nav-link <?php print ($load_module == 'users' OR $view == 'roles') ? 'active' : ''; ?>" href="<?php print admin_url('view:modules/load_module:users/action:profile'); ?>">
                        <i class="mdi mdi-account-multiple"></i> <?php _e("Users"); ?>
                    </a>

                    <?php if (mw()->ui->enable_service_links): ?>
                        <?php if (mw()->ui->custom_support_url): ?>
                            <!--                            <a class="dropdown-item" href="--><?php //print mw()->ui->custom_support_url ?><!--"><strong>--><?php //_e("Support"); ?><!--</strong></a>-->
                        <?php else: ?>
                            <!--                            <a class="dropdown-item" href="javascript:;" onmousedown="mw.contactForm();"><strong>--><?php //_e("Support"); ?><!--</strong></a>-->
                        <?php endif; ?>
                    <?php endif; ?>
                    <!--                    <a href="--><?php //print site_url(); ?><!--?editmode=y" class="go-live-edit-href-set dropdown-item">--><?php //_e("View Website"); ?><!--</a>-->
                </li>

                <li class="nav-item"><a href="<?php print api_url('logout'); ?>" class="nav-link"><i class="mdi mdi-power"></i> <?php _e("Log out"); ?></a></li>



              <?php event_trigger('mw.admin.sidebar.li.last'); ?>


                <div class="mt-5">
                    <?php include(__DIR__ . DS . 'lang_swich_footer.php'); ?>
                </div>
            </ul>


            <script>

                var handleConfirmBeforeLeave = function (c) {
                    if (mw.askusertostay) {
                        mw.confirm(mw.lang("You have unsaved changes. Do you want to save them first") + '?',
                            function () {

                                c.call(undefined, true)
                            },
                            function (){
                                mw.askusertostay = false;
                                c.call(undefined, false)
                            });
                    } else {
                        c.call(undefined, false)
                    }
                };
                $(document).ready(function () {



                    mw.$('.go-live-edit-href-set').each(function () {
                        var el = $(this);

                        if(self !== top){
                            el.attr('target', '_parent');
                        }


                        var href = el.attr('href');

                        if (href.indexOf("editmode") === -1) {
                            href = href + ((href.indexOf('?') === -1 ? '?' : '&') + 'editmode:y');

                            el.attr('href', href);

                        }
                    }).on('mousedown touchstart', function (event){
                        var el = this;

                        if(event.which === 1 || event.type === 'touchstart') {
                            handleConfirmBeforeLeave(function (shouldSave){
                                if(shouldSave) {
                                    var edit_cont_form =  $('#quickform-edit-content');
                                    var edit_cont_form_is_disabled_btn =  $('#js-admin-save-content-main-btn').attr('disabled');
                                    var edit_cont_title =  $('#content-title-field').val();
                                    if (edit_cont_form.length /*&& mw.edit_content && edit_cont_title && !edit_cont_form_is_disabled_btn*/) {
                                        event.stopPropagation();
                                        event.preventDefault();
                                        mw.askusertostay = false;
                                        mw.edit_content.saving = false;
                                        if($(this).hasClass('go-live-edit-href-set-view')){
                                            mw.edit_content.handle_form_submit('n');
                                        } else {
                                            mw.edit_content.handle_form_submit('y');
                                        }
                                    }
                                } else {
                                    mw.askusertostay = false;
                                    location.href = el.getAttribute('href');

                                }
                            });
                        }

                    });
                });
            </script>
        </aside>

<?php event_trigger('mw.admin.header.last'); ?>
