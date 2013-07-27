<?php













function mw_warning($text, $exit = false)
{
    return mw('format')->notif($text, $exit);
}








function debug_info()
{
    //if (c('debug_mode')) {

    return include(ADMIN_VIEWS_PATH . 'debug.php');
    // }
}












