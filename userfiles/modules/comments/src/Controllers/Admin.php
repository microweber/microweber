<?php


namespace Microweber\Comments\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\Comment\Http\Controllers\Admin\AdminCommentController;
use MicroweberPackages\View\View;


class Admin
{
    public $app = null;
    public $views_dir = 'views';


    function __construct($app = null)
    {


        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }
        $this->views_dir = dirname(__DIR__) . DS . 'views' . DS;

    }


    function index($params)
    {
        if (!user_can_access('module.comments.index')) {
            return;
        }

        $view_file = $this->views_dir . 'admin.php';
        $view = new View($view_file);
        $view->assign('params', $params);

        return $view->display();
    }


    function comments_list($params)
    {

        if (!user_can_access('module.comments.index')) {
            return;
        }

        if (!isset($params['content_id'])) {

            if (isset($params['rel_id']) and isset($params['rel_type'])) {
                $data = array(
                    'rel_id' => $params['rel_id'],
                    'rel_type' => $params['rel_type'],

                );
            }


        } else {
            $data = array(
                'content_id' => $params['content_id']
            );

        }


        if (isset($params['search-keyword']) and $params['search-keyword']) {
            $kw = $data['keyword'] = $params['search-keyword'];
            $data['search_in_fields'] = 'comment_name,comment_body,comment_email,comment_website,from_url,comment_subject';
        }

        $data['order_by'] = 'created_at desc';

        $comments = $postComments = get_comments($data);
        if (isset($params['content_id'])) {
            $content = get_content_by_id($params['content_id']);
            $content_id = $params['content_id'];
        } else {
            $content = false;
            $content_id = false;
        }

        $moderation_is_required = get_option('require_moderation', 'comments') == 'y';

        $view_file = $this->views_dir . 'comments_list.php';
        $view = new View($view_file);
        $view->assign('params', $params);
        $view->assign('comments', $comments);
        $view->assign('content_id', $content_id);
        $view->assign('content', $content);
        $view->assign('moderation_is_required', $moderation_is_required);

        return $view->display();
    }


    function comment_item($params)
    {
        if (!user_can_access('module.comments.index')) {
            return;
        }

        $data = array(
            'id' => $params['comment_id'],
            'single' => true,
        );

        $comment = get_comments($data);


        if (!$comment) {
            return;
        }


        $view_file = $this->views_dir . 'comment_item.php';
        $view = new View($view_file);
        $view->assign('params', $params);
        $view->assign('comment', $comment);


        return $view->display();


    }

    function manage($params)
    {
        if (!user_can_access('module.comments.index')) {
            return;
        }

        $keyword = false;
        $paging_param = 'comments_page';
        $current_page_from_url = $this->app->url_manager->param($paging_param);

        $comments_data = array();
        $comments_data['cache_group'] = 'comments/global';
        if (isset($params['search-keyword'])) {
            $comments_data['keyword'] = $params['search-keyword'];
        }
        if (isset($params['content_id'])) {
            $comments_data['content_id'] = $params['content_id'];
        }

        if (isset($params['limit'])) {
            $comments_data['limit'] = intval($params['limit']);
        }


        if (isset($params['content_id'])) {
            $comments_data['content_id'] = $params['content_id'];
        } else {
            if (isset($params['rel_type'])) {
                $comments_data['rel_type'] = $params['rel_type'];
            }
            if (isset($params['rel_id'])) {
                $comments_data['rel_id'] = $params['rel_id'];
            }

        }

        $keyword = false;

        if (isset($params['search-keyword']) and $params['search-keyword']) {
            $keyword = $comments_data['keyword'] = $params['search-keyword'];
            $comments_data['search_in_fields'] = 'comment_name,comment_body,comment_email,comment_website,from_url,comment_subject';
        }
        if ($keyword) {
            $keyword = strip_tags($keyword);
            $keyword = addslashes($keyword);
        }


        if (!$keyword and $current_page_from_url) {
            $comments_data['current_page'] = $current_page_from_url;

        }


        $comments_data['group_by'] = 'rel_id,rel_type';
      //  $comments_data['order_by'] = 'updated_at desc';
        $comments_data['order_by'] = 'created_at desc';


        $request = new Request();
        $request->merge($comments_data);

        $comments_controller = app()->make(AdminCommentController::class);
        $comments_controller_data = $comments_controller->getComments($request);

        return view('comment::admin.comments.partials.list', ['contents' => $comments_controller_data,'module_view'=>1]);

//       // $data = get_comments($comments_data);
//        $data = get_comments($comments_data);
//        //dd($data);
//        $comments_data['page_count'] = true;
//
//        $page_count = get_comments($comments_data);
//
//
//        $view_file = $this->views_dir . 'manage.php';
//
//
//        $view = new View($view_file);
//        $view->assign('params', $params);
//        $view->assign('kw', $keyword);
//        $view->assign('data', $data);
//        $view->assign('page_count', $page_count);
//        $view->assign('paging_param', $paging_param);
//
//        return $view->display();
    }


}
