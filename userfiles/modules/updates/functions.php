<?php


event_bind(
    'mw.admin.sidebar.li.1', function ($item) {
    if (mw()->ui->disable_marketplace != true) {
        $packages = __mw_get_packages_that_has_updates('microweber/core-update');

        if ($packages) {
            print '<module type="updates/admin_sidebar_btn" no_wrap="true" class="mw-lazy-load-module"></module>';
        }
    }
}
);


function __mw_get_packages_that_has_updates($package_name=false)
{


    $search_params = array('return_only_updates' => true);
    if($package_name){
       $search_params['keyword'] = $package_name;
    }

    $cache_id = 'mw_update_check_auto_update_check'.crc32($package_name);
    $cache_group = 'update';


    $last_check = mw()->update->composer_search_packages($search_params);




   /* dd($last_check);

    $last_check = cache_get($cache_id, $cache_group, 3600);
    if (!$last_check) {
        $last_check = mw()->update->composer_search_packages($search_params);
        cache_save($last_check, $cache_id, $cache_group);
    }*/
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
    $notif_html = '';
    $mname = module_name_encode('updates');
    print "<li><a class=\"item-" . $mname . "\" href=\"#option_group=" . $mname . "\"><span class=\"mai-thunder\"></span><strong>" . _e("Updates", true) . "</strong></a></li>";

    //$notif_count = mw()->notifications_manager->get('module=comments&is_read=0&count=1');
    /*if ($notif_count > 0) {
        $notif_html = '<sup class="mw-notif-bubble">' . $notif_count . '</sup>';
    }*/
    //print '<li' . $cls . '><a href="' . admin_url() . 'view:comments"><span class="ico icomment">' . $notif_html . '</span><span>Comments</span></a></li>';
}



