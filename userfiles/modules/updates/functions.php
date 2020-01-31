<?php

event_bind('mw.admin.dashboard.content.before', function ($params = false) {
//    print '<div type="site_stats/admin" class="mw-lazy-load-module" id="site_stats_admin"></div>';


    if (isset($_GET['install_done']) and mw()->ui->disable_marketplace != true) {
        print '<h1 style="font-size: 24px; color: #555555;">Welcome to Microweber ' . MW_VERSION . '</h1>';
        print '<p style="font-size: 13px; color: #999999; line-height: 1.6;">Use Microweber to build your website, online shop or blog.</p>';
        print '<p style="font-size: 13px; color: #999999; line-height: 1.6; margin-bottom:30px;">Create and  edit content, sell online, manage orders and clients with Open Source CMS Microweber.</p>';
    }
});


event_bind(
    'mw.admin.sidebar.li.first', function ($item) {

    $update_channel = Config::get('microweber.update_channel');
    if ('disabled' == $update_channel) {
        return;
    }

    if (mw()->ui->disable_marketplace != true) {
        $cache_id = 'mw_update_check_auto_update_check_core';
        $cache_group = 'update';
        $last_check = cache_get($cache_id, $cache_group, 3600);

        if ($last_check and !empty($last_check) and isset($last_check['microweber/update'])) {
            if (!defined('MW_UPDATE_NOTIFICATION_BTN_DISPLAYED_IN_SIDEBAR')) {
                define('MW_UPDATE_NOTIFICATION_BTN_DISPLAYED_IN_SIDEBAR', 1);
            }
            print '<div type="updates/admin_sidebar_btn" no_wrap="true" class="mw-lazy-load-module"></div>';
        }

    }
}
);


event_bind(
    'mw.admin.sidebar.li.last', function ($item) {
    if (mw()->ui->disable_marketplace != true) {

        if (defined('MW_UPDATE_NOTIFICATION_BTN_DISPLAYED_IN_SIDEBAR')) {
            return;
        }
        $cache_id = 'mw_update_check_auto_update_check_core';
        $cache_group = 'update';

        $last_check = cache_get($cache_id, $cache_group, 3600);

        if ($last_check == 'noupdate') {
            return;
        }

        print '<div type="updates/admin_sidebar_btn" no_wrap="true" class="mw-lazy-load-module"></div>';

        //$check = __mw_check_core_system_update();


//        if ($last_check) {
//         //   print '<div type="updates/admin_sidebar_btn" no_wrap="true" class="mw-lazy-load-module"></div>';
//        }
    }
}
);


function __mw_check_core_system_update()
{


    $cache_id = 'mw_update_check_auto_update_check_core';
    $cache_group = 'update';

    $last_check = cache_get($cache_id, $cache_group, 3600);

    if ($last_check == 'noupdate') {
        return;
    }


    if (!$last_check) {

        try {
            $search_params = array('return_only_updates' => true, 'keyword' => 'microweber/update');

            $last_check = mw()->update->composer_search_packages($search_params);


            if (!$last_check) {
                $last_check = 'noupdate';
            } else {
                $count = count($last_check);
                if ($count > 0) {
                    $notif = array();
                    $notif['replace'] = true;
                    $notif['module'] = 'updates';
                    $notif['rel_type'] = 'update_check';
                    $notif['rel_id'] = 'updates_core';
                    $notif['title'] = 'New system update is available';
                    $notif['description'] = "There is new system update available";
                    // $notif['notification_data'] = @json_encode($last_check);

                    mw()->app->notifications_manager->save($notif);
                }
            }


            cache_save($last_check, $cache_id, $cache_group);


        } catch (Exception $e) {
            $last_check = 'noupdate';

            cache_save($last_check, $cache_id, $cache_group);
        }


    }

    if ($last_check and $last_check == 'noupdate') {
        return;
    }

    return $last_check;


}

event_bind('mw_admin_settings_menu', 'mw_print_admin_updates_settings_link');

function mw_print_admin_updates_settings_link()
{
    $update_channel = Config::get('microweber.update_channel');
    if ('disabled' == $update_channel) {
        return;
    }

    $active = url_param('view');
    $cls = '';
    if ($active == 'comments') {
        $cls = ' class="active" ';
    }

//       $update_check_count = mw()->app->notifications_manager->get('rel_type=update_check&is_read=0&count=1');
//    $notif_html = '';
//    if($update_check_count){
//        $notif_html = '<sup class="mw-notification-count">'.$update_check_count.'</sup>';
//
//    }

    //$check = __mw_check_core_system_update();

    $mname = module_name_encode('updates');
    $modurl = admin_url() . 'view:settings#option_group=' . module_name_encode('updates');
    print "<li><a class=\"item-" . $mname . "\" href=\"" . $modurl . "\"><span class=\"mai-thunder\"></span><strong>" . _e("Updates", true) . "</strong></a></li>";

    //$notif_count = mw()->notifications_manager->get('module=comments&is_read=0&count=1');
    /*if ($notif_count > 0) {
        $notif_html = '<sup class="mw-notif-bubble">' . $notif_count . '</sup>';
    }*/
    //print '<li' . $cls . '><a href="' . admin_url() . 'view:comments"><span class="ico icomment">' . $notif_html . '</span><span>Comments</span></a></li>';
}



