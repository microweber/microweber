<?php


namespace Microweber\Comments\Models;

use Illuminate\Support\Facades\Notification;
use MicroweberPackages\Comment\Events\NewComment;
use MicroweberPackages\Comment\Notifications\NewCommentNotification;
use MicroweberPackages\Database\Crud;
use Microweber\Utils\Http;
use MicroweberPackages\User\Models\User;
use MicroweberPackages\Utils\Mail\MailSender;
use MicroweberPackages\View\View;
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




}
