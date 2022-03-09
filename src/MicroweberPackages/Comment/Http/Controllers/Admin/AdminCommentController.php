<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/10/2020
 * Time: 4:49 PM
 */

namespace MicroweberPackages\Comment\Http\Controllers\Admin;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Notification;
use MicroweberPackages\App\Http\Controllers\AdminController;
use MicroweberPackages\Comment\Models\Comment;
use MicroweberPackages\Comment\Events\NewComment;
use MicroweberPackages\Comment\Notifications\NewCommentNotification;
use MicroweberPackages\Helper\HTMLClean;
use MicroweberPackages\User\Models\User;
use MicroweberPackages\Utils\Mail\MailSender;

class AdminCommentController extends AdminController
{
    public function index(Request $request)
    {
        $contents = $this->getComments($request);

        return $this->view('comment::admin.comments.index', ['contents' => $contents]);
    }

    public function getComments(Request $request)
    {
        $contents = Comment::groupBy(['rel_id', 'rel_type']);
        $filter = $request->all();

        if (!empty($filter)) {
            $contents = $contents->filter($filter);
        }

        $contents = $contents->paginate($request->get('limit', 30))
            ->appends($request->except('page'));

        foreach ($contents as $content) {
            $content->allComments = Comment::where('rel_type', $content['rel_type'])
                ->where('rel_id', $content['rel_id'])
                ->get();
        }

        return $contents;
    }

    public function saveCommentEdit(Request $request)
    {

        $is_del = false;
        $table = 'comments';
        mw_var('FORCE_SAVE', $table);

        $data = $request->all();
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
        if (!isset($data['id']) and !isset($data['is_moderated'])) {
            $data['is_moderated'] = 1;
        } else {
            $require_moderation = get_option('require_moderation', 'comments');
            if ($require_moderation != 'y') {
                $data['is_moderated'] = 1;
            }
        }
        if (isset($data['action']) and isset($data['id'])) {
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

                    $this->__report_for_spam($data['id']);

                    break;

                case 'delete' :
                    $is_del = true;
                    $del = mw()->database_manager->delete_by_id($table, $id = intval($data['id']), $field_name = 'id');

                    break;

                default :
                    break;
            }


        } else {
            if (!isset($data['id'])) {
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
            }
        }

        if ($is_del) {
            return (new JsonResource($data))->response();
        }

        if (!isset($data['comment_body'])) {
            $data['comment_body'] = '';
        }

        $comment_body = $data['comment_body'];

        $cleanHtml = new HTMLClean();
        $comment_body = $cleanHtml->onlyTags($comment_body);

        if (!empty($comment_body) and !empty($data['format']) and $data['format'] == 'markdown') {
            $comment_body = Markdown::convertToHtml($comment_body);
        }

        $data['comment_body'] = $comment_body;
        $data['allow_html'] = '1';

        $saved_data_id = mw()->database_manager->save($table, $data);

        $get_comment = get_comments("single=1&id=" . $saved_data_id);

        return (new JsonResource($get_comment))->response();

    }


    private function __report_for_spam($comment_id)
    {
        if (defined("MW_UNIT_TEST")) {
            return true;
        }

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
            $http = new \MicroweberPackages\Utils\Http\Http();
            $http->url($report_url);
            $http->set_timeout(10);
            return $http->post($report);

        }
    }
}
