<?php


api_expose_admin('media/upload', function ($data) {
    return mw()->media_manager->upload($data);
});

api_expose_admin('get_media_by_id');

api_expose_admin('reorder_media');
api_expose_admin('save_media');
api_expose_admin('save_picture');
api_expose('pixum_img');
api_expose('thumbnail_img');

api_expose_admin('get_media');



api_expose_admin('create_media_dir');


api_expose_admin('upload_progress_check');
api_expose_admin('upload');
api_expose_admin('reorder_media');
api_expose_admin('save_media');

api_expose('pixum_img');
api_expose('thumbnail_img');

api_expose_admin('media/delete_media_file', function ($data) {
    return app()->media_manager->delete_media_file($data);
});

api_expose_admin('delete_media', function ($data) {
    return app()->media_manager->delete($data);
});


function get_picture($content_id, $for = 'post', $full = false)
{
    return app()->media_manager->get_picture($content_id, $for, $full);
}

function get_picture_by_id($media_id)
{
    return get_media_by_id($media_id);
}


function get_media_by_id($media_id)
{
    return app()->media_manager->get_by_id($media_id);
}


function upload_progress_check()
{
    return app()->media_manager->upload_progress_check();
}


function upload($data)
{
    return app()->media_manager->upload($data);
}


function reorder_media($data)
{
    return app()->media_manager->reorder($data);
}


function delete_media($data)
{
    return app()->media_manager->delete($data);
}


function save_media($data)
{
    return save_picture($data);
}


function save_picture($data)
{
    return app()->media_manager->save($data);
}


function pixum_img()
{
    return app()->media_manager->pixum_img();
}

function pixum($width, $height)
{
    return app()->media_manager->pixum($width, $height);
}


function thumbnail_img($params)
{
    return app()->media_manager->thumbnail_img($params);
}

if (!function_exists('thumbnail')) {
    function thumbnail($src, $width = 200, $height = null, $crop = null)
    {
        return app()->media_manager->thumbnail($src, $width, $height, $crop);
    }
}

function get_media($params)
{
    return app()->media_manager->get($params);
}

function get_pictures($params)
{
    return app()->media_manager->get($params);
}


function  create_media_dir($params)
{
    return app()->media_manager->create_media_dir($params);
}





/**
 * Remove EXIF from a IMAGE file.
 * @param string $old Path to original image file (input).
 * @param string $new Path to new jpeg file (output).
 */
function remove_exif_data($old, $new)
{
    // Open the input file for binary reading
    $f1 = fopen($old, 'rb');
    // Open the output file for binary writing
    $f2 = fopen($new, 'wb');

    // Find EXIF marker
    while (($s = fread($f1, 2))) {
        $word = unpack('ni', $s)['i'];
        if ($word == 0xFFE1) {
            // Read length (includes the word used for the length)
            $s = fread($f1, 2);
            $len = unpack('ni', $s)['i'];
            // Skip the EXIF info
            fread($f1, $len - 2);
            break;
        } else {
            fwrite($f2, $s, 2);
        }
    }

    // Write the rest of the file
    while (($s = fread($f1, 4096))) {
        fwrite($f2, $s, strlen($s));
    }

    fclose($f1);
    fclose($f2);
}
