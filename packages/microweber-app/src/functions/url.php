<?php


function public_asset($path = '', $secure = null)
{
    if (!defined('MW_SERVED_FROM_BASE_PATH')) {
        return asset($path, $secure);
    }


    if (!$path) {
        return asset('/public/', $secure) . '/';
    }

    return asset('/public/' . trim($path, '/'), $secure);
}

if (!function_exists('url2dir')) {
    function url2dir($path)
    {
        if (trim($path) == '') {
            return false;
        }




        //    $path = str_ireplace(site_url(), MW_ROOTPATH, $path);
        $public = public_path() . '/';
        $public = rtrim('//', $public);
        $path = str_ireplace(site_url(), $public, $path);

        $path = str_replace('\\', '/', $path);
        $path = str_replace('//', '/', $path);

        return normalize_path($path, false);
    }
}

if (!function_exists('dir2url')) {
    function dir2url($path)
    {

        $path1 = normalize_path($path, false);
        $path2 = normalize_path(public_path(), false);
        $path_media = normalize_path(media_base_path(), true);
        $path_media2 = normalize_path(media_base_path(), true);
        $url_media = media_base_url();

        $path = str_ireplace($path2, '', $path1);

        if (str_starts_with($path, $path_media)) {
            $path = str_ireplace($path_media, $url_media, $path);

            return $path;
        } elseif (str_starts_with($path, $path_media2)) {
            $path = str_ireplace($path_media2, $url_media, $path);
            return $path;
        }


        //$path = str_ireplace(MW_ROOTPATH, '', $path);
        $path = str_ireplace(site_url(), public_path(), $path);
        $path = str_ireplace(site_url(), MW_ROOTPATH, $path);

        $path = str_replace('\\', '/', $path);
        $path = str_replace('//', '/', $path);
        $path = ltrim($path, '/');
        $path = ltrim($path, '\\');

        //var_dump($path);
        return site_url($path);
    }
}


if (!function_exists('shop_url')) {
    function shop_url($add_string = false)
    {
        $shopPage = app()->content_repository->getFirstShopPage();
        if (!empty($shopPage)) {
            return content_link($shopPage['id']);
        }

        return site_url();
    }
}

