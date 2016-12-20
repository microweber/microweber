<?php

autoload_add(__DIR__);

template_head(modules_url().'rating/lib.js');
template_head(modules_url().'rating/rating.js');
template_head(modules_url().'rating/rating.js');
/*
template_head(function($page){
  $css = 'http://bootswatch.com/flatly/bootstrap.css';
  $link = '<link rel="stylesheet" href="'.$css.'" type="text/css">';
  return $link;

});
*/
api_expose('rating/Controller/save');

event_bind('module.comments.item.info', function ($params) {
    return mw('rating\Controller')->comment_rating($params);
});

event_bind('module.rating.simple', function ($params) {
	return mw('rating\Controller')->simple_rating($params);
});