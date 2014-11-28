<?php

function get_picture($content_id, $for = 'post', $full = false)
{
    return mw()->media_manager->get_picture($content_id, $for, $full);

}

function get_picture_by_id($media_id)
{
    return mw()->media_manager->get_by_id($media_id);

}


api_expose('upload_progress_check');

function upload_progress_check()
{
    return mw()->media_manager->upload_progress_check();

}

api_expose('upload');

function upload($data)
{
    return mw()->media_manager->upload($data);

}


api_expose('reorder_media');

function reorder_media($data)
{

    return mw()->media_manager->reorder($data);

}

api_expose('delete_media');

function delete_media($data)
{

    return mw()->media_manager->delete($data);

}

api_expose('save_media');
function save_media($data)
{
    return save_picture($data);
}

function save_picture($data)
{

    return mw()->media_manager->save($data);
}

api_expose('pixum_img');
function pixum_img()
{
    return mw()->media_manager->pixum_img();

}

function pixum($width, $height)
{
    return site_url('api/pixum_img') . "?width=" . $width . "&height=" . $height;
}

api_expose('thumbnail_img');
function thumbnail_img($params)
{
    return mw()->media_manager->thumbnail_img($params);


}

if (!function_exists('thumbnail')) {
    function thumbnail($src, $width = 200, $height = 200)
    {
        return mw()->media_manager->thumbnail($src, $width, $height);

    }
}
function get_pictures($params)
{

    return mw()->media_manager->get($params);

}

api_expose('create_media_dir');

function create_media_dir($params)
{

    return mw()->media_manager->create_media_dir($params);

}

 