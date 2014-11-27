<?php


namespace Comments;


if (!defined("MODULE_DB_COMMENTS")) {
    define('MODULE_DB_COMMENTS', get_table_prefix() . 'comments');
}

class Api {

    static function get($params) {
        $params2 = array();

        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }
        if (isset($params['content_id'])) {
            $params['rel'] = 'content';
            $params['rel_id'] = mw()->database_manager->escape_string($params['content_id']);

        }

        $table = MODULE_DB_COMMENTS;
        $params['table'] = $table;

        $comments = \get($params);

        if(is_array($comments)){
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



   static function save($data) {

        $adm = is_admin();

        $table = MODULE_DB_COMMENTS;
        mw_var('FORCE_SAVE', $table);

        if (isset($data['id'])) {
            if ($adm == false) {
                mw_error('Error: Only admin can edit comments!');
            }
        }

        if (isset($data['action']) and isset($data['id'])) {
            if ($adm == false) {
                mw_error('Error: Only admin can edit comments!');
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
                        $del = \mw()->database_manager->delete_by_id($table, $id = intval($data['id']), $field_name = 'id');
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
                $cap = mw()->user_manager->session_get('captcha');

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

            $data['from_url'] = mw()->url->current(1);

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

        $saved_data = \mw()->database_manager->save($table, $data);




        if (!isset($data['id']) and isset($data['comment_body'])) {


            $notif = array();
            $notif['module'] = "comments";
            $notif['rel'] = $data['rel'];
            $notif['rel_id'] = $data['rel_id'];
            $notif['title'] = "You have new comment";
            $notif['description'] = "New comment is posted on " . mw()->url->current(1);
            $notif['content'] = mw('format')->limit($data['comment_body'], 800);
            mw()->notifications_manager->save($notif);

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


                $message = "Hi, <br/> You have new comment posted on " . mw()->url->current(1) . ' <br /> ';
                $message .= "IP:" . MW_USER_IP . ' <br /> ';
                $message .=mw('format')->array_to_ul($data3);
                \Microweber\email\Sender::send($email_on_new_comment_value, $subject, $message, 1);
            }



        }




        return $saved_data;
    }



    static function mark_as_old($data) {

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
                    $upd['rel_id'] = mw()->database_manager->escape_string($data['content_id']);
                    \mw()->database_manager->save($table, $upd);
                }
            }
            return $get_comm;

        }

    }
}