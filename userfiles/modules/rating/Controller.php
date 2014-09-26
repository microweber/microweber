<?php


namespace Microweber\rating;


class Controller
{
    var $model = null;

    function __construct($app = null)
    {
        $this->model = new Model($app);
    }

    function index($params)
    {
        $cont_id = false;
		if(isset($params['content_id'])){
			$cont_id = $params['content_id'];
		} elseif(isset($params['content-id'])){
			$cont_id = $params['content-id'];
		}
		
		if($cont_id  == false){
			
		return;	
		}
		
		 $rating = 0;
        $rel = 'content';
        $rel_id = intval($cont_id);

        $get = array();
        $get['rel'] = $rel;
        $get['rel_id'] = $rel_id;
        $get['sum'] = 'rating';
        $get['group_by'] = 'rel_id,rel';
        $get['single'] = true;
        $get = $this->model->get($get);
        if (!isset($get['rating'])) {
            $rating_points = 0;
        } else {
            $rating_points = $get['rating'];
        }


        $get = array();
        $get['rel'] = $rel;
        $get['rel_id'] = $rel_id;
        $get['count'] = true;
        $total_of_ratings = $this->model->get($get);

        $view_file = __DIR__ . DS . 'views' . DS . 'star_rating.php';
        $view = new \Microweber\View($view_file);
        if ($rating_points > 0 and $total_of_ratings > 0) {
            $rating = $rating_points / $total_of_ratings;
        }

        $view->assign('ratings', intval($rating));
        $view->assign('rel', $rel);
        $view->assign('rel_id', $rel_id);

        return $view->display();
    }
	
	 function simple_rating($item)
    {
        
		 
		$rating = 0;
        $rel = 'content';
        $rel_id = $item['id'];

        $get = array();
        $get['rel'] = $rel;
        $get['rel_id'] = $rel_id;
        $get['sum'] = 'rating';
        $get['group_by'] = 'rel_id,rel';
        $get['single'] = true;
        $get = $this->model->get($get);
        if (!isset($get['rating'])) {
            $rating_points = 0;
        } else {
            $rating_points = $get['rating'];
        }


        $get = array();
        $get['rel'] = $rel;
        $get['rel_id'] = $rel_id;
        $get['count'] = true;
        $total_of_ratings = $this->model->get($get);

        $view_file = __DIR__ . DS . 'views' . DS . 'simple_rating.php';
        $view = new \Microweber\View($view_file);
        if ($rating_points > 0 and $total_of_ratings > 0) {
            $rating = $rating_points / $total_of_ratings;
        }

        $view->assign('ratings', intval($rating));
        $view->assign('rel', $rel);
        $view->assign('rel_id', $rel_id);

        return $view->display();

    }
	 

    function comment_rating($item)
    {
        $rating = 0;
        $rel = 'comment';
        $rel_id = $item['id'];

        $get = array();
        $get['rel'] = $rel;
        $get['rel_id'] = $rel_id;
        $get['sum'] = 'rating';
        $get['group_by'] = 'rel_id,rel';
        $get['single'] = true;
        $get = $this->model->get($get);
        if (!isset($get['rating'])) {
            $rating_points = 0;
        } else {
            $rating_points = $get['rating'];
        }


        $get = array();
        $get['rel'] = $rel;
        $get['rel_id'] = $rel_id;
        $get['count'] = true;
        $total_of_ratings = $this->model->get($get);

        $view_file = __DIR__ . DS . 'views' . DS . 'comment_rating.php';
        $view = new \Microweber\View($view_file);
        if ($rating_points > 0 and $total_of_ratings > 0) {
            $rating = $rating_points / $total_of_ratings;
        }

        $view->assign('ratings', intval($rating));
        $view->assign('rel', $rel);
        $view->assign('rel_id', $rel_id);

        return $view->display();

    }

    function save($item)
    {
        if (!isset($item['rel_id']) and !isset($item['rel']) and !isset($item['rating'])) {
            return false;
        }
        $save = $this->model->save($item);
        return $save;
    }

    function show_rating($item)
    {
        $ratings = $this->model->get($item);
    }
}