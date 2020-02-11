<?php

api_expose_admin('tag/edit', function($params) {

    $tag_id = $params['tag_id'];
    $filter = [
        'id'=>$tag_id,
        'single'=>1
    ];
    $tag = db_get('tagging_tags', $filter);

    if ($tag) {
        $newData = [];
        $newData['name'] = $params['name'];
        $newData['slug'] = $params['slug'];
        $newData['id'] = $tag_id;

        if (db_save('tagging_tags',$newData)) {
           echo json_encode(['status'=>true]);
        }
    }

    echo json_encode(['status'=>false]);

});

api_expose_admin('tag/delete', function($params) {

    $tag_id = $params['tag_id'];
    $filter = [
        'id'=>$tag_id,
        'single'=>1
    ];
    $tag = db_get('tagging_tags', $filter);
    if ($tag) {
        if (db_delete('tagging_tags', $tag_id)) {
            echo json_encode(['status'=>true]);
        }
    }

    echo json_encode(['status'=>false]);

});