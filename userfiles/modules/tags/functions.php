<?php

api_expose_admin('get_post_tags', function($params) {

    $post = get_content([
        'id'=>$params['post_id'],
        'single'=> '1'
    ]);

    $tags = [];

    return array('title'=>$post['title'], 'tags'=>$tags);
});

api_expose_admin('tag/edit', function($params) {

    if (empty(trim($params['name'])) || empty(trim($params['slug']))) {
        echo json_encode(['status'=>false]);
        exit;
    }

    $newData = [];
    $newData['name'] = $params['name'];
    $newData['slug'] = $params['slug'];

    if (isset($params['tag_id'])) {
        $tag_id = $params['tag_id'];
        $tag = db_get('tagging_tags', [
            'no_cache'=>false,
            'id'=>$tag_id,
            'single'=>1
        ]);
        if ($tag) {
            $newData['id'] = $tag['id'];
        }
    }

    if (db_save('tagging_tags',$newData)) {
        echo json_encode(['status'=>true]);
        exit;
    }

    echo json_encode(['status'=>false]);
    exit;

});

api_expose_admin('tag/delete', function($params) {

    $tag_id = $params['tag_id'];
    $filter = [
        'no_cache'=>false,
        'id'=>$tag_id,
        'single'=>1
    ];
    $tag = db_get('tagging_tags', $filter);
    if ($tag) {
        if (db_delete('tagging_tags', $tag_id)) {
            echo json_encode(['status'=>true]);
            exit;
        }
    }

    echo json_encode(['status'=>false]);
    exit;
});