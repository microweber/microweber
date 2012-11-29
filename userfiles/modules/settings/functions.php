<?

/**
 *
 * Settings module api
 *
 * @package		modules
 * @subpackage		settings
 * @since		Version 0.1
 */

// ------------------------------------------------------------------------
action_hook('mw_admin_header_menu', 'mw_print_admin_menu_settings_btn');

function mw_print_admin_menu_settings_btn() {
  $active = url_param('view');
  $cls = '';
  if($active == 'settings'){
	   $cls = ' class="active" ';
  }
	print '<li'.$cls.'><a href="'.admin_url().'view:settings">Settings</a></li>';
}


 