<ul class="navbar-nav-padding nav-item-profile-wrapper">
{{--    <li>--}}
{{--        <div class="nav-item nav-item-profile dropdown">--}}
{{--            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"--}}
{{--               aria-label="Open user menu">--}}

{{--                <div class="mw-admin-sidebar-profile"--}}
{{--                     style="background-image: url('<?php echo user_picture(); ?>'); background-position: center center; background-size: contain; background-repeat: no-repeat;">--}}
{{--                    <span style="font-size: 14px; width: 20px; height: 20px;"> </span>--}}
{{--                </div>--}}
{{--                <div>--}}
{{--                    <?php echo get_username_short(); ?>--}}
{{--                </div>--}}


{{--            </a>--}}
{{--            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">--}}
{{--                @include('admin::layouts.partials.navabar-bottom-menu-lang-switch')--}}



{{--                <a href="{{admin_url('user/profile')}}" class="dropdown-item">--}}
{{--                     <svg fill="currentColor"xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">--}}
{{--                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>--}}
{{--                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>--}}
{{--                        <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>--}}
{{--                        <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"></path>--}}
{{--                    </svg>--}}
{{--                    <?php _e('Profile') ?></a>--}}
{{--                <a href="#" class="dropdown-item">--}}
{{--                     <svg fill="currentColor"xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-help-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">--}}
{{--                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>--}}
{{--                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>--}}
{{--                        <path d="M12 16v.01"></path>--}}
{{--                        <path d="M12 13a2 2 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483"></path>--}}
{{--                    </svg>--}}
{{--                    <?php _e('Feedback') ?>--}}
{{--                </a>--}}

{{--                <a href="javascript:mw_admin_toggle_dark_theme()" class="dropdown-item">--}}
{{--                     <svg fill="currentColor"xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brightness-down" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">--}}
{{--                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>--}}
{{--                        <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>--}}
{{--                        <path d="M12 5l0 .01"></path>--}}
{{--                        <path d="M17 7l0 .01"></path>--}}
{{--                        <path d="M19 12l0 .01"></path>--}}
{{--                        <path d="M17 17l0 .01"></path>--}}
{{--                        <path d="M12 19l0 .01"></path>--}}
{{--                        <path d="M7 17l0 .01"></path>--}}
{{--                        <path d="M5 12l0 .01"></path>--}}
{{--                        <path d="M7 7l0 .01"></path>--}}
{{--                    </svg>--}}
{{--                    <?php _e('Theme') ?>--}}
{{--                </a>--}}
{{--                <div class="dropdown-divider"></div>--}}
{{--                <a href="<?php print api_url('logout'); ?>" class="dropdown-item">--}}
{{--                     <svg fill="currentColor"xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-logout" width="24"--}}
{{--                         height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"--}}
{{--                         stroke-linecap="round" stroke-linejoin="round">--}}
{{--                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>--}}
{{--                        <path--}}
{{--                            d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"></path>--}}
{{--                        <path d="M7 12h14l-3 -3m0 6l3 -3"></path>--}}
{{--                    </svg>--}}

{{--                    <?php _e('Logout') ?></a>--}}

{{--            </div>--}}
{{--        </div>--}}
{{--    </li>--}}
    <li class="nav-item nav-item-profile">




    </li>
    <?php event_trigger('mw.admin.sidebar.li.last'); ?>
    <div class="mt-3">
        @include('admin::layouts.partials.navabar-bottom-menu-lang-switch')
    </div>

    <a href="javascript:mw_admin_toggle_dark_theme()" class="dropdown-item nav-link">
        <svg fill="currentColor" style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M480 696q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Zm0 80q-83 0-141.5-58.5T280 576q0-83 58.5-141.5T480 376q83 0 141.5 58.5T680 576q0 83-58.5 141.5T480 776ZM80 616q-17 0-28.5-11.5T40 576q0-17 11.5-28.5T80 536h80q17 0 28.5 11.5T200 576q0 17-11.5 28.5T160 616H80Zm720 0q-17 0-28.5-11.5T760 576q0-17 11.5-28.5T800 536h80q17 0 28.5 11.5T920 576q0 17-11.5 28.5T880 616h-80ZM480 296q-17 0-28.5-11.5T440 256v-80q0-17 11.5-28.5T480 136q17 0 28.5 11.5T520 176v80q0 17-11.5 28.5T480 296Zm0 720q-17 0-28.5-11.5T440 976v-80q0-17 11.5-28.5T480 856q17 0 28.5 11.5T520 896v80q0 17-11.5 28.5T480 1016ZM226 378l-43-42q-12-11-11.5-28t11.5-29q12-12 29-12t28 12l42 43q11 12 11 28t-11 28q-11 12-27.5 11.5T226 378Zm494 495-42-43q-11-12-11-28.5t11-27.5q11-12 27.5-11.5T734 774l43 42q12 11 11.5 28T777 873q-12 12-29 12t-28-12Zm-42-495q-12-11-11.5-27.5T678 322l42-43q11-12 28-11.5t29 11.5q12 12 12 29t-12 28l-43 42q-12 11-28 11t-28-11ZM183 873q-12-12-12-29t12-28l43-42q12-11 28.5-11t27.5 11q12 11 11.5 27.5T282 830l-42 43q-11 12-28 11.5T183 873Zm297-297Z"/></svg>
       <span class="fs-3">
            <?php _e('Theme') ?>
       </span>
    </a>


    <a href="{{admin_url('user/profile')}}" class="nav-link fs-3">
        <div class="mw-admin-sidebar-profile">
            <p class="mb-0 text-uppercase" style="font-size: 14px;"><?php print get_username_short() ?></p>
        </div>

        <?php print user_name(); ?>
    </a>
</ul>
