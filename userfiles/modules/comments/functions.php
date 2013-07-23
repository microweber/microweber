<?php
if (!defined("MODULE_DB_COMMENTS")) {
	define('MODULE_DB_COMMENTS', MW_TABLE_PREFIX . 'comments');
}

action_hook('mw_admin_dashboard_quick_link', 'mw_print_admin_dashboard_comments_btn');

function mw_print_admin_dashboard_comments_btn() {
	$active = url_param('view');
	$cls = '';
	if ($active == 'comments') {
		$cls = ' class="active" ';
	}
	$notif_html = '';
	$notif_count = \mw\Notifications::get('module=comments&is_read=n&count=1');
	if ($notif_count > 0) {
		$notif_html = '<sup class="mw-notif-bubble">' . $notif_count . '</sup>';
	}
	print '<li' . $cls . '><a href="' . admin_url() . 'view:comments"><span class="ico icomment">' . $notif_html . '</span><span>Comments</span></a></li>';
}

//action_hook('mw_admin_settings_menu', 'mw_print_admin_comments_settings_link');

function mw_print_admin_comments_settings_link() {
	$active = url_param('view');
	$cls = '';
	if ($active == 'comments') {
		$cls = ' class="active" ';
	}
	$notif_html = '';
$mname = module_name_encode('comments/settings');
	print "<li><a class=\"item-".$mname."\" href=\"#option_group=".$mname."\">Comments</a></li>";

	//$notif_count = \mw\Notifications::get('module=comments&is_read=n&count=1');
	/*if ($notif_count > 0) {
		$notif_html = '<sup class="mw-notif-bubble">' . $notif_count . '</sup>';
	}*/
	//print '<li' . $cls . '><a href="' . admin_url() . 'view:comments"><span class="ico icomment">' . $notif_html . '</span><span>Comments</span></a></li>';
}





/**
 * mark_comments_as_old

 */
api_expose('mark_comments_as_old');

function mark_comments_as_old($data) {

	only_admin_access();

	if (isset($data['content_id'])) {
		$table = MODULE_DB_COMMENTS;
		mw_var('FORCE_SAVE', $table);
		$data['is_new'] = 'y';
		$get_comm = get_comments($data);
		if (!empty($get_comm)) {
			foreach ($get_comm as $get_com) {
				$upd = array();
				$upd['is_new'] = 'n';

				$upd['id'] = $get_com['id'];
				$upd['rel'] = 'content';
				$upd['rel_id'] = db_escape_string($data['content_id']);
				save_data($table, $upd);
			}
		}
		return $get_comm;

	}

}

/**
 * post_comment

 */
api_expose('post_comment');

function post_comment($data) {

	$adm = is_admin();

	$table = MODULE_DB_COMMENTS;
	mw_var('FORCE_SAVE', $table);

	if (isset($data['id'])) {
		if ($adm == false) {
			error('Error: Only admin can edit comments!');
		}
	}

	if (isset($data['action']) and isset($data['id'])) {
		if ($adm == false) {
			error('Error: Only admin can edit comments!');
		} else {
			$action = strtolower($data['action']);

			switch ($action) {
				case 'publish' :
				$data['is_moderated'] = 'y';

				break;
				case 'unpublish' :
				$data['is_moderated'] = 'n';

				break;
				case 'spam' :
				$data['is_moderated'] = 'n';

				break;

				case 'delete' :
				$del = db_delete_by_id($table, $id = intval($data['id']), $field_name = 'id');
				return $del;
				break;

				default :
				break;
			}

			// d();
		}
	} else {

		if (!isset($data['rel'])) {
			return array('error' => 'Error: invalid data');
		}
		if (!isset($data['rel_id'])) {
			return array('error' => 'Error: invalid data');
		} else {
			if (trim($data['rel_id']) == '') {
				return array('error' => 'Error: invalid data');
			}
		}

		if (!isset($data['captcha'])) {
			return array('error' => 'Please enter the captcha answer!');
		} else {
			$cap = session_get('captcha');

			if ($cap == false) {
				return array('error' => 'You must load a captcha first!');
			}
			if (intval($data['captcha']) != ($cap)) {
				//     d($cap);
				if ($adm == false) {
					return array('error' => 'Invalid captcha answer!');
				}
			}
		}
	}
	if (!isset($data['id']) and isset($data['comment_body'])) {

		if (!isset($data['comment_email']) and user_id() == 0) {
			return array('error' => 'You must type your email or be logged in order to comment.');
		}

		$data['from_url'] = curent_url(1);

	}

	if ($adm == true and !isset($data['id']) and !isset($data['is_moderated'])) {
		$data['is_moderated'] = 'y';
	} else {
		$require_moderation = get_option('require_moderation', 'comments');
		if ($require_moderation != 'y') {
			$data['is_moderated'] = 'y';
		}
	}

	// d( $require_moderation);

	$saved_data = save_data($table, $data);




	if (!isset($data['id']) and isset($data['comment_body'])) {


		$notif = array();
		$notif['module'] = "comments";
		$notif['rel'] = $data['rel'];
		$notif['rel_id'] = $data['rel_id'];
		$notif['title'] = "You have new comment";
		$notif['description'] = "New comment is posted on " . curent_url(1);
		$notif['content'] = character_limiter($data['comment_body'], 800);
		\mw\Notifications::save($notif);

		$email_on_new_comment = get_option('email_on_new_comment', 'comments') == 'y';
		$email_on_new_comment_value = get_option('email_on_new_comment_value', 'comments');

		if ($email_on_new_comment == true) {
			$subject = "You have new comment";
			$data2 = $data;
			unset($data2['rel']);
			unset($data2['rel_id']);
			$data3 = array();
			foreach ($data2 as $key => $value) {
				$key2 = str_ireplace('comment_', ' ', $key);
				if($key2 == 'body'){
				$key2 = 'text';
				}

				$data3[$key2] = nl2br($value);
			}


			$message = "Hi, <br/> You have new comment posted on " . curent_url(1) . ' <br /> ';
			$message .= "IP:" . USER_IP . ' <br /> ';
			$message .=array_pp($data3);
			\mw\email\Sender::send($email_on_new_comment_value, $subject, $message, 1);
		}



	}




	return $saved_data;
}

function get_comments($params) {
	$params2 = array();

	if (is_string($params)) {
		$params = parse_str($params, $params2);
		$params = $params2;
	}
	if (isset($params['content_id'])) {
		$params['rel'] = 'content';
		$params['rel_id'] = db_escape_string($params['content_id']);

	}

	$table = MODULE_DB_COMMENTS;
	$params['table'] = $table;

	$comments = get($params);

	if(isarr($comments)){
		$i = 0;
		foreach ($comments as $item) {
			if( isset($item['created_by']) and intval($item['created_by']) > 0 and ($item['comment_name'] == false or $item['comment_name'] == '')){
				$comments[$i]['comment_name'] = user_name($item['created_by']);
			}
			$i++;
		}
	}








	return $comments;
}
