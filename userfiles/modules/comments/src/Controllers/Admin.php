<?php


namespace Microweber\Comments\Controllers;

use Microweber\View;


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

        only_admin_access();

    }


    function index($params)
    {

        $view_file = $this->views_dir . 'admin.php';
        $view = new View($view_file);
        $view->assign('params', $params);

        return $view->display();
    }


    function comments_list($params)
    {


        $data = array(
            'content_id' => $params['content_id']
        );

        if (isset($params['search-keyword']) and $params['search-keyword']) {
            $kw = $data['keyword'] = $params['search-keyword'];
            $data['search_in_fields'] = 'comment_name,comment_body,comment_email,comment_website,from_url,comment_subject';
        }


        $comments = $postComments = get_comments($data);

        $content = get_content_by_id($params['content_id']);

        $content_id = $params['content_id'];


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

        $keyword = false;
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
        $comments_data['group_by'] = 'rel_id,rel_type';
        $comments_data['order_by'] = 'created_at desc';

        $data = get_comments($comments_data);


        $view_file = $this->views_dir . 'manage.php';


        $view = new View($view_file);
        $view->assign('params', $params);
        $view->assign('kw', $keyword);
        $view->assign('data', $data);

        return $view->display();
    }


}
