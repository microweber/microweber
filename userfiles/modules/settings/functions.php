<?php

/**
 *
 * Settings module api
 *
 * @package     modules
 * @subpackage      settings
 * @since       Version 0.1
 */

// ------------------------------------------------------------------------
event_bind('mw_admin_header_menu', 'mw_print_admin_menu_settings_btn');

function mw_print_admin_menu_settings_btn()
{
    $active = mw('url')->param('view');
    $cls = '';
    if ($active == 'settings') {
        $cls = ' class="active" ';
    }
    print '<li' . $cls . '><a href="' . admin_url() . 'view:settings"><i class="ico inavsettings"></i><span>' . _e("Settings", true) . '</span></a></li>';
}
