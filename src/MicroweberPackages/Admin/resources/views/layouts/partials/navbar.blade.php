<aside class="navbar navbar-vertical navbar-expand-lg admin-dashboard-left-nav">
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



    <div class="container-fluid" id="sidebar-menu">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <h1 class="navbar-brand navbar-brand-autodark justify-content-start ms-3">
            <?php
            if (mw()->ui->admin_logo != false):
                $logo = mw()->ui->admin_logo;
            elseif (mw()->ui->admin_logo_login() != false):
                $logo = mw()->ui->admin_logo_login();
            else:
                $logo = modules_url() . 'microweber/api/libs/mw-ui/assets/img/logo.svg';
            endif;
            ?>
            <a class="w-75" href="<?php print admin_url('view:dashboard'); ?>">
                <img alt="" src="<?php print $logo; ?>">
            </a>

        </h1>

        <div class="navbar-nav flex-row d-lg-none">
            <div class="nav-item d-none d-lg-flex me-3">
                <div class="btn-list">
                    <a href="https://github.com/tabler/tabler" class="btn" target="_blank" rel="noreferrer">
                        <!-- Download SVG icon from http://tabler-icons.io/i/brand-github -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 19c-4.3 1.4 -4.3 -2.5 -6 -3m12 5v-3.5c0 -1 .1 -1.4 -.5 -2c2.8 -.3 5.5 -1.4 5.5 -6a4.6 4.6 0 0 0 -1.3 -3.2a4.2 4.2 0 0 0 -.1 -3.2s-1.1 -.3 -3.5 1.3a12.3 12.3 0 0 0 -6.2 0c-2.4 -1.6 -3.5 -1.3 -3.5 -1.3a4.2 4.2 0 0 0 -.1 3.2a4.6 4.6 0 0 0 -1.3 3.2c0 4.6 2.7 5.7 5.5 6c-.6 .6 -.6 1.2 -.5 2v3.5"></path></svg>
                        Source code
                    </a>
                    <a href="https://github.com/sponsors/codecalm" class="btn" target="_blank" rel="noreferrer">
                        <!-- Download SVG icon from http://tabler-icons.io/i/heart -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-pink" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"></path></svg>
                        Sponsor
                    </a>
                </div>
            </div>
            <div class="d-none d-lg-flex">
                <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Enable dark mode" data-bs-original-title="Enable dark mode">
                    <!-- Download SVG icon from http://tabler-icons.io/i/moon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z"></path></svg>
                </a>
                <a href="?theme=light" class="nav-link px-0 hide-theme-light" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Enable light mode" data-bs-original-title="Enable light mode">
                    <!-- Download SVG icon from http://tabler-icons.io/i/sun -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7"></path></svg>
                </a>
                <div class="nav-item dropdown d-none d-md-flex me-3">
                    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
                        <!-- Download SVG icon from http://tabler-icons.io/i/bell -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"></path><path d="M9 17v1a3 3 0 0 0 6 0v-1"></path></svg>
                        <span class="badge bg-red"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Last updates</h3>
                            </div>
                            <div class="list-group list-group-flush list-group-hoverable">
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto"><span class="status-dot status-dot-animated bg-red d-block"></span></div>
                                        <div class="col text-truncate">
                                            <a href="#" class="text-body d-block">Example 1</a>
                                            <div class="d-block text-muted text-truncate mt-n1">
                                                Change deprecated html tags to text decoration classes (#29604)
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#" class="list-group-item-actions">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto"><span class="status-dot d-block"></span></div>
                                        <div class="col text-truncate">
                                            <a href="#" class="text-body d-block">Example 2</a>
                                            <div class="d-block text-muted text-truncate mt-n1">
                                                justify-content:between ⇒ justify-content:space-between (#29734)
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#" class="list-group-item-actions show">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-yellow" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto"><span class="status-dot d-block"></span></div>
                                        <div class="col text-truncate">
                                            <a href="#" class="text-body d-block">Example 3</a>
                                            <div class="d-block text-muted text-truncate mt-n1">
                                                Update change-version.js (#29736)
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#" class="list-group-item-actions">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto"><span class="status-dot status-dot-animated bg-green d-block"></span></div>
                                        <div class="col text-truncate">
                                            <a href="#" class="text-body d-block">Example 4</a>
                                            <div class="d-block text-muted text-truncate mt-n1">
                                                Regenerate package-lock.json (#29730)
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#" class="list-group-item-actions">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                    <span class="avatar avatar-sm" style="background-image: url(./static/avatars/000m.jpg)"></span>
                    <div class="d-none d-xl-block ps-2">
                        <div>Paweł Kuna</div>
                        <div class="mt-1 small text-muted">UI Designer</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="#" class="dropdown-item">Status</a>
                    <a href="./profile.html" class="dropdown-item">Profile</a>
                    <a href="#" class="dropdown-item">Feedback</a>
                    <div class="dropdown-divider"></div>
                    <a href="./settings.html" class="dropdown-item">Settings</a>
                    <a href="./sign-in.html" class="dropdown-item">Logout</a>
                </div>
            </div>
        </div>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav pt-lg-3" id="mw-admin-main-navigation">
                <li class="nav-item">
                    <a href="<?php print admin_url(); ?>" class="nav-link py-3 fs-3 <?php if (!$view): ?> active <?php endif; ?>">
                        <i class="fs-1 me-2 mdi mdi-view-dashboard"></i> <?php _e("Dashboard"); ?>
                    </a>
                </li>

                <?php event_trigger('mw.admin.sidebar.li.first'); ?>

                <?php if (user_can_view_module(['module' => 'content'])): ?>
                <li class="nav-item dropdown-no-js <?php echo $website_class; ?>">

                    <a href="<?php echo route('admin.content.index'); ?>" class="nav-link py-3 fs-3 dropdown-toggle  <?php echo $website_class; ?>">
                        <i class="fs-1 me-2 mdi mdi-earth"></i>
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

                        <?php if (is_shop_module_enabled_for_user()): ?>
                        <a href="<?php print route('admin.product.index'); ?>" class="dropdown-item <?php if ($action == 'products'): ?> active <?php endif; ?>">
                            <?php _e("Products"); ?>
                            <span data-href="<?php print route('admin.product.create'); ?>" class="btn btn-success btn-rounded btn-icon btn-sm add-new" data-bs-toggle="tooltip" title="<?php _e("Add new product") ?>"><i class="fs-1 me-2 mdi mdi-plus"></i></span>
                        </a>
                        <?php endif; ?>

                        <a class="dropdown-item <?php if ($action == 'settings'): ?> active <?php endif; ?>" href="<?php print admin_url(); ?>view:content/action:settings">
                            <?php _e("Settings"); ?>
                        </a>
                    </div>
                </li>
                <?php endif; ?>

                <?php if (is_shop_module_enabled_for_user()): ?>
                <li class="nav-item dropdown-no-js <?php echo $shop_class; ?>">
                    <a href="<?php print route('admin.product.index'); ?>" class="nav-link py-3 fs-3 dropdown-toggle <?php echo $shop_class; ?>">
                        <i class="fs-1 me-2 mdi mdi-shopping"></i>
                        <span class="badge-holder"><?php _e("Shop"); ?><?php if ($order_notif_html): ?><?php print $order_notif_html; ?><?php endif; ?></span>
                    </a>
                    <div class="dropdown-menu">

                    <!-- <a href="<?php /*print route('admin.shop.dashboard'); */?>" class="dropdown-item <?php /*if ($action == 'dashboard'): */?> active <?php /*endif; */?>">
                                <?php /*_e("Dashboard"); */?>
                        </a>-->

                    <!--   <?php /*if (user_can_view_module(['module' => 'shop.products'])): */?>
                        <a href="<?php /*print admin_url(); */?>view:shop/action:products" class="dropdown-item <?php /*if ($action == 'products'): */?> active <?php /*endif; */?>">
                                    <?php /*_e("Products"); */?>
                        <span data-href="<?php /*print route('admin.product.create'); */?>" class="btn btn-success btn-rounded btn-icon btn-sm add-new" data-bs-toggle="tooltip" title="<?php /*_e("Add new product") */?>"><i class="fs-1 me-2 mdi mdi-plus"></i></span>
                                </a>
                            --><?php
                        /*                            endif;
                                                    */?>

                        <?php if (user_can_view_module(['module' => 'shop.products'])): ?>
                        <a href="<?php print route('admin.product.index'); ?>" class="dropdown-item <?php if ($action == 'products'): ?> active <?php endif; ?>">
                            <?php _e("Products"); ?>
                            <span data-href="<?php print route('admin.product.create'); ?>" class="btn btn-success btn-rounded btn-icon btn-sm add-new" data-bs-toggle="tooltip" title="<?php _e("Add new product") ?>"><i class="fs-1 me-2 mdi mdi-plus"></i></span>
                        </a>
                        <?php endif; ?>

                        <?php if (user_can_view_module(['module' => 'order.index'])): ?>
                        <a href="<?php echo route('admin.order.index'); ?>" class="dropdown-item <?php if($view == 'order'): ?>active<?php endif;?>">
                            <?php _e("Orders"); ?>
                            <?php if ($order_notif_html): ?><?php print $order_notif_html; ?><?php endif; ?>
                            <span data-href="javascript:mw_admin_add_order_popup()" class="btn btn-success btn-rounded btn-icon btn-sm add-new"
                                  data-bs-toggle="tooltip" title="<?php _e("Add order") ?>"><i class="fs-1 me-2 mdi mdi-plus"></i></span>
                        </a>
                        <?php endif; ?>


                        <?php if (user_can_view_module(['module' => 'shop.category'])): ?>

                        <a href="<?php print route('admin.shop.category.index'); ?>" class="dropdown-item <?php if ($action == 'shop_category'): ?> active <?php endif; ?>">
                            <?php _e("Categories"); ?>
                            <span data-href="<?php echo route('admin.shop.category.create'); ?>" class="btn btn-success btn-rounded btn-icon btn-sm add-new" data-bs-toggle="tooltip" title="<?php _e("Add new category") ?>"><i class="fs-1 me-2 mdi mdi-plus"></i></span>
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
                <li class="nav-item">
                    <?php
                    if (($view == 'modules' AND $load_module != 'users' AND $load_module != 'shop__coupons')) {
                        $modules_class = 'active';
                    } else {
                        $modules_class = '';
                    }
                    ?>
                    <a href="<?php print admin_url(); ?>view:modules" class="nav-link py-3 fs-3 <?php echo $modules_class; ?>"><i class="fs-1 me-2 mdi mdi-view-grid-plus"></i> <?php _e("Modules"); ?> </a>
                </li>
                <?php endif; ?>

                <?php if (user_can_access('module.marketplace.index')): ?>
                <?php if (mw()->ui->disable_marketplace != true): ?>

                <?php
                $composerClient = new \MicroweberPackages\Package\MicroweberComposerClient();
                $countNewUpdates = $composerClient->countNewUpdatesCached();
                ?>

                <li class="nav-item">
                    <a href="<?php print admin_url(); ?>view:packages" class="nav-link py-3 fs-3 <?php if ($view == 'packages'): ?>active<?php endif; ?>">
                        <i class="fs-1 me-2 mdi mdi-fruit-cherries"></i> <?php _e("Marketplace"); ?>
                        <?php
                        if ($countNewUpdates > 0):
                        ?>
                        <span class="badge-holder ms-auto">
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
                    <a class="nav-link py-3 fs-3  <?php if (  ($view == 'settings')): ?> active <?php endif; ?>" href="<?php print admin_url(); ?>settings">
                        <i class="fs-1 me-2 mdi mdi-cog"></i>
                        <span class="badge-holder"><?php _e("Settings"); ?></span>
                    </a>

                </li>





                <?php /*
                <li class="nav-item dropdown">
                    <a class="nav-link py-3 fs-3 dropdown-toggle <?php if (!url_param('has_core_update') and ($view == 'settings')): ?> active <?php endif; ?>" href="<?php print admin_url(); ?>view:settings#option_group=website">
                        <i class="fs-1 me-2 mdi mdi-cog"></i>
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
                    <a class="nav-link py-3 fs-3 <?php print ($load_module == 'users' OR $view == 'roles') ? 'active' : ''; ?>" href="<?php print admin_url('view:modules/load_module:users/action:profile'); ?>">
                        <i class="fs-1 me-2 mdi mdi-account-multiple"></i> <?php _e("Users"); ?>
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

                <li class="nav-item"><a href="<?php print api_url('logout'); ?>" class="nav-link py-3 fs-3"><i class="fs-1 me-2 mdi mdi-power"></i> <?php _e("Log out"); ?></a></li>



                <?php event_trigger('mw.admin.sidebar.li.last'); ?>


                <div class="mt-5">
                    <?php include(modules_path(). DS . 'admin/lang_swich_footer.php'); ?>
                </div>
            </ul>
        </div>

    </div>



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
