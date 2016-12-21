<?php


namespace rating;


class Controller
{
    var $model = null;

    function __construct($app = null)
    {
        $this->model = new Model($app);
    }

    function index($params)
    {
        $require_comment = false;
        $rel_type = 'content';
        $cont_id = false;
        if (isset($params['content_id'])) {
            $cont_id = $params['content_id'];
        } elseif (isset($params['content-id'])) {
            $cont_id = $params['content-id'];
        } else if (isset($params['rel_id'])) {
            $cont_id = $params['rel_id'];
        } elseif (isset($params['rel-id'])) {
            $cont_id = $params['rel-id'];
        }

        if (isset($params['rel_type'])) {
            $rel_type = $params['rel_type'];
        } elseif (isset($params['rel-type'])) {
            $rel_type = $params['rel-type'];
        }

        if (isset($params['comment'])) {
            $require_comment = $params['comment'];
        }

        if ($cont_id == false) {
            return;
        }

        $rating = 0;

        $rel_id = trim($cont_id);

        $get = array();
        $get['rel_type'] = $rel_type;
        $get['rel_id'] = $rel_id;
        $get['avg'] = 'rating';
        $rating = $this->model->get($get);


//        if ($rating_points > 0 and $total_of_ratings > 0) {
//            $rating = $rating_points / $total_of_ratings;
//        }


        $module_template = false;

        if ($module_template == false and isset($params['template'])) {
            $module_template = $params['template'];
        }

        if ($module_template != false and trim($module_template) != '' and trim(strtolower($module_template) != 'none')) {
            $template_file = module_templates('rating', $module_template);
        } else {
            $template_file = module_templates('rating', 'default');
        }


        if ($template_file and is_file($template_file)) {
            $view_file = $template_file;
            //$view_file = __DIR__ . DS . 'views' . DS . 'star_rating.php';
            $view = new \Microweber\View($view_file);
            $view->assign('ratings', intval($rating));
            $view->assign('rel_type', $rel_type);
            $view->assign('rel_id', $rel_id);
            $view->assign('require_comment', $require_comment);
            $view->assign('params', $params);
            return $view->display();
        }


    }

    function simple_rating($item)
    {


        $rating = 0;
        $rel_type = 'content';
        $rel_id = $item['id'];

        $get = array();
        $get['rel_type'] = $rel_type;
        $get['rel_id'] = $rel_id;
        $get['sum'] = 'rating';
        $get['group_by'] = 'rel_id,rel_type';
        $get['single'] = true;
        $get = $this->model->get($get);
        if (!isset($get['rating'])) {
            $rating_points = 0;
        } else {
            $rating_points = $get['rating'];
        }


        $get = array();
        $get['rel_type'] = $rel_type;
        $get['rel_id'] = $rel_id;
        $get['count'] = true;
        $total_of_ratings = $this->model->get($get);

        $view_file = __DIR__ . DS . 'views' . DS . 'simple_rating.php';
        $view = new \Microweber\View($view_file);
        if ($rating_points > 0 and $total_of_ratings > 0) {
            $rating = $rating_points / $total_of_ratings;
        }

        $view->assign('ratings', intval($rating));
        $view->assign('rel_type', $rel_type);
        $view->assign('rel_id', $rel_id);

        return $view->display();

    }


    function comment_rating($item)
    {

        $rating = 0;
        $rel_type = 'comment';
        $rel_id = $item['id'];

        $get = array();
        $get['rel_type'] = $rel_type;
        $get['rel_id'] = $rel_id;
        $get['sum'] = 'rating';
        $get['group_by'] = 'rel_id,rel_type';
        $get['single'] = true;
        $get = $this->model->get($get);
        if (!isset($get['rating'])) {
            $rating_points = 0;
        } else {
            $rating_points = $get['rating'];
        }


        $get = array();
        $get['rel_type'] = $rel_type;
        $get['rel_id'] = $rel_id;
        $get['count'] = true;
        $total_of_ratings = $this->model->get($get);

        $view_file = __DIR__ . DS . 'views' . DS . 'comment_rating.php';
        $view = new \Microweber\View($view_file);
        if ($rating_points > 0 and $total_of_ratings > 0) {
            $rating = $rating_points / $total_of_ratings;
        }

        $view->assign('ratings', intval($rating));
        $view->assign('rel_type', $rel_type);
        $view->assign('rel_id', $rel_id);

        return $view->display();

    }

    function save($item)
    {


        if (!isset($item['rel_id']) or !isset($item['rel_type'])) {
            return false;
        }

        if (!isset($item['rating'])) {
            $item['rating'] = 0;
        }

        $save = array();

        $save['rel_id'] = $item['rel_id'];
        $save['rel_type'] = $item['rel_type'];
        $save['rating'] = $item['rating'];


        if (isset($item['comment'])) {
            $save['comment'] = $item['comment'];
        }

        $check = array();
        $check['rel_id'] = $item['rel_id'];
        $check['rel_type'] = $item['rel_type'];
        $check['single'] = true;


        if (is_logged()) {
            //check if the same user has voted and if it is change his rating instead of adding new
            $check['created_by'] = user_id();
            $check = $this->model->get($check);

        } else {
            //check if the same session has voted and if it is change his rating instead of adding new
            $sid = mw()->user_manager->session_id();
            $check['session_id'] = $sid;
            $check = $this->model->get($check);
        }


        if ($check and isset($check['id'])) {
            $save['id'] = $check['id'];
        }

        if (isset($save['id']) and isset($save['rating']) and $save['rating'] == 0) {
            return $this->model->delete($save['id']);
        }


        $save = $this->model->save($save);
        return $save;
    }


}