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
                        <li class="nav-item dropdown">
                            <a href="" class="nav-link fs-3 dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="true">
                                <svg style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M240 976q-33 0-56.5-23.5T160 896V416q0-33 23.5-56.5T240 336h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800 416v480q0 33-23.5 56.5T720 976H240Zm0-80h480V416h-80v80q0 17-11.5 28.5T600 536q-17 0-28.5-11.5T560 496v-80H400v80q0 17-11.5 28.5T360 536q-17 0-28.5-11.5T320 496v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480 256q-33 0-56.5 23.5T400 336ZM240 896V416v480Z"/></svg>
                                <span class="badge-holder">
                                {{$item->prepend->text()}}
                            </span>
                            </a>
                            <div class="dropdown-menu" data-bs-popper="static">
                                <div class="dropdown-menu-columns">
                                    <div class="dropdown-menu-column">
                                        @foreach($item->items() as $subItem)
                                            <a href="" class="dropdown-item justify-content-between">
                                               <span>
                                                    {{$subItem->text()}}
                                               </span>
                                                <span data-href="" class="add-new" data-bs-toggle="tooltip" title="">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M240 656q-33 0-56.5-23.5T160 576q0-33 23.5-56.5T240 496q33 0 56.5 23.5T320 576q0 33-23.5 56.5T240 656Zm240 0q-33 0-56.5-23.5T400 576q0-33 23.5-56.5T480 496q33 0 56.5 23.5T560 576q0 33-23.5 56.5T480 656Zm240 0q-33 0-56.5-23.5T640 576q0-33 23.5-56.5T720 496q33 0 56.5 23.5T800 576q0 33-23.5 56.5T720 656Z"/></svg>
                                                </span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{$item->url()}}" class="nav-link fs-3 ">
                                <svg style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M520 456V216h320v240H520ZM120 616V216h320v400H120Zm400 320V536h320v400H520Zm-400 0V696h320v240H120Zm80-400h160V296H200v240Zm400 320h160V616H600v240Zm0-480h160v-80H600v80ZM200 856h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360 776Z"></path></svg>
                                <span>
                                {{$item->text()}}
                                </span>
                            </a>
                        </li>
                    @endif
                @endforeach

                <?php event_trigger('mw.admin.sidebar.li.last'); ?>


                <li class="nav-item mt-auto"><a href="<?php print api_url('Get Help'); ?>" class="nav-link fs-3">
                        <svg style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M478 816q21 0 35.5-14.5T528 766q0-21-14.5-35.5T478 716q-21 0-35.5 14.5T428 766q0 21 14.5 35.5T478 816Zm-36-154h74q0-33 7.5-52t42.5-52q26-26 41-49.5t15-56.5q0-56-41-86t-97-30q-57 0-92.5 30T342 438l66 26q5-18 22.5-39t53.5-21q32 0 48 17.5t16 38.5q0 20-12 37.5T506 530q-44 39-54 59t-10 73Zm38 314q-83 0-156-31.5T197 859q-54-54-85.5-127T80 576q0-83 31.5-156T197 293q54-54 127-85.5T480 176q83 0 156 31.5T763 293q54 54 85.5 127T880 576q0 83-31.5 156T763 859q-54 54-127 85.5T480 976Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>
                       <span>
                             <?php _e("Get Help"); ?>
                       </span>
                    </a></li>

                <li class="nav-item"><a href="<?php print api_url('logout'); ?>" class="nav-link fs-3">
                        <svg style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M440 616V216h80v400h-80Zm40 320q-74 0-139.5-28.5T226 830q-49-49-77.5-114.5T120 576q0-80 33-151t93-123l56 56q-48 40-75 97t-27 121q0 116 82 198t198 82q117 0 198.5-82T760 576q0-64-26.5-121T658 358l56-56q60 52 93 123t33 151q0 74-28.5 139.5t-77 114.5q-48.5 49-114 77.5T480 936Z"/></svg>

                        <span>
                             <?php _e("Logout"); ?>
                       </span>
                    </a></li>
            </ul>

            <hr class="thin">
            <ul class="navbar-nav-padding nav-item-profile-wrapper">
                <li class="nav-item nav-item-profile">
                    <a href="{{admin_url('user/profile')}}" class="nav-link fs-3">
                        <div class="mw-admin-sidebar-profile">
                                <span style="font-size: 14px;">SB</span>
                        </div>
                        <div>
                            <?php echo get_username_short(); ?>
                        </div>
                    </a>
                </li>
{{--                <?php event_trigger('mw.admin.sidebar.li.last'); ?>--}}
{{--                <div class="mt-5">--}}
{{--                    <?php include(modules_path(). DS . 'admin/lang_swich_footer.php'); ?>--}}
{{--                </div>--}}
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
