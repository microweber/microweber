<?php


use Illuminate\Support\Facades\Config;

function userfiles_path()
{
    static $folder;


    if (!$folder) {
        // $folder = normalize_path(base_path() . DIRECTORY_SEPARATOR);
        //if(MW_USERFILES_FOLDER_NAME){
      //  $folder = normalize_path(public_path() . DIRECTORY_SEPARATOR . MW_USERFILES_FOLDER_NAME . DIRECTORY_SEPARATOR);
        $folder = normalize_path(storage_path('app/public/') . DIRECTORY_SEPARATOR . MW_USERFILES_FOLDER_NAME . DIRECTORY_SEPARATOR);
        //  }
    }

    return $folder;
}

function userfiles_folder_name()
{
    return MW_USERFILES_FOLDER_NAME;
}

/**
 * Which Laravel filesystem disk userfiles (media, custom CSS, fonts, …) are
 * written to / served from.
 *
 * Default "public" (local). Set MEDIA_UPLOAD_DISK=s3 to push userfiles to an
 * S3-compatible bucket. Falls back to "public" if s3 is selected but not
 * configured, so a missing bucket never silently drops writes. Single source
 * of truth for every userfiles writer (media_upload_disk() delegates here).
 */
function userfiles_disk()
{
    $disk = Config::get('media.upload_disk');
    if (!$disk) {
        $disk = env('MEDIA_UPLOAD_DISK', 'public');
    }

    if ($disk === 's3' && !Config::get('filesystems.disks.s3.bucket')) {
        return 'public';
    }

    return $disk ?: 'public';
}

/**
 * Path of a userfiles sub-resource RELATIVE to the "public" disk root
 * (storage/app/public), e.g. userfiles_base_relpath('css') =>
 * "userfiles/<site>/css". Already per-site via MW_USERFILES_FOLDER_NAME, so the
 * same string addresses the local file and its S3 object (per-site subfolder).
 */
function userfiles_base_relpath($sub = '')
{
    $rel = MW_USERFILES_FOLDER_NAME . ($sub !== '' ? '/' . trim($sub, '/') : '');

    return str_replace('\\', '/', $rel);
}

/**
 * True when userfiles are backed by a remote (non-local) disk such as S3.
 */
function userfiles_disk_is_remote()
{
    return !in_array(userfiles_disk(), ['public', 'local'], true);
}


function userfiles_url()
{
    static $folder;
    if (!$folder) {

//        if (defined('MW_BOOT_FROM_PUBLIC_FOLDER')) {
//            $folder = site_url();
//        } else {
//            $folder = site_url(MW_USERFILES_FOLDER_NAME . '/');
//        }
      //  $folder = site_url(MW_USERFILES_FOLDER_NAME . '/');
        $folder = site_url('storage/'.MW_USERFILES_FOLDER_NAME . '/');

    }

    return $folder;
}


function modules_path()
{
    static $folder;
    if (!$folder) {
      //  $folder = (userfiles_path() . MW_MODULES_FOLDER_NAME . DIRECTORY_SEPARATOR);
        $folder = normalize_path(base_path(MW_MODULES_FOLDER_NAME), true);
    }

    return $folder;
}

function elements_path()
{
    static $folder;
    if (!$folder) {
        $folder = (userfiles_path() . MW_ELEMENTS_FOLDER_NAME . DIRECTORY_SEPARATOR);
    }

    return $folder;
}



function modules_url()
{
    static $folder;
    if (!$folder) {
//        if (defined('MW_BOOT_FROM_PUBLIC_FOLDER')) {
//            $folder = site_url(MW_MODULES_FOLDER_NAME . '/');
//        } else {
//            $folder = site_url(MW_USERFILES_FOLDER_NAME . '/' . MW_MODULES_FOLDER_NAME . '/');
//        }
//
        $folder = site_url(MW_USERFILES_FOLDER_NAME . '/' . MW_MODULES_FOLDER_NAME . '/');

    }

    return $folder;
}

function templates_dir()
{
    static $folder;
    if (!$folder) {
        //  $folder = (userfiles_path() . MW_TEMPLATES_FOLDER_NAME . DIRECTORY_SEPARATOR);
        $folder = (base_path('Templates') . DIRECTORY_SEPARATOR);
    }

    return $folder;
}

/**
 * @alias Use templates_dir() instead.
 */
function templates_path()
{
    return templates_dir();
}

function templates_url()
{
    static $folder;
    if (!$folder) {
//        if (defined('MW_BOOT_FROM_PUBLIC_FOLDER')) {
//            $folder = site_url( MW_TEMPLATES_FOLDER_NAME . '/');
//        } else {
//            $folder = site_url(MW_USERFILES_FOLDER_NAME . '/' . MW_TEMPLATES_FOLDER_NAME . '/');
//        }

        // $folder = site_url(MW_USERFILES_FOLDER_NAME . '/' . MW_TEMPLATES_FOLDER_NAME . '/');
        //  $folder = site_url(MW_USERFILES_FOLDER_NAME . '/' . MW_TEMPLATES_FOLDER_NAME . '/');

        $folder = site_url('templates' . '/');
    }

    return $folder;
}

function admin_url($add_string = false)
{
    static $admin_url = null;
    if ($admin_url === null) {
        $admin_url = Config::get('microweber.admin_url');
        if (!$admin_url) {
            $admin_url = 'admin';
        }
    }
    if ($admin_url) {
        $url = site_url($admin_url);
    } else {
        $url = site_url('admin');

    }

    $url = $url . '/' . $add_string;
    return $url;
}

//Microweber system

function mw_cache_path()
{
    return storage_path() . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
}

/* @deprecated */
function mw_includes_url()
{
    static $folder;
    if (!$folder) {
        $folder = modules_url() . MW_SYSTEM_MODULE_FOLDER . '/';
    }

    return $folder;
}

/* @deprecated */
function mw_includes_path()
{
    static $folder;
    if (!$folder) {
        // $folder = modules_path() . MW_SYSTEM_MODULE_FOLDER . '/';
        $folder = base_path() . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'MicroweberPackages' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;

    }

    return $folder;
}

function mw_root_path()
{
    static $folder;
    if (!$folder) {
        $folder = public_path();
    }

    return $folder;
}
