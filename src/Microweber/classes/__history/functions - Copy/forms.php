<?php

if (!defined("MW_DB_TABLE_COUNTRIES")) {
	define('MW_DB_TABLE_COUNTRIES', MW_TABLE_PREFIX . 'countries');
}
if (!defined("MW_DB_TABLE_FORMS_LISTS")) {
	define('MW_DB_TABLE_FORMS_LISTS', MW_TABLE_PREFIX . 'forms_lists');
}

if (!defined("MW_DB_TABLE_FORMS_DATA")) {
	define('MW_DB_TABLE_FORMS_DATA', MW_TABLE_PREFIX . 'forms_data');
}

//action_hook('mw_db_init_default', 'mw_db_init_forms_table');
action_hook('mw_db_init', 'mw_db_init_forms_table');

function mw_db_init_forms_table() {
	$function_cache_id = false;

	$args = func_get_args();

	foreach ($args as $k => $v) {

		$function_cache_id = $function_cache_id . serialize($k) . serialize($v);
	}

	$function_cache_id = __FUNCTION__ . crc32($function_cache_id);

	$cache_content = cache_get_content($function_cache_id, 'db');

	if (($cache_content) != false) {

		return $cache_content;
	}

	$table_name = MW_DB_TABLE_FORMS_DATA;

	$fields_to_add = array();

	//$fields_to_add[] = array('updated_on', 'datetime default NULL');
	$fields_to_add[] = array('created_on', 'datetime default NULL');
	$fields_to_add[] = array('created_by', 'int(11) default NULL');
	//$fields_to_add[] = array('edited_by', 'int(11) default NULL');
	$fields_to_add[] = array('rel', 'TEXT default NULL');
	$fields_to_add[] = array('rel_id', 'TEXT default NULL');
	//$fields_to_add[] = array('position', 'int(11) default NULL');
	$fields_to_add[] = array('list_id', 'int(11) default 0');
	$fields_to_add[] = array('form_values', 'TEXT default NULL');
	$fields_to_add[] = array('module_name', 'TEXT default NULL');

	$fields_to_add[] = array('url', 'TEXT default NULL');
	$fields_to_add[] = array('user_ip', 'TEXT default NULL');

	set_db_table($table_name, $fields_to_add);

	db_add_table_index('rel', $table_name, array('rel(55)'));
	db_add_table_index('rel_id', $table_name, array('rel_id(255)'));
	db_add_table_index('list_id', $table_name, array('list_id'));

	$table_name = MW_DB_TABLE_FORMS_LISTS;

	$fields_to_add = array();

	//$fields_to_add[] = array('updated_on', 'datetime default NULL');
	$fields_to_add[] = array('created_on', 'datetime default NULL');
	$fields_to_add[] = array('created_by', 'int(11) default NULL');
	$fields_to_add[] = array('title', 'longtext default NULL');
	$fields_to_add[] = array('description', 'TEXT default NULL');
	$fields_to_add[] = array('custom_data', 'TEXT default NULL');

	$fields_to_add[] = array('module_name', 'TEXT default NULL');
	$fields_to_add[] = array('last_export', 'datetime default NULL');
	$fields_to_add[] = array('last_sent', 'datetime default NULL');

	set_db_table($table_name, $fields_to_add);

	db_add_table_index('title', $table_name, array('title(55)'));

	cache_save(true, $function_cache_id, $cache_group = 'db');
	return true;

}

action_hook('mw_db_init', 'mw_db_init_countries_table');

function mw_db_init_countries_table() {
	$function_cache_id = false;

	$args = func_get_args();

	foreach ($args as $k => $v) {

		$function_cache_id = $function_cache_id . serialize($k) . serialize($v);
	}

	$function_cache_id = __FUNCTION__ . crc32($function_cache_id);

	$cache_content = cache_get_content($function_cache_id, 'db');

	if (($cache_content) != false) {

		return $cache_content;
	}

	$table_sql = INCLUDES_PATH . 'install' . DS . 'countries.sql';

	import_sql_from_file($table_sql);

	cache_save(true, $function_cache_id, $cache_group = 'db');
	return true;
}

function countries_list() {

	$table = MW_DB_TABLE_COUNTRIES;

	$sql = "SELECT name as country_name from $table   ";

	$q = db_query($sql, __FUNCTION__ . crc32($sql), 'db');
	$res = array();
	if (isarr($q)) {
		foreach ($q as $value) {
			$res[] = $value['country_name'];
		}
		return $res;
	} else {
		return false;
	}

}

api_expose('save_form_list');
function save_form_list($params) {
	$adm = is_admin();
	if ($adm == false) {
		exit('You must be admin');
	}

	$table = MW_DB_TABLE_FORMS_LISTS;

	if (isset($params['mw_new_forms_list'])) {
		$params['id'] = 0;
		$params['id'] = 0;
		$params['title'] = $params['mw_new_forms_list'];
	}
	if (isset($params['for_module'])) {
		$params['module_name'] = $params['for_module'];
	}

	$params['table'] = $table;
	$id = save_data($table, $params);
	if (isset($params['for_module_id'])) {
		$opts = array();
		$data['module'] = $params['module_name'];
		$data['option_group'] = $params['for_module_id'];
		$data['option_key'] = 'list_id';
		$data['option_value'] = $id;
		save_option($data);
	}

	return array('success' => 'List is updated', $params);



	return $params;
}


api_expose('delete_forms_list');

function delete_forms_list($data) {

	$adm = is_admin();
	if ($adm == false) {
		return array('error' =>'Error: not logged in as admin.'.__FILE__.__LINE__);
	}
	$table = MW_DB_TABLE_FORMS_LISTS;
	if (isset($data['id'])) {
		$c_id = intval($data['id']);
		db_delete_by_id('forms_lists', $c_id);
		db_delete_by_id('forms_data', $c_id, 'list_id');

	}
}

api_expose('delete_form_entry');

function delete_form_entry($data) {

	$adm = is_admin();
	if ($adm == false) {
		return array('error' =>'Error: not logged in as admin.'.__FILE__.__LINE__);
	}
	$table = MW_DB_TABLE_FORMS_LISTS;
	if (isset($data['id'])) {
		$c_id = intval($data['id']);


			$fields = get_custom_fields('forms_data', $data['id'], 1);

			if (isarr($fields)) {

				foreach ($fields as $key => $value) {

					if(isset($value['id'])){

						$remid =  $value['id'];
						$custom_field_table = MW_TABLE_PREFIX . 'custom_fields';
						$q = "DELETE FROM $custom_field_table where id='$remid'";

						db_q($q);


					}




				}



			cache_clean_group('custom_fields');

			}




		db_delete_by_id('forms_data', $c_id);
	}
}

api_expose('forms_list_export_to_excel');
function forms_list_export_to_excel($params){

	set_time_limit(0);

	$adm = is_admin();
	if ($adm == false) {
		return array('error' =>'Error: not logged in as admin.'.__FILE__.__LINE__);
	}
	if (!isset($params['id'])) {
		return array('error' =>'Please specify list id! By posting field id=the list id ');

	} else {
		$lid = intval($params['id']);
		$data = get_form_entires('limit=100000&list_id=' .$lid);
		if(isarr($data)){
			  foreach ($data as $item) {
			   if(isset($item['custom_fields'])){
			   	 $custom_fields = array();
			    foreach ($item['custom_fields'] as $value) {
			     $custom_fields[$value['custom_field_name']] =$value;
			    }
			   }
			  }
			}



		  $csv_output = '';
	      if(isset($custom_fields) and isarr($custom_fields )){
	      	$csv_output = 'id,';
	      	$csv_output .= 'created_on,';
	      	$csv_output .= 'user_ip,';
		   	foreach($custom_fields   as $k=>$item){
			$csv_output .= titlelize($k).",";
			$csv_output .= "\t";
		   	}
		   	$csv_output .= "\n";


			   	 foreach ($data as $item){

				   	if(isset($item['custom_fields'])){
				   	$csv_output .= $item['id'].",";
					$csv_output .= "\t";
					$csv_output .= $item['created_on'].",";
					$csv_output .= "\t";
					$csv_output .= $item['user_ip'].",";
					$csv_output .= "\t";

					foreach($item['custom_fields']   as $item1){
					$csv_output .= $item1['custom_field_values_plain'].",";
					$csv_output .= "\t";
					}
					$csv_output .= "\n";
					}
			   }
		   }
		   $filename ='export'."_".date("Y-m-d_H-i",time()).uniqid().'.csv';
		   $filename_path = CACHEDIR.'forms_data'.DS.'global'.DS;
		   if(!is_dir( $filename_path)){
				 mkdir_recursive( $filename_path);
			}
			$filename_path_full = $filename_path.$filename;
 			file_put_contents($filename_path_full , $csv_output );
			$download = dir2url($filename_path_full);
 			return array('success' =>'Your file has been exported!', 'download' =>$download);

	}



}


function get_form_entires($params) {
	$params = parse_params($params);
	$table = MW_DB_TABLE_FORMS_DATA;
	$params['table'] = $table;


if(!isset($params["order_by"] )){
$params["order_by"] = 'created_on desc';
}


	//$params['debug'] = $table;
	$data = get($params);
	$ret = array();
	if (isarr($data)) {

		foreach ($data as $item) {
			//d($item);
			//

			$fields = get_custom_fields('forms_data', $item['id'], 1);

			if (isarr($fields)) {
				ksort($fields);
				$item['custom_fields'] = array();
				foreach ($fields as $key => $value) {
					$item['custom_fields'][$key] = $value;
				}
			}
			//d($fields);
			$ret[] = $item;
		}
		return $ret;
	} else {
		return $data;
	}
}

function get_form_lists($params) {
	$params = parse_params($params);
	$table = MW_DB_TABLE_FORMS_LISTS;
	$params['table'] = $table;

	return get($params);
}

api_expose('post_form');
function post_form($params) {

	$adm = is_admin();

	$table = MW_DB_TABLE_FORMS_DATA;
	mw_var('FORCE_SAVE', $table);

	if (isset($params['id'])) {
		if ($adm == false) {
			return array('error' => 'Error: Only admin can edit forms!');
		}
	}
	$for = 'module';
	if (isset($params['for'])) {
		$for = $params['for'];
	}

	if (isset($params['for_id'])) {
		$for_id = $params['for_id'];
	} else if (isset($params['data-id'])) {
		$for_id = $params['data-id'];
	} else if (isset($params['id'])) {
		$for_id = $params['id'];
	}

	//$for_id =$params['id'];
	if (isset($params['rel_id'])) {
		$for_id = $params['rel_id'];
	}

	$dis_cap = get_option('disable_captcha', $for_id) == 'y';

	if ($dis_cap == false) {
		if (!isset($params['captcha'])) {
			return array('error' => 'Please enter the captcha answer!');
		} else {
			$cap = session_get('captcha');

			if ($cap == false) {
				return array('error' => 'You must load a captcha first!');
			}
			if (intval($params['captcha']) != ($cap)) {
				//     d($cap);
				if ($adm == false) {
					return array('error' => 'Invalid captcha answer!');
				}
			}
		}
	}

	if ($for == 'module') {
		$list_id = get_option('list_id', $for_id);
	}
	$email_to = get_option('email_to', $for_id);
	$email_bcc = get_option('email_bcc', $for_id);
	$email_autorespond = get_option('email_autorespond', $for_id);

	$email_autorespond_subject = get_option('email_autorespond_subject', $for_id);

	if ($list_id == false) {
		$list_id = 0;
	}

	$to_save = array();
	$fields_data = array();
	$more = get_custom_fields($for, $for_id, 1);
	$cf_to_save = array();
	if (!empty($more)) {
		foreach ($more as $item) {
			if (isset($item['custom_field_name'])) {
				$cfn = ($item['custom_field_name']);

				$cfn2 = str_replace(' ', '_', $cfn);
				$fffound = false;

				if (isset($params[$cfn2])) {
					$fields_data[$cfn2] = $params[$cfn2];
					$item['custom_field_value'] = $params[$cfn2];
					$fffound = 1;
					$cf_to_save[] = $item;
				} elseif (isset($params[$cfn])) {
					$fields_data[$cfn] = $params[$cfn];
					$item['custom_field_value'] = $params[$cfn2];
					$cf_to_save[] = $item;
					$fffound = 1;
				}

			}
		}
	}
	$to_save['list_id'] = $list_id;
	$to_save['rel_id'] = $for_id;
	$to_save['rel'] = $for;
	//$to_save['custom_fields'] = $fields_data;

	if (isset($params['module_name'])) {
		$to_save['module_name'] = $params['module_name'];
	}

	if (isset($params['form_values'])) {
		$to_save['form_values'] = $params['form_values'];
	}

	$save = save_data($table, $to_save);

	if (!empty($cf_to_save)) {
		$table_custom_field = MW_TABLE_PREFIX . 'custom_fields';

		foreach ($cf_to_save as $value) {

			$value['copy_of_field'] = $value['id'];

			$value['id'] = 0;
			if (isset($value['session_id'])) {
				unset($value['session_id']);
			}
			$value['rel_id'] = $save;
			$value['rel'] = 'forms_data';

			$cf_save = save_data($table_custom_field, $value);
		}
	}

	if (isset($params['module_name'])) {

		$pp_arr = $params;
		$pp_arr['ip'] = USER_IP;
		unset($pp_arr['module_name']);
		if (isset($pp_arr['rel'])) {
			unset($pp_arr['rel']);
		}

		if (isset($pp_arr['rel_id'])) {
			unset($pp_arr['rel_id']);
		}

		if (isset($pp_arr['list_id'])) {
			unset($pp_arr['list_id']);
		}

		if (isset($pp_arr['for'])) {
			unset($pp_arr['for']);
		}

		if (isset($pp_arr['for_id'])) {
			unset($pp_arr['for_id']);
		}

		$notif = array();
		$notif['module'] = $params['module_name'];
		$notif['rel'] = 'forms_lists';
		$notif['rel_id'] = $list_id;
		$notif['title'] = "New form entry";
		$notif['description'] = "You have new form entry";
		$notif['content'] = "You have new form entry from " . curent_url(1) . '<br />' . array_pp($pp_arr);
		\mw\Notifications::save($notif);
		//	d($cf_to_save);
		if ($email_to == false) {
			$email_to = get_option('email_from', 'email');

		}
		if ($email_to != false) {
			$mail_sj = "Thank you!";
			$mail_autoresp = "Thank you for your submition! <br/>";

			if ($email_autorespond_subject != false) {
				$mail_sj = $email_autorespond_subject;
			}
			if ($email_autorespond != false) {
				$mail_autoresp = $email_autorespond;
			}

			$mail_autoresp = $mail_autoresp . array_pp($pp_arr);

			$user_mails = array();
			$user_mails[] = $email_to;
			if (isset($email_bcc) and (filter_var($email_bcc, FILTER_VALIDATE_EMAIL))) {
				$user_mails[] = $email_bcc;
			}

			if (isset($cf_to_save) and !empty($cf_to_save)) {
				foreach ($cf_to_save as $value) {
					//	d($value);
					$to = $value['custom_field_value'];
					if (isset($to) and (filter_var($to, FILTER_VALIDATE_EMAIL))) {
						//	d($to);
						$user_mails[] = $to;
					}
				}
			}
			$scheduler = new \mw\utils\Events();
			// schedule a global scope function:

			if (!empty($user_mails)) {
				array_unique($user_mails);
				foreach ($user_mails as $value) {
					//\mw\email\Sender::send($value,$mail_sj,$mail_autoresp );
					$scheduler -> registerShutdownEvent("\mw\email\Sender::send", $value, $mail_sj, $mail_autoresp);

				}
			}

		}
	}

	return ($save);

}
