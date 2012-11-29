<?


/**
 *
 * Newsletter module api
 *
 * @package		modules
 * @subpackage		newsletter
 * @since		Version 0.1
 */

// ------------------------------------------------------------------------
action_hook('mw_db_init', 'mw_newsletter_module_init_db');

function mw_newsletter_module_init_db() {
  $active = url_param('view');
  $cls = '';
  if($active == 'settings'){
	   $cls = ' class="active" ';
  }
	print '<li'.$cls.'><a href="'.admin_url().'view:settings">newsleter</a></li>';
}
