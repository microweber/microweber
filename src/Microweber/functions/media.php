<?php

function get_picture($content_id, $for = 'post', $full = false)
{
    return mw('media')->get_picture($content_id, $for, $full);

}


api_expose('upload_progress_check');

function upload_progress_check()
{
    return mw('media')->upload_progress_check();

}

api_expose('upload');

function upload($data)
{
    return mw('media')->upload($data);

}


api_expose('reorder_media');

function reorder_media($data)
{

    return mw('media')->reorder($data);

}

api_expose('delete_media');

function delete_media($data)
{

    return mw('media')->delete($data);

}

api_expose('save_media');

function save_media($data)
{

    return mw('media')->save($data);
}

api_expose('pixum_img');
function pixum_img()
{
    return mw('media')->pixum_img();

}

function pixum($width, $height)
{
    return site_url('api/pixum_img') . "?width=" . $width . "&height=" . $height;
}

api_expose('thumbnail_img');
function thumbnail_img($params)
{
    return mw('media')->thumbnail_img($params);


}

if (!function_exists('thumbnail')) {
    function thumbnail($src, $width = 200, $height = 200)
    {
        return mw('media')->thumbnail($src, $width, $height);

    }
}
function get_pictures($params)
{

    return mw('media')->get($params);

}

api_expose('create_media_dir');

function create_media_dir($params)
{

    return mw('media')->create_media_dir($params);

}


//
//api_expose('delete_media_file');
//
//function delete_media_file($params)
//{
//    only_admin_access();
//
//    $target_path = MW_MEDIA_DIR . 'uploaded' . DS;
//    $target_path = normalize_path($target_path, 0);
//    $path_restirct = MW_USERFILES;
//
//    $fn_remove_path = $_REQUEST["path"];
//    $resp = array();
//    if ($fn_remove_path != false and is_array($fn_remove_path)) {
//        foreach ($fn_remove_path as $key => $value) {
//
//            $fn_remove = mw('url')->to_path($value);
//
//            if (isset($fn_remove) and trim($fn_remove) != '' and trim($fn_remove) != 'false') {
//                $path = urldecode($fn_remove);
//                $path = normalize_path($path, 0);
//                $path = str_replace('..', '', $path);
//                $path = str_replace($path_restirct, '', $path);
//                $target_path = MW_USERFILES . DS . $path;
//                $target_path = normalize_path($target_path, false);
//
//                if (stristr($target_path, MW_MEDIA_DIR)) {
//
//                    if (is_dir($target_path)) {
//                        mw('Microweber\Utils\Files')->rmdir($target_path, false);
//                        $resp = array('success' => 'Directory ' . $target_path . ' is deleted');
//                    } else if (is_file($target_path)) {
//                        unlink($target_path);
//                        $resp = array('success' => 'File ' . basename($target_path) . ' is deleted');
//                    } else {
//                        $resp = array('error' => 'Not valid file or folder ' . $target_path . ' ');
//                    }
//
//                } else {
//                    $resp = array('error' => 'Not allowed to delete on ' . $target_path . ' ');
//
//                }
//            }
//
//        }
//    }
//    return $resp;
//
//}


api_expose('svg_gradient_output');

function svg_gradient_output()
{

    ?><?php

    $expires= 60*60 * 60 * 24 * 14;
    header('Pragma: public');
    $etag = '"'.md5(serialize($_GET)).'"';

    header('Cache-Control: max-age=' . $expires);
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
    header("Content-type: image/svg+xml");
    header('Last-Modified:'. gmdate('D, d M Y H:i:s', time() - $expires) . ' GMT');
    header('ETag:'.$etag);
    ?><?php echo '<?xml version="1.0" encoding="UTF-8" standalone="no"?>'; ?><?php echo "\n"; ?>
    <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
    <svg width="100%" height="100%" version="1.1" preserveAspectRatio="xMinYMin none" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="round-gradient-box" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" style="stop-color:#<?php echo $_GET['top']; ?>;stop-opacity:1"/>
                <stop offset="100%" style="stop-color:#<?php echo $_GET['bot']; ?>;stop-opacity:1"/>
            </linearGradient>
        </defs>
        <rect x="0" y="0" width="100%" height="100%" style="fill:url(#round-gradient-box)"/>
    </svg>
    <?php exit(); ?>
<?php
}