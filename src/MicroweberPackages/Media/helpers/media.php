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


api_expose_admin('upload');
api_expose_admin('reorder_media');
api_expose_admin('save_media');

api_expose('pixum_img');
api_expose('thumbnail_img');

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

/**
 * Remove EXIF from a IMAGE file.
 * @param string $old Path to original image file (input).
 * @param string $new Path to new jpeg file (output).
 */
function remove_exif_data($in, $out)
{
    $buffer_len = 4096;
    $fd_in = fopen($in, 'rb');
    $fd_out = fopen($out, 'wb');
    while (($buffer = fread($fd_in, $buffer_len)))
    {
        //  \xFF\xE1\xHH\xLLExif\x00\x00 - Exif
        //  \xFF\xE1\xHH\xLLhttp://      - XMP
        //  \xFF\xE2\xHH\xLLICC_PROFILE  - ICC
        //  \xFF\xED\xHH\xLLPhotoshop    - PH
        while (preg_match('/\xFF[\xE1\xE2\xED\xEE](.)(.)(exif|photoshop|http:|icc_profile|adobe)/si', $buffer, $match, PREG_OFFSET_CAPTURE))
        {
            //echo "found: '{$match[3][0]}' marker\n";
            $len = ord($match[1][0]) * 256 + ord($match[2][0]);
            //echo "length: {$len} bytes\n";
            //echo "write: {$match[0][1]} bytes to output file\n";
            fwrite($fd_out, substr($buffer, 0, $match[0][1]));
            $filepos = $match[0][1] + 2 + $len - strlen($buffer);
            fseek($fd_in, $filepos, SEEK_CUR);
            //echo "seek to: ".ftell($fd_in)."\n";
            $buffer = fread($fd_in, $buffer_len);
        }
        //echo "write: ".strlen($buffer)." bytes to output file\n";
        fwrite($fd_out, $buffer, strlen($buffer));
    }
    fclose($fd_out);
    fclose($fd_in);
}
