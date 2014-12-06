<?php
if (!defined("MODULE_DB_COMMENTS")) {
    define('MODULE_DB_COMMENTS', get_table_prefix() . 'comments');
}
//
//mw()->content->comments=(function(){
//	return $this->morphMany('Comments', 'rel');
//dd(__FILE__);	
//	
//});
//mw()->content->comments=(function(){
//	return $this->morphMany('Comments', 'rel');
//dd(__FILE__);	
//	
//});


Filter::bind('comments',function($params, $app){
	return $app->morphMany('Comments', 'rel');
 	
	
}); 

//
//$comm = Content::with(array('comments' => function($q)
// {
//  // $q->where('comments.rel_id','=','content.id')->groupBy('id')->groupBy('comments.rel_id');; 
//   
// $q->where('comments.rel_id','=','content.id')->whereNotNull('comments.rel_id')->groupBy('id'); 
//   
// }))->get()->toArray();
//
// $comm = Content::with('comments')->get()->toArray();
//dd( $comm);
// $comm = Content::find(1)->comments()->get();
//dd( $comm);


//	$post_params = array();
// $post_params['comments'] = true;
//  $post_params['one'] = true;
//$post_params['order_by'] = 'id desc';
//$content = get_content($post_params);
//
//dd($content);

//Content::comments=(function(){
//	return $this->morphMany('Comments', 'rel');
//dd(__FILE__);	
//	
//});

//mw()->content->comments->get();

//mw()->database->custom_filter('comments', function(){
//	
//dd(__FILE__);	
//	
//});


//mw()->bind('conne', function() 
//{
//    return new Comments();
//});
 //mw()->content->find(1)->comments()->get();
// $comm = Content::find(1)->comments()->get();
//dd( $comm);




mw()->database->custom_filter('comments', function($query,$param,$table){
	//
//	$app = $app->morphMany('comments', 'rel');
//	
//	$query = $query->with('comments');
//		return $query;
//		
//	dd($query);
//dd(__FILE__);	
//	
});

//	$post_params = array();
// $post_params['comments'] = true;
//  $post_params['one'] = true;
//$post_params['order_by'] = 'id desc';
//$content = get_content($post_params);
//
//dd($content );





 




event_bind('module.content.manager.item', 'mw_print_admin_post_list_comments_counter');








function mw_print_admin_post_list_comments_counter($item)
{
    
	
	if (isset($item['id'])) {

        $new = get_comments('count=1&is_moderated=n&content_id=' . $item['id']);
        if ($new > 0) {
            $have_new = 1;
        } else {
            $have_new = 0;
            $new = get_comments('count=1&content_id=' . $item['id']);
        }
        $comments_link = admin_url('view:comments') . '/#content_id=' . $item['id'];

        if ($have_new) {

        }
        $link = "<a class='comments-bubble' href='{$comments_link}'  title='{$new}'>";
        $link .= "<span class='mw-icon-comment'></span><span class='comment-number'>{$new}</span>";
        $link .= "</a>";
        print $link;
    }


}


event_bind('module.content.edit', 'mw_print_admin_post_comments_counter_quick_list');

function mw_print_admin_post_comments_counter_quick_list($item)
{

  
    if (isset($item['id'])) {
        $new = get_comments('count=1&rel_type=content&rel_id=' . $item['id']);
        if ($new > 0) {
            $btn = array();
            $btn['title'] = 'Comments';
            $btn['class'] = 'mw-icon-comment';
            $btn['html'] = '<module type="comments/comments_for_post" no_post_head="true" content_id=' . $item['id'] . '  />';
          //  mw()->modules->ui('content.edit.tabs', $btn);
        }
    }
}


event_bind('mw.admin.dashboard.links', 'mw_print_admin_dashboard_comments_btn');

function mw_print_admin_dashboard_comments_btn()
{
   
    print __FILE__.__LINE__;
	return;
    $admin_dashboard_btn = array();
    $admin_dashboard_btn['view'] = 'comments';

    $admin_dashboard_btn['icon_class'] = 'mw-icon-comment';
    $notif_html = '';
    $notif_count = mw()->notifications->get('module=comments&is_read=n&count=1');

    if ($notif_count > 0) {
        $notif_html = '<sup class="mw-notification-count">' . $notif_count . '</sup>';
    }
    $admin_dashboard_btn['text'] = _e("Comments", true) . $notif_html;
    mw()->ui->admin_dashboard_menu($admin_dashboard_btn);


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
                $upd['rel_type'] = 'content';
                $upd['rel_id'] = mw()->db->escape_string($data['content_id']);
                mw()->db->save($table, $upd);
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

    if (defined("MW_API_CALL")) {
        if (!$adm) {
            $validate_token = mw()->user_manager->csrf_validate($data);
            if ($validate_token == false) {
                return array('error' => 'Invalid token!');
            }
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
                    $del = mw()->db->delete_by_id($table, $id = intval($data['id']), $field_name = 'id');
                    return array('success' => 'Deleted comment with id:' . $id);
                    return $del;
                    break;

                default :
                    break;
            }

        }
    } else {

        if (!isset($data['rel_type'])) {
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

            if (isset($data['module_id'])) {

                $captcha_sid = 'captcha_' . $data['module_id'];
                $cap_sid = mw()->user_manager->session_get($captcha_sid);
                if ($cap_sid != false) {
                    $cap = $cap_sid;
                }

            }
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
        $ref = mw()->url_manager->current(1);
        if ($ref != false and $ref != '') {
            $data['from_url'] = htmlentities(strip_tags(mw()->url_manager->current(1)));
        }
    }

    if ($adm == true and !isset($data['id']) and !isset($data['is_moderated'])) {
        $data['is_moderated'] = 'y';
    } else {
        $require_moderation = get_option('require_moderation', 'comments');
        if ($require_moderation != 'y') {
            $data['is_moderated'] = 'y';
        }
    }

    if (isset($data['comment_website'])) {
        $data['comment_website'] = mw()->format->clean_xss($data['comment_website']);
    }
    if (isset($data['comment_email'])) {
        $data['comment_email'] = mw()->format->clean_xss($data['comment_email']);
    }
    if (isset($data['comment_name'])) {
        $data['comment_name'] = mw()->format->clean_xss($data['comment_name']);
    }
    if (isset($data['from_url'])) {
        $data['from_url'] = mw()->format->clean_xss($data['from_url']);
    }

    $saved_data = mw()->db->save($table, $data);


    if (!isset($data['id']) and isset($data['comment_body'])) {


        $notif = array();
        $notif['module'] = "comments";
        $notif['rel_type'] = $data['rel_type'];
        $notif['rel_id'] = $data['rel_id'];
        $notif['title'] = "You have new comment";
        $notif['description'] = "New comment is posted on " . mw()->url_manager->current(1);
        $notif['content'] = mw()->format->limit($data['comment_body'], 800);
        $notf_id = mw()->notifications->save($notif);
        $data['moderate'] = admin_url('view:modules/load_module:comments/mw_notif:' . $notf_id);
        $email_on_new_comment = get_option('email_on_new_comment', 'comments') == 'y';
        $email_on_new_comment_value = get_option('email_on_new_comment_value', 'comments');

        if ($email_on_new_comment == true) {
            $subject = "You have new comment";
            $data2 = $data;
            unset($data2['rel_type']);
            unset($data2['rel_id']);
            $data3 = array();
            foreach ($data2 as $key => $value) {
                $key2 = str_ireplace('comment_', ' ', $key);
                if ($key2 == 'body') {
                    $key2 = 'text';
                }

                $data3[$key2] = nl2br($value);
            }


            $message = "Hi, <br/> You have new comment posted on " . mw()->url_manager->current(1) . ' <br /> ';
            $message .= "IP:" . MW_USER_IP . ' <br /> ';
            $message .= mw()->format->array_to_ul($data3);
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
        $params['rel_type'] = 'content';
        $params['rel_id'] = mw()->db->escape_string($params['content_id']);

    }

    $table = MODULE_DB_COMMENTS;
    $params['table'] = $table;

  /*  if (isset($params['posts_category'])) {
              //  $params['debug'] = 'content';
        //$params['no_cache'] = 'content';
       // $params['debug'] = 'content';
       // $params['no_cache'] = 'content';
        $params['filter']['posts_category'] = function ($orm, $value) {
            $categories_items_table = get_table_prefix() . 'categories_items';
            $comments_table = get_table_prefix() . 'comments';
            $orm->inner_join($categories_items_table, array($comments_table . '.rel_id', '=', $categories_items_table . '.rel_id'));
            $orm->where($categories_items_table . '.parent_id', $value);
            $orm->order_by_desc($comments_table . '.created_at');
        };  
    }
*/

    $comments = get($params);
   // print_r(mw()->orm->getLastQuery());
    $date_format = get_option('date_format', 'website');
    if ($date_format == false) {
        $date_format = "Y-m-d H:i:s";
    }
    $aj = mw()->url_manager->is_ajax();
    if (is_array($comments)) {
        $i = 0;
        foreach ($comments as $item) {
            if (isset($params['count'])) {
                if (isset($item['qty'])) {
                    return $item['qty'];
                }
            }
            if (isset($item['created_by']) and intval($item['created_by']) > 0 and ($item['comment_name'] == false or $item['comment_name'] == '')) {
                $comments[$i]['comment_name'] = user_name($item['created_by']);
            }
            if (isset($item['created_at']) and  trim($item['created_at']) != '') {
                $comments[$i]['created_at'] = date($date_format, strtotime($item['created_at']));
            }
            if (isset($item['updated_at']) and  trim($item['updated_at']) != '') {
                $comments[$i]['updated_at'] = date($date_format, strtotime($item['updated_at']));
            }
            if (isset($item['comment_body']) and ($item['comment_body'] != '')) {
                $surl = site_url();
                $item['comment_body'] = str_replace('{SITE_URL}', $surl, $item['comment_body']);
                $comments[$i]['comment_body'] = mw()->format->autolink($item['comment_body']);
            }

            if (isset($params['single'])) {
                return $comments[$i];
            }

            $i++;
        }
    }
    return $comments;
}




