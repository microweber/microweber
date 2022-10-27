<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/27/2020
 * Time: 3:34 PM
 */

// Language is changed
event_bind('mw.admin.change_language', function () {
   // sync_tags();
});

function sync_tags() {
    $tagging_tags = db_get('tagging_tags', 'no_limit=1');
    if (!empty($tagging_tags)) {
        foreach ($tagging_tags as $tagging_tag) {
            $save = tagging_tag_edit($tagging_tag);
        }
    }
}

api_expose_admin('tagging_tag/autocomplete', 'tagging_tag_autocomplete');
function tagging_tag_autocomplete($params) {

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
}

api_expose_admin('tagging_tag/edit', 'tagging_tag_edit');
function tagging_tag_edit($params) {

    if (empty(trim($params['name']))) {
        return ['status'=>false];
    }

    if (!isset($params['slug'])) {
        $params['slug'] = '';
    }

    if (!isset($params['description'])) {
        $params['description'] = '';
    }

    $newData = [];
    $newData['name'] = $params['name'];
    $newData['slug'] = $params['slug'];
    $newData['description'] = $params['description'];
    if (isset($params['id'])) {
        $newData['id'] = $params['id'];
    }

    $cleanInput = new \MicroweberPackages\Helper\HTMLClean();
    $newData = $cleanInput->cleanArray($newData);

    if (isset($params['tagging_tag_id']) && !empty($params['tagging_tag_id'])) {
        $tagging_tag_id = $params['tagging_tag_id'];
        $tag = db_get('tagging_tags', [
            'no_cache'=>false,
            'id'=>$tagging_tag_id,
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

    // Update all posts name with tag slug
    $getTaggingTagged = db_get('tagging_tagged', 'tag_slug='.$newData['slug'].'&no_cache=1');
    if ($getTaggingTagged) {
        foreach ($getTaggingTagged as $taggingTaggedPost) {

            $newTaggingTaggedPost = [];
            $newTaggingTaggedPost['id'] = $taggingTaggedPost['id'];
            $newTaggingTaggedPost['tag_name'] = $newData['name'];

            db_save('tagging_tagged', $newTaggingTaggedPost);
        }
    }

    if (!isset($newData['id'])) {
        $findTaggingTag = db_get('tagging_tags', 'slug=' . $newData['slug'].'&single=1');
        if ($findTaggingTag) {
            $newData['id'] = $findTaggingTag['id'];
            return ['status'=>false,'message'=>'The tag slug is allready exists.', 'id'=> $newData['id']];
        }
    }

    $tagSaved = db_save('tagging_tags',$newData);
    if ($tagSaved) {

        if (!isset($newData['id'])) {
            $newData['id'] = $tagSaved;
        }

        return $newData;
    }

    return ['status'=>false];

}

api_expose_admin('tagging_tag/get', 'tagging_tag_get');
function tagging_tag_get($params) {

    $cleanInput = new \MicroweberPackages\Helper\HTMLClean();
    $params = $cleanInput->cleanArray($params);

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

api_expose_admin('tagging_tag/view', 'tagging_tag_view');
function tagging_tag_view($params) {

    $tagging_tag_id = $params['tagging_tag_id'];
    $filter = [
        'no_cache'=>false,
        'id'=>$tagging_tag_id,
        'single'=>1
    ];
    $tag = db_get('tagging_tags', $filter);

    return $tag;
}

api_expose_admin('tagging_tag/delete', 'tagging_tag_delete');
function tagging_tag_delete($params) {

    $tagging_tag_id = $params['tagging_tag_id'];
    $filter = [
        'no_cache'=>false,
        'id'=>$tagging_tag_id,
        'single'=>1
    ];
    $tag = db_get('tagging_tags', $filter);
    if ($tag) {
        if (db_delete('tagging_tags', $tagging_tag_id)) {

            // Delete this tag for all posts
            db_delete('tagging_tagged', $tag['name'], 'tag_name');

            echo json_encode(['status'=>true]);
            exit;
        }
    }

    echo json_encode(['status'=>false]);
    exit;
}


