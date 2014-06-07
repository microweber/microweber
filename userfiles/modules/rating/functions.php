<?php
autoload_add(__DIR__);
template_header(MW_MODULES_URL.'rating/lib.js');
template_header(MW_MODULES_URL.'rating/rating.js');

api_expose('rating/Controller/save');

event_bind('module.comments.item.info', function ($params) {
    return mw('rating/controller')->comment_rating($params);
});

