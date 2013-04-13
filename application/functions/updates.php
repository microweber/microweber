<?php

api_expose('mw_post_update');
function mw_post_update() {

	$a = is_admin();
	if ($a != false) {
		cache_clean_group('db');
		cache_clean_group('update/global');
		cache_clean_group('elements/global');
		cache_clean_group('modules/global');
		scan_for_modules();
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
	
	if (isarr($params)) {
		foreach ($params as $param_k => $param) {
 			if($param_k == 'mw_version'){
				$param['mw_version'] = $param_k;
			}
			
			if($param_k == 'elements'){
				$param['elements'] = $param;
			}
			
			
			if($param_k == 'modules'){
				$param['modules'] = $param;
			}
			if($param_k == 'module_templates'){
				$param['module_templates'] = $param;
			}
			
			if (isset($param['mw_version'])) {
				$res[] = $update_api -> install_version($param['mw_version']);
			}
			if (isset($param['elements']) and isarr($param['elements'])) {
				foreach ($param['elements'] as $item) {
					$res[] = $update_api -> install_element($item);
				}
			}
			if (isset($param['modules']) and isarr($param['modules'])) {
				foreach ($param['modules'] as $item) {
					$res[] = $update_api -> install_module($item);
				}
			}
			if (isset($param['module_templates']) and isarr($param['module_templates'])) {
				foreach ($param['module_templates'] as $k => $item) {
					$res[] = $update_api -> install_module_template($k, $item);
				}
			}

		}
	}
	return $res;

}

function mw_updates_count() {
	$count = 0;
	$upd_count = mw_check_for_update();

	if (isset($upd_count['modules'])) {
		$count = $count + sizeof($upd_count['modules']);
	}
	return $count;
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
