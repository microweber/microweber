<?php

function get_picture($content_id, $for = 'post', $full = false)
{
    return mw()->media_manager->get_picture($content_id, $for, $full);
}

function get_picture_by_id($media_id)
{
    return get_media_by_id($media_id);
}

api_expose_admin('get_media_by_id');

function get_media_by_id($media_id)
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

api_expose_admin('media/upload', function ($data) {

    return mw()->media_manager->upload($data);
});

api_expose_admin('reorder_media');

function reorder_media($data)
{
    return mw()->media_manager->reorder($data);
}

api_expose('delete_media');

function delete_media($data)
{
    return mw()->media_manager->delete($data);
}

api_expose_admin('save_media');
function save_media($data)
{
    return save_picture($data);
}

api_expose_admin('save_picture');

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
    return mw()->media_manager->pixum($width, $height);
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
api_expose_admin('get_media');
function get_media($params)
{
    return mw()->media_manager->get($params);
}

function get_pictures($params)
{
    return mw()->media_manager->get($params);
}

api_expose_admin('create_media_dir');
function create_media_dir($params)
{
    return mw()->media_manager->create_media_dir($params);
}

api_bind('media/delete_media_file', function ($data) {
    return mw()->media_manager->delete_media_file($data);
});
