<aside class="navbar navbar-vertical navbar-expand-lg admin-dashboard-left-nav p-3">
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

        <h1 class="navbar-brand navbar-brand-autodark justify-content-start">
            <?php
            if (mw()->ui->admin_logo != false):
                $logo = mw()->ui->admin_logo;
            elseif (mw()->ui->admin_logo_login() != false):
                $logo = mw()->ui->admin_logo_login();
            else:
                $logo = modules_url() . 'microweber/api/libs/mw-ui/assets/img/logo.svg';
            endif;
            ?>
            <a class="w-100 mb-md-3" href="<?php print admin_url('view:dashboard'); ?>">
                <img alt="" src="<?php print $logo; ?>">
            </a>

        </h1>


        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3" id="mw-admin-main-navigation">
                <li class="nav-item">
                    <a href="<?php print admin_url(); ?>" class="nav-link fs-3 <?php if (!$view): ?> active <?php endif; ?>">
                        <svg style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M520 456V216h320v240H520ZM120 616V216h320v400H120Zm400 320V536h320v400H520Zm-400 0V696h320v240H120Zm80-400h160V296H200v240Zm400 320h160V616H600v240Zm0-480h160v-80H600v80ZM200 856h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360 776Z"/></svg>
                       <span>
                            <?php _e("Dashboard"); ?>
                       </span>
                    </a>
                </li>

                <?php event_trigger('mw.admin.sidebar.li.first'); ?>

                <?php if (user_can_view_module(['module' => 'content'])): ?>
                <li class="nav-item dropdown <?php echo $website_class; ?>">
                    <a href="<?php echo route('admin.content.index'); ?>" class="nav-link dropdown-toggle py-3 fs-3" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="true">
                        <svg style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M480 976q-83 0-156-31.5T197 859q-54-54-85.5-127T80 576q0-83 31.5-156T197 293q54-54 127-85.5T480 176q83 0 156 31.5T763 293q54 54 85.5 127T880 576q0 83-31.5 156T763 859q-54 54-127 85.5T480 976Zm-40-82v-78q-33 0-56.5-23.5T360 736v-40L168 504q-3 18-5.5 36t-2.5 36q0 121 79.5 212T440 894Zm276-102q20-22 36-47.5t26.5-53q10.5-27.5 16-56.5t5.5-59q0-98-54.5-179T600 280v16q0 33-23.5 56.5T520 376h-80v80q0 17-11.5 28.5T400 496h-80v80h240q17 0 28.5 11.5T600 616v120h40q26 0 47 15.5t29 40.5Z"/></svg>
                        <span class="badge-holder"><?php _e("Website"); ?></span>
                    </a>

                    <div class="dropdown-menu" data-bs-popper="static">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a href="<?php echo route('admin.page.index'); ?>" class="dropdown-item justify-content-between <?php if ($action == 'pages'): ?> active <?php endif; ?>">
                                        <?php _e("Pages"); ?>
                                    <span class="btn btn-success btn-rounded btn-icon btn-sm add-new" data-bs-toggle="tooltip" title="<?php _e("Add new page") ?>" data-href="<?php print route('admin.page.create'); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24"><path fill="white" d="M24 10h-10v-10h-4v10h-10v4h10v10h4v-10h10z"/></svg></span>
                                </a>

                                <a class="dropdown-item justify-content-between <?php if ($action == 'posts'): ?> active <?php endif; ?>" href="<?php echo route('admin.post.index'); ?>">
                                        <?php _e("Posts"); ?>
                                    <span class="btn btn-success btn-rounded btn-icon btn-sm add-new" data-bs-toggle="tooltip" title="<?php _e("Add new post") ?>" data-href="<?php print route('admin.post.create'); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24"><path fill="white" d="M24 10h-10v-10h-4v10h-10v4h10v10h4v-10h10z"/></svg></span>
                                </a>

                                <a class="dropdown-item justify-content-between <?php if ($action == 'categories'): ?> active <?php endif; ?>" href="<?php print admin_url(); ?>view:content/action:categories">
                                        <?php _e("Categories"); ?>
                                    <span class="btn btn-success btn-rounded btn-icon btn-sm add-new" data-href="<?php print route('admin.category.create'); ?>" data-bs-toggle="tooltip" title="<?php _e("Add new category") ?>"><svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24"><path fill="white" d="M24 10h-10v-10h-4v10h-10v4h10v10h4v-10h10z"/></svg></span>
                                </a>
                                    <?php if (is_shop_module_enabled_for_user()): ?>
                                <a href="<?php print route('admin.product.index'); ?>" class="dropdown-item justify-content-between <?php if ($action == 'products'): ?> active <?php endif; ?>">
                                        <?php _e("Products"); ?>
                                    <span data-href="<?php print route('admin.product.create'); ?>" class="btn btn-success btn-rounded btn-icon btn-sm add-new" data-bs-toggle="tooltip" title="<?php _e("Add new product") ?>"><i class="mdi mdi-plus"></i></span>
                                </a>
                                <?php endif; ?>
                                <a class="dropdown-item justify-content-between <?php if ($action == 'settings'): ?> active <?php endif; ?>" href="<?php print admin_url(); ?>view:content/action:settings">
                                        <?php _e("Settings"); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                <?php endif; ?>

                <?php if (is_shop_module_enabled_for_user()): ?>
                <li class="nav-item dropdown <?php echo $shop_class; ?>">
                    <a href="<?php print route('admin.product.index'); ?>" class="nav-link fs-3 dropdown-toggle <?php echo $shop_class; ?>" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="true">
                        <svg style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M240 976q-33 0-56.5-23.5T160 896V416q0-33 23.5-56.5T240 336h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800 416v480q0 33-23.5 56.5T720 976H240Zm0-80h480V416h-80v80q0 17-11.5 28.5T600 536q-17 0-28.5-11.5T560 496v-80H400v80q0 17-11.5 28.5T360 536q-17 0-28.5-11.5T320 496v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480 256q-33 0-56.5 23.5T400 336ZM240 896V416v480Z"/></svg>
                        <span class="badge-holder"><?php _e("Shop"); ?><?php if ($order_notif_html): ?><?php print $order_notif_html; ?><?php endif; ?></span>
                    </a>
                    <div class="dropdown-menu" data-bs-popper="static">

                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <?php if (user_can_view_module(['module' => 'shop.products'])): ?>
                                <a href="<?php print route('admin.product.index'); ?>" class="dropdown-item justify-content-between <?php if ($action == 'products'): ?> active <?php endif; ?>">
                                   <span>
                                        <?php _e("Products"); ?>
                                   </span>
                                    <span data-href="<?php print route('admin.product.create'); ?>" class="btn btn-success btn-rounded btn-icon btn-sm add-new" data-bs-toggle="tooltip" title="<?php _e("Add new product") ?>"><i class="mdi mdi-plus"></i></span>
                                </a>
                                <?php endif; ?>

                                <?php if (user_can_view_module(['module' => 'order.index'])): ?>
                                <a href="<?php echo route('admin.order.index'); ?>" class="dropdown-item justify-content-between <?php if($view == 'order'): ?>active<?php endif;?>">
                                   <span>
                                        <?php _e("Orders"); ?>
                                   </span>
                                    <?php if ($order_notif_html): ?><?php print $order_notif_html; ?><?php endif; ?>
                                    <span data-href="javascript:mw_admin_add_order_popup()" class="btn btn-success btn-rounded btn-icon btn-sm add-new"
                                          data-bs-toggle="tooltip" title="<?php _e("Add order") ?>"><i class="mdi mdi-plus"></i></span>
                                </a>
                                <?php endif; ?>


                                <?php if (user_can_view_module(['module' => 'shop.category'])): ?>

                                <a href="<?php print route('admin.shop.category.index'); ?>" class="dropdown-item justify-content-between <?php if ($action == 'shop_category'): ?> active <?php endif; ?>">
                                   <span>
                                        <?php _e("Categories"); ?>
                                   </span>
                                    <span data-href="<?php echo route('admin.shop.category.create'); ?>" class="btn btn-success btn-rounded btn-icon btn-sm add-new" data-bs-toggle="tooltip" title="<?php _e("Add new category") ?>"><i class=" mdi mdi-plus"></i></span>
                                </a>

                                <?php
                                endif;
                                ?>

                                <?php if (user_can_view_module(['module' => 'shop.customers'])): ?>
                                <a href="<?php  print admin_url();  ?>customers" class="dropdown-item justify-content-between <?php if (url_segment(1) == 'customers'):  ?> active <?php endif;  ?>">
                                  <span>
                                        <?php  _e("Clients");   ?>
                                  </span>
                                </a>

                                <?php

                                /*
                                <a href="<?php echo route('admin.customers.index'); ?>" class="dropdown-item <?php if ($view == 'customers'): ?> active <?php endif; ?>">
                                    <?php _e("Clients"); ?>
                                </a>*/
                                ?>
                                <?php endif; ?>

                                <?php if (user_can_view_module(['module' => 'invoices']) && Route::has('admin.invoices.index') && mw()->module_manager->is_installed('invoice')): ?>
                                <a href="<?php echo route('admin.invoices.index'); ?>" class="dropdown-item justify-content-between <?php if ($view == 'invoices'): ?> active <?php endif; ?>">
                                    <?php _e("Invoices"); ?>
                                </a>
                                <?php endif; ?>

                                <a href="<?php print admin_url(); ?>view:shop/action:options" class="dropdown-item justify-content-between <?php if ($action == 'options'): ?> active <?php endif; ?>">
                                    <?php _e("Settings"); ?>
                                </a>
                            </div>
                        </div>
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
                    <a href="<?php print admin_url(); ?>view:modules" class="nav-link fs-3 <?php echo $modules_class; ?>">
                        <svg style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="m390 976-68-120H190l-90-160 68-120-68-120 90-160h132l68-120h180l68 120h132l90 160-68 120 68 120-90 160H638l-68 120H390Zm248-440h86l44-80-44-80h-86l-45 80 45 80ZM438 656h84l45-80-45-80h-84l-45 80 45 80Zm0-240h84l46-81-45-79h-86l-45 79 46 81ZM237 536h85l45-80-45-80h-85l-45 80 45 80Zm0 240h85l45-80-45-80h-86l-44 80 45 80Zm200 120h86l45-79-46-81h-84l-46 81 45 79Zm201-120h85l45-80-45-80h-85l-45 80 45 80Z"/></svg>

                           <span>
                                <?php _e("Modules"); ?>
                           </span> </a>
                </li>
                <?php endif; ?>

                <?php if (user_can_access('module.marketplace.index')): ?>
                <?php if (mw()->ui->disable_marketplace != true): ?>

                <?php
                $composerClient = new \MicroweberPackages\Package\MicroweberComposerClient();
                $countNewUpdates = $composerClient->countNewUpdatesCached();
                ?>

                <li class="nav-item">
                    <a href="<?php print admin_url(); ?>marketplace" class="nav-link fs-3 <?php if ($view == 'packages'): ?>active<?php endif; ?>">
                        <svg style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M841 538v318q0 33-23.5 56.5T761 936H201q-33 0-56.5-23.5T121 856V538q-23-21-35.5-54t-.5-72l42-136q8-26 28.5-43t47.5-17h556q27 0 47 16.5t29 43.5l42 136q12 39-.5 71T841 538Zm-272-42q27 0 41-18.5t11-41.5l-22-140h-78v148q0 21 14 36.5t34 15.5Zm-180 0q23 0 37.5-15.5T441 444V296h-78l-22 140q-4 24 10.5 42t37.5 18Zm-178 0q18 0 31.5-13t16.5-33l22-154h-78l-40 134q-6 20 6.5 43t41.5 23Zm540 0q29 0 42-23t6-43l-42-134h-76l22 154q3 20 16.5 33t31.5 13ZM201 856h560V574q-5 2-6.5 2H751q-27 0-47.5-9T663 538q-18 18-41 28t-49 10q-27 0-50.5-10T481 538q-17 18-39.5 28T393 576q-29 0-52.5-10T299 538q-21 21-41.5 29.5T211 576h-4.5q-2.5 0-5.5-2v282Zm560 0H201h560Z"/></svg>
                           <span>
                                <?php _e("Marketplace"); ?>
                           </span>
                        <?php
                        if ($countNewUpdates > 0):
                        ?>
                        <span class="badge-holder ms-auto">
                                    <span class="badge badge-success badge-pill mr-1 lh-0 d-inline-flex justify-content-center align-items-center position-relative" style="font-size: 11px; width: 20px; height:20px;"><?php echo $countNewUpdates; ?></span>
                                </span>
                        <?php
                        endif;
                        ?>
                    </a>
                </li>
                <?php endif; ?>
                <?php endif; ?>

                <li class="nav-item dropdown">
                    <a class="nav-link fs-3  <?php if (  ($view == 'settings')): ?> active <?php endif; ?>" href="<?php print admin_url(); ?>settings">
                        <svg style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="m370 976-16-128q-13-5-24.5-12T307 821l-119 50L78 681l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78 471l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12l-16 128H370Zm112-260q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342 576q0 58 40.5 99t99.5 41Zm0-80q-25 0-42.5-17.5T422 576q0-25 17.5-42.5T482 516q25 0 42.5 17.5T542 576q0 25-17.5 42.5T482 636Zm-2-60Zm-40 320h79l14-106q31-8 57.5-23.5T639 729l99 41 39-68-86-65q5-14 7-29.5t2-31.5q0-16-2-31.5t-7-29.5l86-65-39-68-99 42q-22-23-48.5-38.5T533 362l-13-106h-79l-14 106q-31 8-57.5 23.5T321 423l-99-41-39 68 86 64q-5 15-7 30t-2 32q0 16 2 31t7 30l-86 65 39 68 99-42q22 23 48.5 38.5T427 790l13 106Z"/></svg>
                        <span class="badge-holder"><?php _e("Settings"); ?></span>
                    </a>

                </li>





                <?php /*
                <li class="nav-item dropdown">
                    <a class="nav-link fs-3 dropdown-toggle <?php if (!url_param('has_core_update') and ($view == 'settings')): ?> active <?php endif; ?>" href="<?php print admin_url(); ?>view:settings#option_group=general">
                        <i class="fs-1 me-2 mdi mdi-cog"></i>
                        <span class="badge-holder"><?php _e("Settings"); ?></span>
                    </a>
                    <div class="dropdown-menu">

                        <a class="item-website dropdown-item" href="<?php print admin_url(); ?>view:settings#option_group=general">
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
                    <a class="nav-link fs-3 <?php print ($load_module == 'users' OR $view == 'roles') ? 'active' : ''; ?>" href="<?php print admin_url('users'); ?>">
                        <svg style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M400 576q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM80 896V784q0-33 17-62t47-44q51-26 115-44t141-18h14q6 0 12 2-8 18-13.5 37.5T404 696h-4q-71 0-127.5 18T180 750q-9 5-14.5 14t-5.5 20v32h252q6 21 16 41.5t22 38.5H80Zm560 40-12-60q-12-5-22.5-10.5T584 852l-58 18-40-68 46-40q-2-14-2-26t2-26l-46-40 40-68 58 18q11-8 21.5-13.5T628 596l12-60h80l12 60q12 5 22.5 11t21.5 15l58-20 40 70-46 40q2 12 2 25t-2 25l46 40-40 68-58-18q-11 8-21.5 13.5T732 876l-12 60h-80Zm40-120q33 0 56.5-23.5T760 736q0-33-23.5-56.5T680 656q-33 0-56.5 23.5T600 736q0 33 23.5 56.5T680 816ZM400 496q33 0 56.5-23.5T480 416q0-33-23.5-56.5T400 336q-33 0-56.5 23.5T320 416q0 33 23.5 56.5T400 496Zm0-80Zm12 400Z"/></svg>                       <span>
                              <?php _e("Users"); ?>
                        </span>
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

                <li class="nav-item"><a href="<?php print api_url('logout'); ?>" class="nav-link fs-3">
                        <svg style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M440 616V216h80v400h-80Zm40 320q-74 0-139.5-28.5T226 830q-49-49-77.5-114.5T120 576q0-80 33-151t93-123l56 56q-48 40-75 97t-27 121q0 116 82 198t198 82q117 0 198.5-82T760 576q0-64-26.5-121T658 358l56-56q60 52 93 123t33 151q0 74-28.5 139.5t-77 114.5q-48.5 49-114 77.5T480 936Z"/></svg>

                       <span>
                             <?php _e("Users"); ?>
                       </span>
                    </a></li>



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
