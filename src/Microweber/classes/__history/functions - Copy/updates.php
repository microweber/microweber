<?php




 action_hook('mw_admin_settings_menu', 'mw_print_admin_backup_settings_link');

function mw_print_admin_backup_settings_link() {

	if(is_module_installed('admin/backup')){

	$active = url_param('view');
	$cls = '';
	$mname = module_name_encode('admin/backup/small');
	if ($active == $mname ) {
		$cls = ' class="active" ';
	}
	$notif_html = '';
	$url = admin_url('view:modules/load_module:'.$mname);
	print "<li><a class=\"item-".$mname."\" href=\"#option_group=".$mname."\">Backup</a></li>";
	//print "<li><a class=\"item-".$mname."\" href=\"".$url."\">Backup</a></li>";
	}
	//$notif_count = \mw\Notifications::get('module=comments&is_read=n&count=1');
	/*if ($notif_count > 0) {
		$notif_html = '<sup class="mw-notif-bubble">' . $notif_count . '</sup>';
	}*/
	//print '<li' . $cls . '><a href="' . admin_url() . 'view:comments"><span class="ico icomment">' . $notif_html . '</span><span>Comments</span></a></li>';
}















api_expose('mw_post_update');
function mw_post_update() {

	$a = is_admin();
	if ($a != false) {
		cache_clean_group('db');
		cache_clean_group('update/global');
		cache_clean_group('elements/global');

		cache_clean_group('templates');
		cache_clean_group('modules/global');
		scan_for_modules();
		get_elements();
		exec_action('mw_db_init_default');
		exec_action('mw_db_init_modules');
		exec_action('mw_db_init');

	}

}




api_expose('mw_apply_updates');

function mw_apply_updates($params) {
	only_admin_access();
	$params = parse_params($params);
	$update_api = new \mw\Update();
	$res = array();
 	$upd_params = array();
	if (isarr($params)) {
		foreach ($params as $param_k => $param) {
			if ($param_k == 'mw_version') {
				$upd_params['mw_version'] = $param_k;
			}

			if ($param_k == 'elements') {
				$upd_params['elements'] = $param;
			}

			if ($param_k == 'modules') {
				$upd_params['modules'] = $param;
			}
			if ($param_k == 'module_templates') {
				$upd_params['module_templates'] = $param;
			}

			if (isset($upd_params['mw_version'])) {
				$res[] = $update_api -> install_version($upd_params['mw_version']);

			}
			if (isset($upd_params['elements']) and isarr($upd_params['elements'])) {
				foreach ($param['elements'] as $item) {
					$res[] = $update_api -> install_element($item);
				}
			}
			if (isset($upd_params['modules']) and isarr($upd_params['modules'])) {
				foreach ($param['modules'] as $item) {
					$res[] = $update_api -> install_module($item);
				}
			}
			if (isset($upd_params['module_templates']) and isarr($upd_params['module_templates'])) {
				foreach ($upd_params['module_templates'] as $k => $item) {
					if (isarr($item)) {
						foreach ($item as $layout_file) {
							$res[] = $update_api -> install_module_template($k, $layout_file);

						}

					} elseif (is_string($item)) {
						$res[] = $update_api -> install_module_template($k, $item);
					}
				}
			}

		}

		if (isarr($res)) {
			mw_post_update();
			\mw\Notifications::delete_for_module('updates');

		}
	}
	return $res;

}

function mw_updates_count() {
	$count = 0;
	$upd_count = mw_check_for_update();
	if(isset($upd_count['count'])){
		return intval($upd_count['count']);
	} else {
		return false;
	}


}

$mw_avail_updates = false;
function mw_check_for_update() {
	global $mw_avail_updates;
	if ($mw_avail_updates == false) {

		$update_api = new \mw\Update();

		$iudates = $update_api -> check();
$mw_avail_updates = $iudates;

	}
	return $mw_avail_updates;

}





//api_expose('mw_send_anonymous_server_data');
// function used do send us the language files
function mw_send_anonymous_server_data($params) {
	only_admin_access();
	$update_api = new \mw\Update();



 		if ($params != false) {
            $params = parse_params($params);
        } else {

        }


        if(method_exists($update_api,'send_anonymous_server_data')){
        	$iudates = $update_api -> send_anonymous_server_data($params);

        	return $iudates;
        } else {
        	 $params['site_url'] = site_url();
        	 $result = $update_api->call('send_anonymous_server_data', $params);
       		 return $result;
        }



}



