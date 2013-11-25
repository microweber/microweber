<?php
if (!defined("MODULE_DB_COMMENTS")) {
    define('MODULE_DB_COMMENTS', MW_TABLE_PREFIX . 'comments');
}

event_bind('mw_admin_dashboard_quick_link', 'mw_print_admin_dashboard_comments_btn');

function mw_print_admin_dashboard_comments_btn()
{
    $active = url_param('view');
    $cls = '';
    if ($active == 'comments') {
        $cls = ' class="active" ';
    }
    $notif_html = '';
    $notif_count = mw('Microweber\Notifications')->get('module=comments&is_read=n&count=1');
    if ($notif_count > 0) {
        $notif_html = '<sup class="mw-notif-bubble">' . $notif_count . '</sup>';
    }
    print '<li' . $cls . '><a href="' . admin_url() . 'view:comments"><span class="ico icomment">' . $notif_html . '</span><span>Comments</span></a></li>';
}

//event_bind('mw_admin_settings_menu', 'mw_print_admin_comments_settings_link');

function mw_print_admin_comments_settings_link()
{
    $active = url_param('view');
    $cls = '';
    if ($active == 'comments') {
        $cls = ' class="active" ';
    }
    $notif_html = '';
    $mname = module_name_encode('comments/settings');
    print "<li><a class=\"item-" . $mname . "\" href=\"#option_group=" . $mname . "\">Comments</a></li>";

}


/**
 * mark_comments_as_old

 */
api_expose('mark_comments_as_old');

function mark_comments_as_old($data)
{

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
                $upd['rel_id'] = mw('db')->escape_string($data['content_id']);
                mw('db')->save($table, $upd);
            }
        }
        return $get_comm;

    }

}

/**
 * post_comment

 */
api_expose('post_comment');

function post_comment($data)
{

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
                    $del = mw('db')->delete_by_id($table, $id = intval($data['id']), $field_name = 'id');
                    return array('success' => 'Deleted comment with id:' . $id);
                    return $del;
                    break;

                default :
                    break;
            }

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
            $cap = mw('user')->session_get('captcha');

            if ($cap == false) {
                return array('error' => 'You must load a captcha first!');
            }
            if (intval($data['captcha']) != ($cap)) {
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

        $data['from_url'] = mw('url')->current(1);

    }

    if ($adm == true and !isset($data['id']) and !isset($data['is_moderated'])) {
        $data['is_moderated'] = 'y';
    } else {
        $require_moderation = get_option('require_moderation', 'comments');
        if ($require_moderation != 'y') {
            $data['is_moderated'] = 'y';
        }
    }

    if (isset($data['comment_body'])) {
        $comment_body = ($data['comment_body']);
    }

    $saved_data = mw('db')->save($table, $data);


    if (!isset($data['id']) and isset($data['comment_body'])) {


        $notif = array();
        $notif['module'] = "comments";
        $notif['rel'] = $data['rel'];
        $notif['rel_id'] = $data['rel_id'];
        $notif['title'] = "You have new comment";
        $notif['description'] = "New comment is posted on " . mw('url')->current(1);
        $notif['content'] = mw('format')->limit($data['comment_body'], 800);
        mw('Microweber\Notifications')->save($notif);

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
                if ($key2 == 'body') {
                    $key2 = 'text';
                }

                $data3[$key2] = nl2br($value);
            }


            $message = "Hi, <br/> You have new comment posted on " . mw('url')->current(1) . ' <br /> ';
            $message .= "IP:" . MW_USER_IP . ' <br /> ';
            $message .= mw('format')->array_to_ul($data3);
            \Microweber\email\Sender::send($email_on_new_comment_value, $subject, $message, 1);
        }


    }


    return $saved_data;
}

function get_comments($params)
{
    $params2 = array();

    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $params2;
    }
    if (isset($params['content_id'])) {
        $params['rel'] = 'content';
        $params['rel_id'] = mw('db')->escape_string($params['content_id']);

    }

    $table = MODULE_DB_COMMENTS;
    $params['table'] = $table;

    if (isset($params['category'])) {
        if (!defined("MW_DB_TABLE_TAXONOMY_ITEMS")) {
            define('MW_DB_TABLE_TAXONOMY_ITEMS', MW_TABLE_PREFIX . 'categories_items');
        }
        $table_cat_items = MW_DB_TABLE_TAXONOMY_ITEMS;
        //$cat_items = get_category_items($params['category']);
        $cat = intval($params['category']);
        $select = " SELECT * ";
        $limit = " limit 30 ";
        if (isset($params['count'])) {
            $select = " SELECT  count(*) as qty ";
            $limit = "   ";

        }
        $sql = $select . " FROM $table
        LEFT JOIN $table_cat_items ON
            $table_cat_items.rel = $table.rel
            where $table_cat_items.parent_id=$cat
            and $table_cat_items.rel_id =$table.rel_id $limit";

        $comments = mw('db')->query($sql, 'comments_in_category_'.crc32($sql), 'comments/global');

        unset($params['category']);
    } else {
        $comments = get($params);
    }


    $date_format = get_option('date_format', 'website');
    if ($date_format == false) {
        $date_format = "Y-m-d H:i:s";
    }
    $aj = mw('url')->is_ajax();

    if (is_array($comments)) {



        $i = 0;
        foreach ($comments as $item) {
            if ($aj == true) {
                //  $item =  mw('format')->clean_html($item);

            }
            if (isset($params['count'])) {
                if (isset($item['qty'])) {
                    return $item['qty'];
                }

            }
            if (isset($item['created_by']) and intval($item['created_by']) > 0 and ($item['comment_name'] == false or $item['comment_name'] == '')) {
                $comments[$i]['comment_name'] = user_name($item['created_by']);
            }
            if (isset($item['created_on']) and  trim($item['created_on']) != '') {
                $comments[$i]['created_on'] = date($date_format, strtotime($item['created_on']));
            }
            if (isset($item['updated_on']) and  trim($item['updated_on']) != '') {
                $comments[$i]['updated_on'] = date($date_format, strtotime($item['updated_on']));
            }
            if (isset($item['comment_body']) and ($item['comment_body'] != '')) {
                $comments[$i]['comment_body'] = mw('format')->autolink($item['comment_body']);
            }
            $i++;
        }
    }


    return $comments;
}
