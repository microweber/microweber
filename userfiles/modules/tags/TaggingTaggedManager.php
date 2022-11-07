<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/27/2020
 * Time: 4:25 PM
 */

api_expose_admin('tagging_tagged/get_by_taggable_id', 'tagging_tagged_get_by_taggable_id');
function tagging_tagged_get_by_taggable_id($params) {

    if (!isset($params['taggable_id'])) {
        return;
    }

    $post = get_content([
        'id'=>$params['taggable_id'],
        'single'=> '1',
        'no_cache'=>'1'
    ]);

    $tags = db_get('tagging_tagged', [
        'taggable_id'=>$post['id'],
        'no_cache'=>'1'
    ]);

    return array('title'=>$post['title'], 'tags'=>$tags);
}

api_expose_admin('tagging_tagged/add', 'tagging_tagged_add');
function tagging_tagged_add($params) {

    $params = xss_clean($params);
    $taggableIds = [];
    if (isset($params['taggable_ids']) && is_array($params['taggable_ids'])){
        $taggableIds = $params['taggable_ids'];
    }

    if (empty($taggableIds)) {
        if (!isset($params['taggable_id']) || empty($params['taggable_id'])) {
            return ['status'=>false, 'message'=>_e('Post can\'t be identicated.', true)];
        }
        $taggableIds[] = intval($params['taggable_id']);
    }

    if (empty(trim($params['tag_name']))) {
        return ['status'=>false, 'message'=>_e('Please, fill the tag name.', true)];
    }

    if (empty($params['tagging_tag_id'])) {

        $saveTaggingTag = tagging_tag_edit([
            'name'=>$params['tag_name']
        ]);

        $params['tagging_tag_id'] = $saveTaggingTag['id'];

        // return ['status'=>false, 'message'=>_e('Global tag can\'t be identicated.', true)];
    }

    $getGlobalTag = db_get('tagging_tags',['id'=>intval($params['tagging_tag_id']), 'single'=>1]);
    if ($getGlobalTag) {

        $savedIds = [];
        $errors = [];
        foreach ($taggableIds as $taggableId) {

            $checkTaggingTagged = db_get('tagging_tagged', 'taggable_id=' . $taggableId . '&tag_slug=' . $getGlobalTag['slug'] . '&single=1');
            if ($checkTaggingTagged) {
                $errors[] = ['status' => false, 'message' => _e('Tag is allready added.', true)];
                continue;
            }

            // Save tag post
            $saveTaggingTagged = db_save('tagging_tagged', [
                'taggable_id' => $taggableId,
                'taggable_type' => 'content',
                'tag_name' => $getGlobalTag['name'],
                'tag_slug' => $getGlobalTag['slug'],
                'tag_description' => $getGlobalTag['description'],
            ]);

            if ($saveTaggingTagged) {
                $savedIds[] = db_get('tagging_tagged', 'id=' . $saveTaggingTagged . '&single=1');
            }
        }

        return ['status'=>true, 'ids'=>$savedIds, 'errors'=>$errors];
    }

    return ['status'=>false];

}

api_expose_admin('tagging_tagged/delete', 'tagging_tagged_delete');
function tagging_tagged_delete($params) {

    $tagging_tagged_id = $params['tagging_tagged_id'];
    $filter = [
        'no_cache'=>false,
        'id'=>$tagging_tagged_id,
        'single'=>1
    ];
    $tag = db_get('tagging_tagged', $filter);
    if ($tag) {
        if (db_delete('tagging_tagged', $tagging_tagged_id)) {
            return ['status'=>true];
        }
    }

    return ['status'=>false];
}
