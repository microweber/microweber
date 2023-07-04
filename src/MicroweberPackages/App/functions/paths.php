<?php


function userfiles_path()
{
    static $folder;


    if (!$folder) {
       // $folder = normalize_path(base_path() . DIRECTORY_SEPARATOR);
        //if(MW_USERFILES_FOLDER_NAME){
        $folder = normalize_path(base_path() . DIRECTORY_SEPARATOR . MW_USERFILES_FOLDER_NAME . DIRECTORY_SEPARATOR);
        //  }
    }

    return $folder;
}
function userfiles_folder_name(){
    return MW_USERFILES_FOLDER_NAME;
}


function userfiles_url()
{
    static $folder;
    if (!$folder) {

        if (defined('MW_BOOT_FROM_PUBLIC_FOLDER')) {
            $folder = site_url();
        } else {
            $folder = site_url(MW_USERFILES_FOLDER_NAME . '/');
        }

    }

    return $folder;
}




function modules_path()
{
    static $folder;
    if (!$folder) {
        $folder = (userfiles_path() . MW_MODULES_FOLDER_NAME . DIRECTORY_SEPARATOR);
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

function elements_url()
{
    static $folder;
    if (!$folder) {
        $folder = (userfiles_url() . MW_ELEMENTS_FOLDER_NAME . '/');
    }

    return $folder;
}

function modules_url()
{
    static $folder;
    if (!$folder) {
        if (defined('MW_BOOT_FROM_PUBLIC_FOLDER')) {
            $folder = site_url(MW_MODULES_FOLDER_NAME . '/');
        } else {
            $folder = site_url(MW_USERFILES_FOLDER_NAME . '/' . MW_MODULES_FOLDER_NAME . '/');
        }
    }

    return $folder;
}
function templates_dir()
{
    static $folder;
    if (!$folder) {
        $folder = (userfiles_path() . MW_TEMPLATES_FOLDER_NAME . DIRECTORY_SEPARATOR);
    }

    return $folder;
}

/**
 * @deprecated Use templates_dir() instead.
 */
function templates_path()
{
         return templates_dir();
}

function templates_url()
{
    static $folder;
    if (!$folder) {
        if (defined('MW_BOOT_FROM_PUBLIC_FOLDER')) {
            $folder = site_url( MW_TEMPLATES_FOLDER_NAME . '/');
        } else {
            $folder = site_url(MW_USERFILES_FOLDER_NAME . '/' . MW_TEMPLATES_FOLDER_NAME . '/');
        }
    }

    return $folder;
}

function admin_url($add_string = false)
{
    static $admin_url = null;
    if ($admin_url === null) {
        $admin_url = \Config::get('microweber.admin_url');
        if (!$admin_url) {
            $admin_url = 'admin';
        }
    }
    if ($admin_url) {
        $url = site_url($admin_url);
    } else {
        $url = site_url('admin');

    }
    if($add_string){
        $url = $url . '/' . $add_string;
    }

    return $url;
}

//Microweber system

function mw_cache_path()
{
    return storage_path() . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
}

function mw_includes_url()
{
    static $folder;
    if (!$folder) {
        $folder = modules_url() . MW_SYSTEM_MODULE_FOLDER . '/';
    }

    return $folder;
}

function mw_includes_path()
{
    static $folder;
    if (!$folder) {
        $folder = modules_path() . MW_SYSTEM_MODULE_FOLDER . '/';
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
