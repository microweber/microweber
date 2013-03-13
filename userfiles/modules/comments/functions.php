<?
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
	print '<li' . $cls . '><a href="' . admin_url() . 'view:comments"><span class="ico icomment"></span><span>Comments</span></a></li>';
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
                case 'publish':
                    $data['is_moderated'] = 'y';

                    break;
                case 'unpublish':
                    $data['is_moderated'] = 'n';

                    break;
                case 'spam':
                    $data['is_moderated'] = 'n';

                    break;

                case 'delete':

                    $del = db_delete_by_id($table, $id = intval($data['id']), $field_name = 'id');
                    return $del;
                    break;

                default:
                    break;
            }



            // d();
        }
    } else {


        if (!isset($data['to_table'])) {
            error('Error: invalid data');
        }
        if (!isset($data['to_table_id'])) {
            error('Error: invalid data');
        } else {
            if (intval($data['to_table_id']) == 0) {
                error('Error: invalid data');
            }
        }

        if (!isset($data ['captcha'])) {
            return array('error' => 'Please enter the captcha answer!');
        } else {
            $cap = session_get('captcha');

            if ($cap == false) {
                return array('error' => 'You must load a captcha first!');
            }
            if (intval($data ['captcha']) != ($cap)) {
                //     d($cap);
                if ($adm == false) {
                    return array('error' => 'Invalid captcha answer!');
                }
            }
        }
    }
 if (!isset($data['id']) and isset($data['comment_body'])) {
		$notif = array();
			$notif['module'] = "comments";
			$notif['title'] = "You have new comment";
			$notif['description'] = "New comment is posted on ".curent_url(1);
			$notif['content'] = "New comment... ".character_limiter($data['comment_body'],80);
			post_notification($notif);
 }

    $data = save_data($table, $data);
    return $data;
}

function get_comments($params) {
    $params2 = array();

    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $params2;
    }
if (isset($params['content_id'])) {
            $params['to_table'] = 'table_content';
			$params['to_table_id'] = db_escape_string($params['content_id']);;
        }

    
  $table = MODULE_DB_COMMENTS;
    $params['table'] = $table;

    $params = get($params);
    return $params;
}