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
        $website_class = 'show';
    } else if ($view == 'content' and $action != false) {
        $website_class = 'show';
    }
    if ($routeName == 'admin.post.index') {
        $website_class = "show";
        $action = 'posts';
    }
    if ($routeName == 'admin.page.index') {
        $website_class = "show";
        $action = 'pages';
    }
    if ($routeName == 'admin.content.index') {
        $website_class = "show";
        $action = 'content';
    }
    if ($routeName == 'admin.category.index') {
        $website_class = "show";
        $action = 'category';
    }


    $shop_class = '';
    if ($view == 'shop' and $action == false) {
        $shop_class = "show";
    } elseif ($view == 'shop' and $action != false) {
        $shop_class = "show";
    } elseif ($view == 'modules' and $load_module == 'shop__coupons') {
        $shop_class = "show";
    } elseif ($view == 'shop' AND $action == 'products' OR $action == 'orders' OR $action == 'clients' OR $action == 'options') {
        $shop_class = "show";
    } elseif ($view == 'invoices') {
        $shop_class = "show";
    } elseif ($view == 'customers') {
        $shop_class = "show";
    } elseif ($view == 'order') {
        $shop_class = "show";
    }
    if ($routeName == 'admin.shop.dashboard') {
        $shop_class = "show";
    }
    ?>



    <div class="container-fluid" id="sidebar-menu">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <h1 class="navbar-brand navbar-nav-padding navbar-brand-autodark justify-content-start" style="padding: 0;">
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
            <ul class="navbar-nav navbar-nav-padding" id="mw-admin-main-navigation">

                <?php event_trigger('mw.admin.sidebar.li.first'); ?>


                @foreach(\MicroweberPackages\Admin\Facades\AdminManager::getMenu('left_menu_top') as $item)
                    @if(method_exists($item, 'items'))
                        @include('admin::layouts.partials.navbar-dropdown-link')
                    @else
                        @include('admin::layouts.partials.navbar-link')
                    @endif
                @endforeach

                <?php event_trigger('mw.admin.sidebar.li.last'); ?>


            </ul>



            <hr class="thin">

            @include('admin::layouts.partials.navbar-bottom-menu')

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

            ;(() => {
                const nav = document.querySelector('#sidebar-menu');
                document.addEventListener('click', e => {
                    if(!nav.contains(e.target)) {
                        document.querySelectorAll('.mw-admin-sidebar-navigation-menu.active').forEach(node => node.classList.remove('active'))
                    }
                })
                document.querySelectorAll('#sidebar-menu .add-new').forEach(node => {
                    node.addEventListener('click', e => {

                        e.preventDefault();
                        e.stopPropagation();
                        var target = node.parentElement.nextElementSibling;
                        if(target) {
                            document.querySelectorAll('.mw-admin-sidebar-navigation-menu').forEach(node => {
                                if(node !== target) {
                                    node.classList.remove('active');
                                } else {
                                    node.classList.toggle('active');
                                }
                            })
                        }
                    })
                })

            })();



        });
    </script>
</aside>
