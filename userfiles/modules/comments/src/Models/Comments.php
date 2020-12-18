<?php


namespace Microweber\Comments\Models;

use Microweber\Providers\Database\Crud;
use Microweber\Utils\Http;
use Microweber\Utils\MailSender;
use Microweber\View;
use GrahamCampbell\Markdown\Facades\Markdown;


class Comments extends Crud
{

    public $app;
    public $table = 'comments';


    public function get($params = false)
    {
        $params2 = array();

        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }
        if (isset($params['content_id'])) {
            $params['rel_type'] = 'content';
            $params['rel_id'] = mw()->database_manager->escape_string($params['content_id']);

        }
        $date_format = get_option('date_format', 'website');
        if ($date_format == false) {
            $date_format = "Y-m-d H:i:s";
        }
        $table = $this->table;
        $params['table'] = $table;

        $comments = db_get($params);

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
                if (isset($item['created_at']) and trim($item['created_at']) != '') {
                    $comments[$i]['created_at_display'] = date($date_format, strtotime($item['created_at']));
                }
                if (isset($item['updated_at']) and trim($item['updated_at']) != '') {
                    $comments[$i]['updated_at_display'] = date($date_format, strtotime($item['updated_at']));
                }
                if (isset($item['comment_body']) and ($item['comment_body'] != '')) {
                    $surl = site_url();
                    $item['comment_body'] = str_replace('{SITE_URL}', $surl, $item['comment_body']);
                    $comments[$i]['comment_body'] = $item['comment_body']; // mw()->format->autolink($item['comment_body']);
                }

                if (isset($params['single'])) {

                    return $comments;
                }

                $i++;
            }
        }


        return $comments;
    }


    public function save($data)
    {

        $adm = is_admin();

        $table = MODULE_DB_COMMENTS;
        mw_var('FORCE_SAVE', $table);

        if (isset($data['id'])) {
            if ($adm == false) {
                mw_error('Error: Only admin can edit comments!');
            }
        }
        if (!isset($data['rel_type']) and isset($data['rel'])) {
            $data['rel_type'] = $data['rel'];
        }

        if (isset($data['reply_to_comment_id'])) {
            $old_comment = $this->get_by_id($data['reply_to_comment_id']);
            $data['id'] = 0;
            if (!$old_comment) {
                return array('error' => 'Error: invalid data');
            }
            if (isset($old_comment['rel_type'])) {
                $data['rel_type'] = $old_comment['rel_type'];
            }
            if (isset($old_comment['rel_id'])) {
                $data['rel_id'] = $old_comment['rel_id'];
            }


        }
        if ($adm == true and !isset($data['id']) and !isset($data['is_moderated'])) {
            $data['is_moderated'] = 1;
        } else {
            $require_moderation = get_option('require_moderation', 'comments');
            if ($require_moderation != 'y') {
                $data['is_moderated'] = 1;
            }
        }
        if (isset($data['action']) and isset($data['id'])) {
            if ($adm == false) {
                mw_error('Error: Only admin can edit comments!');
            } else {
                $action = strtolower($data['action']);

                switch ($action) {
                    case 'publish' :
                        $data['is_moderated'] = 1;
                        $data['is_spam'] = 0;


                        break;
                    case 'unpublish' :
                        $data['is_moderated'] = 0;

                        break;
                    case 'spam' :
                        $data['is_moderated'] = 0;
                        $data['is_spam'] = 1;

                        break;

                    case 'delete' :
                        $del = mw()->database_manager->delete_by_id($table, $id = intval($data['id']), $field_name = 'id');
                        return $del;
                        break;

                    default :
                        break;
                }

            }
        } else {

            if (!isset($data['rel_type'])) {
                return array('error' => 'Error: invalid data rel_type');
            }
            if (!isset($data['rel_id'])) {
                return array('error' => 'Error: invalid data rel_id');
            } else {
                if (trim($data['rel_id']) == '') {
                    return array('error' => 'Error: invalid data rel_id');
                }
            }

            if (!is_admin()) {


                $needs_terms = get_option('require_terms', 'comments') == 'y';


                if ($needs_terms) {
                    $user_id_or_email = $this->app->user_manager->id();
                    if (!$user_id_or_email) {
                        if (isset($data['comment_email'])) {
                            $user_id_or_email = $data['comment_email'];
                        }
                    }

                    if (!$user_id_or_email) {
                        $checkout_errors['comments_needs_email'] = _e('You must provide email address', true);
                    } else {
                        $terms_and_conditions_name = 'terms_comments';

                        $check_term = $this->app->user_manager->terms_check($terms_and_conditions_name, $user_id_or_email);
                        if (!$check_term) {
                            if (isset($data['terms']) and $data['terms']) {
                                $this->app->user_manager->terms_accept($terms_and_conditions_name, $user_id_or_email);
                            } else {
                                return array(
                                    'error' => _e('You must agree to terms and conditions', true),
                                    'form_data_required' => 'terms',
                                    'form_data_module' => 'users/terms'
                                );
                            }
                        }
                    }
                }


                if (!isset($data['captcha'])) {
                    return array(
                        'error' => _e('Invalid captcha answer!', true),
                        'captcha_error' => true,
                        'form_data_required' => 'captcha',
                        'form_data_module' => 'captcha'
                    );

                } else {
                    $validate_captcha = $this->app->captcha_manager->validate($data['captcha']);
                    if (!$validate_captcha) {

                        return array(
                            'error' => _e('Invalid captcha answer!', true),
                            'captcha_error' => true,
                            'form_data_required' => 'captcha',
                            'form_data_module' => 'captcha'
                        );


                    }
                }

            }


        }
        if (!isset($data['id']) and isset($data['comment_body'])) {

            if (!isset($data['comment_email']) and user_id() == 0) {
                return array('error' => 'You must type your email or be logged in order to comment.');
            }

            $data['from_url'] = mw()->url_manager->current(1);

        }

        if (!isset($data['comment_body'])) {
        	$data['comment_body'] = '';
        }

		$comment_body = $data['comment_body'];

		// Claer HTML
		$comment_body = $this->app->format->clean_html($comment_body);

		// Clear XSS
		$evil = ['(?<!\w)on\w*',   'xmlns', 'formaction',   'xlink:href', 'FSCommand', 'seekSegmentTime'];
		$comment_body =  $this->app->format->clean_xss($comment_body, true, $evil, 'removeEvilAttributes');

		$comment_body = Markdown::convertToHtml($comment_body);

		$data['comment_body'] = $comment_body;
		$data['allow_html'] = '1';
		$data['allow_scripts'] = '1';

		$saved_data_id = mw()->database_manager->save($table, $data);


        if (!isset($data['id']) and isset($data['comment_body'])) {


            $notif = array();
            $notif['module'] = "comments";
            $notif['rel_type'] = $data['rel_type'];
            $notif['rel_id'] = $data['rel_id'];
            $notif['title'] = "You have new comment";
            $notif['description'] = "New comment is posted on " . mw()->url_manager->current(1);
            $notif['content'] = mw('format')->limit($data['comment_body'], 800);
            mw()->notifications_manager->save($notif);

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
                $message .= mw('format')->array_to_ul($data3);

                $sender = new MailSender();
                $sender->setEmailTo($email_on_new_comment_value);
                $sender->setEmailSubject($subject);
                $sender->setEmailMessage($message);
                $sender->setEmailHostnameToSubject(1);
                $sender->send();
            }


        }

        $get_comment = get_comments("single=1&id=" . $saved_data_id);

        if (isset($get_comment['is_subscribed_for_notification']) && isset($get_comment['is_sent_email'])) {

        	if ($get_comment['action'] == 'publish' && $get_comment['is_subscribed_for_notification'] == 1 && $get_comment['is_sent_email'] == 0) {

		        // Send notification
        		if (is_numeric($saved_data_id)) {
        			$emailJob = (new  \Microweber\Comments\Jobs\JobSendMailNotificationOnComment($saved_data_id))->onQueue('processing');
		        	\Queue::later(5, $emailJob);
		        }

	        }
        }

        return $saved_data_id;
    }

    public function mark_as_spam($data)
    {

        only_admin_access();
        if (isset($data['comment_id'])) {
            $s = array();
            $s['id'] = $data['comment_id'];
            $s['is_moderated'] = 0;
            $s['is_spam'] = 1;
            $s['table'] =  $this->table;

            $s = mw()->database_manager->save($s);
            if ($s) {
                $this->__report_for_spam($s);
            }


        }


    }

    public function mark_as_old($data)
    {

        only_admin_access();

        if (isset($data['content_id'])) {
            $table = MODULE_DB_COMMENTS;
            mw_var('FORCE_SAVE', $table);
            $data['is_new'] = 1;
            $get_comm = get_comments($data);
            if (!empty($get_comm)) {
                foreach ($get_comm as $get_com) {
                    $upd = array();
                    $upd['is_new'] = 0;

                    $upd['id'] = $get_com['id'];
                    $upd['rel_type'] = 'content';
                    $upd['rel_id'] = mw()->database_manager->escape_string($data['content_id']);
                    mw()->database_manager->save($table, $upd);
                }
            }
            return $get_comm;

        }

    }


    private function __report_for_spam($comment_id)
    {

        $comment = $this->get_by_id($comment_id);
        $report_url = 'https://spamchecker.microweberapi.com/';

        if ($comment) {
            $report = array();
            $report['site_url'] = site_url();
            $report['from_url'] = $comment['from_url'];
            $report['is_spam'] = 1;
            if (isset($comment['user_ip']) and $comment['user_ip']) {
                $report['ip'] = trim($comment['user_ip']);
            }
            if (isset($comment['comment_email']) and $comment['comment_email']) {
                $report['email'] = trim($comment['comment_email']);
            }
            if (isset($comment['created_by']) and $comment['created_by']) {
                $report['is_logged'] = true;
                $report['user_id'] = $comment['created_by'];
            }
            if (isset($comment['comment_name']) and $comment['comment_name']) {
                $report['comment_name'] = $comment['comment_name'];
            }
            if (isset($comment['comment_body']) and $comment['comment_body']) {
                $report['comment_body'] = $comment['comment_body'];
            }
            if (isset($comment['comment_website']) and $comment['comment_website']) {
                $report['comment_website'] = $comment['comment_website'];
            }
            if (isset($comment['comment_subject']) and $comment['comment_subject']) {
                $report['comment_subject'] = $comment['comment_subject'];
            }

            if (isset($comment['rel_type']) and $comment['rel_type']) {
                $report['rel_type'] = $comment['rel_type'];
            }
            if (isset($comment['rel_id']) and $comment['rel_id']) {
                $report['rel_id'] = $comment['rel_id'];
            }
            $http = new Http();
            $http->url($report_url);
            $http->set_timeout(10);
            return $http->post($report);

        }
    }

}
