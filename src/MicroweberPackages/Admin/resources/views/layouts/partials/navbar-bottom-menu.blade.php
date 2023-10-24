<?php
$bodyTheme = 'light';

if (isset($_COOKIE['admin_theme_dark'])) {
    $bodyTheme = 'dark';
}
?>

<ul class="navbar-nav-padding nav-item-profile-wrapper">
    <?php event_trigger('mw.admin.sidebar.li.last'); ?>
    <li class="mt-3">

            @include('admin::layouts.partials.navabar-bottom-menu-lang-switch')

    </li>

    <li class="py-1">
        <a onclick="mw_admin_toggle_dark_theme()"
           class="nav-link navbar-change-theme-icon navbar-change-theme-icon-light" style="padding-bottom: 0 !important;">
            <span id="navbar-change-theme-icon-light" <?php if ($bodyTheme == 'dark'): ?> style="display: none;" <?php endif; ?>>
                <span class="d-flex">
                    <svg fill="currentColor" class="me-3" xmlns="http://www.w3.org/2000/svg" height="24"
                         viewBox="0 96 960 960" width="24">
                        <path
                            d="M480 696q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Zm0 80q-83 0-141.5-58.5T280 576q0-83 58.5-141.5T480 376q83 0 141.5 58.5T680 576q0 83-58.5 141.5T480 776ZM80 616q-17 0-28.5-11.5T40 576q0-17 11.5-28.5T80 536h80q17 0 28.5 11.5T200 576q0 17-11.5 28.5T160 616H80Zm720 0q-17 0-28.5-11.5T760 576q0-17 11.5-28.5T800 536h80q17 0 28.5 11.5T920 576q0 17-11.5 28.5T880 616h-80ZM480 296q-17 0-28.5-11.5T440 256v-80q0-17 11.5-28.5T480 136q17 0 28.5 11.5T520 176v80q0 17-11.5 28.5T480 296Zm0 720q-17 0-28.5-11.5T440 976v-80q0-17 11.5-28.5T480 856q17 0 28.5 11.5T520 896v80q0 17-11.5 28.5T480 1016ZM226 378l-43-42q-12-11-11.5-28t11.5-29q12-12 29-12t28 12l42 43q11 12 11 28t-11 28q-11 12-27.5 11.5T226 378Zm494 495-42-43q-11-12-11-28.5t11-27.5q11-12 27.5-11.5T734 774l43 42q12 11 11.5 28T777 873q-12 12-29 12t-28-12Zm-42-495q-12-11-11.5-27.5T678 322l42-43q11-12 28-11.5t29 11.5q12 12 12 29t-12 28l-43 42q-12 11-28 11t-28-11ZM183 873q-12-12-12-29t12-28l43-42q12-11 28.5-11t27.5 11q12 11 11.5 27.5T282 830l-42 43q-11 12-28 11.5T183 873Zm297-297Z"/>
                    </svg>

                    <span class="fs-3">
                      <?php _e('Light') ?>
                    </span>
                </span>
            </span>

            <span id="navbar-change-theme-icon-dark" <?php if ($bodyTheme == 'light'): ?> style="display: none;" <?php endif; ?> >
                <span class="d-flex">
                    <svg fill="currentColor" class="me-3" xmlns="http://www.w3.org/2000/svg" height="24"
                         viewBox="0 96 960 960" width="24">
                        <path
                            d="M480 936q-150 0-255-105T120 576q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444 396q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480 936Zm0-80q88 0 158-48.5T740 681q-20 5-40 8t-40 3q-123 0-209.5-86.5T364 396q0-20 3-40t8-40q-78 32-126.5 102T200 576q0 116 82 198t198 82Zm-10-270Z"/>
                    </svg>
                    <span class="fs-3">
                <?php _e('Dark') ?>
            </span>
                </span>
            </span>
        </a>
    </li>
    <li class="nav-item nav-item-profile pt-1">

        <a href="{{admin_url('user/profile')}}" class="nav-link fs-3">

        @if(user_has_picture(user_id()))
        <img src="{{user_picture(user_id(),165,165)}}" alt="" class="rounded-circle profile-img" >
        @else
        <div class="mw-admin-sidebar-profile">
            <span class="mb-0 text-uppercase" style="font-size: 14px; text-overflow: ellipsis;">

                <?php print get_username_short() ?>
            </span>
        </div>
        @endif

            <span style="text-overflow: ellipsis; overflow: hidden; display: block; white-space:nowrap; max-width: 150px;">
               <?php print user_name(); ?>
            </span>

        </a>
    </li>
</ul>


