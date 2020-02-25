<?php

api_expose_admin('get_post_tags', 'get_post_tags');

api_expose_admin('tag/get', 'tag_get');
api_expose_admin('tags/get', 'tags_get');
api_expose_admin('tag/view', 'tag_view');
api_expose_admin('tag/edit', 'tag_edit');
api_expose_admin('tag/delete', 'tag_delete');

api_expose_admin('post_tag/edit', 'post_tag_edit');
api_expose_admin('post_tag/delete', 'post_tag_delete');


api_expose_admin('tag/edit/autocomplete', function($params) {

    $founded_tags = [];

    $filter = '';
    if (isset($params['keyword'])) {
        $filter = 'keyword=' . $params['keyword'].'&search_in_fields=name,slug&no_cache=1';
    }

    $tagging_tags = db_get('tagging_tags', $filter);
    if ($tagging_tags) {
        foreach ($tagging_tags as $tagging_tag) {
            $founded_tags[] = [
                'id' => $tagging_tag['id'],
                'title' => $tagging_tag['name'],
            ];
        }
    }

    return ['data'=>$founded_tags];

});

function get_post_tags($params) {

    $post = get_content([
        'id'=>$params['post_id'],
        'single'=> '1'
    ]);

    $tags = db_get('tagging_tagged', [
        'taggable_id'=>$post['id']
    ]);

    return array('title'=>$post['title'], 'tags'=>$tags);
}
function tags_get($params) {

    $filter = 'order_by=id desc';
    if (isset($params['keyword'])) {
        $filter .= '&keyword=' . $params['keyword'].'&search_in_fields=name,slug';
    }

    $tagging_tags = db_get('tagging_tags', $filter);
    if ($tagging_tags) {
        foreach ($tagging_tags as &$tag) {
            $tag['posts_count'] = db_get('tagging_tagged', [
                'tag_slug'=>$tag['slug'],
                'count'=>1
            ]);
        }
        return $tagging_tags;
    }

    return ['error'=>true];
}

function tag_view($params) {

    $tag_id = $params['tag_id'];
    $filter = [
        'no_cache'=>false,
        'id'=>$tag_id,
        'single'=>1
    ];
    $tag = db_get('tagging_tags', $filter);

    return $tag;
}

function tag_edit($params) {

    if (empty(trim($params['name']))) {
        return ['status'=>false];
    }

    $newData = [];
    $newData['name'] = $params['name'];
    $newData['slug'] = $params['slug'];
    $newData['description'] = $params['description'];

    if (isset($params['tag_id']) && !empty($params['tag_id'])) {
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

    if (empty($newData['slug'])) {
        $newData['slug'] = mw()->url_manager->slug($newData['name']);
    } else {
        $newData['slug'] = mw()->url_manager->slug($newData['slug']);
    }

    if (!isset($newData['id'])) {
        $findTaggingTag = db_get('tagging_tags', 'name=' . $newData['name'].'&single=1');
        if ($findTaggingTag) {
            return ['status'=>false,'message'=>'The global tag is allready exists.'];
        }
    }


    $tagSaved = db_save('tagging_tags',$newData);
    if ($tagSaved) {
        if (!isset($newData['id'])) {
            $newData['id'] = $tagSaved;
        }

        if (isset($_POST['post_ids']) && is_array($_POST['post_ids'])) {
            foreach ($_POST['post_ids'] as  $post_id) {
                post_tag_edit([
                    'post_id'=>$post_id,
                    'tag_name'=>$newData['name'],
                    'tag_slug'=>$newData['slug'],
                    'tag_description'=>$newData['description'],
                ]);
            }
        }

        return $newData;
    }

    return ['status'=>false];

}

function tag_delete($params) {

    $tag_id = $params['tag_id'];
    $filter = [
        'no_cache'=>false,
        'id'=>$tag_id,
        'single'=>1
    ];
    $tag = db_get('tagging_tags', $filter);
    if ($tag) {
        if (db_delete('tagging_tags', $tag_id)) {

            // Delete this tag for all posts
            db_delete('tagging_tagged', $tag['slug'], 'tag_slug');

            echo json_encode(['status'=>true]);
            exit;
        }
    }

    echo json_encode(['status'=>false]);
    exit;
}

function post_tag_edit($params) {

    if (empty(trim($params['tag_name']))) {
        return ['status'=>false, 'message'=>_e('Please, fill the tag name.', true)];
    }

    if (empty($params['post_id'])) {
        return ['status'=>false, 'message'=>_e('Post cant be identicated.', true)];
    }

    if (empty($params['tag_slug'])) {
        $params['tag_slug'] = mw()->url_manager->slug($params['tag_name']);
    } else {
        $params['tag_slug'] = mw()->url_manager->slug($params['tag_slug']);
    }

    // Save global tag
    $check_global_tag = db_get('tagging_tags',['name'=>$params['tag_name'], 'single'=>1]);
    if (!$check_global_tag) {
        db_save('tagging_tags', [
            'name' => $params['tag_name'],
            'slug' => $params['tag_slug'],
            'description' => $params['tag_description'],
        ]);
    }

    if (!isset($params['id'])) {
        $params['id'] = false;
    }

    // Save tag post
    $save = db_save('tagging_tagged', [
       'id'=>$params['id'],
       'taggable_id'=>$params['post_id'],
       'taggable_type'=> 'Content',
       'tag_name'=>$params['tag_name'],
       'tag_slug'=>$params['tag_slug'],
       'tag_description'=>$params['tag_description'],
    ]);

    if (!isset($params['id']) || empty($params['id'])) {
        $params['id'] = $save;
    }

    return $params;
}

function post_tag_delete($params) {

    $post_tag_id = $params['post_tag_id'];
    $filter = [
        'no_cache'=>false,
        'id'=>$post_tag_id,
        'single'=>1
    ];
    $tag = db_get('tagging_tagged', $filter);
    if ($tag) {
        if (db_delete('tagging_tagged', $post_tag_id)) {
            return ['status'=>true];
        }
    }

    return ['status'=>false];

}