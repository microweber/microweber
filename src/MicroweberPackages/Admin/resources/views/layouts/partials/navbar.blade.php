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

                    <?php if (is_shop_module_enabled_for_user()): ?>
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

        <?php if (is_shop_module_enabled_for_user()): ?>
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
                <?php endif; ?>

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
            <?php include(modules_path(). DS . 'admin/lang_swich_footer.php'); ?>
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
