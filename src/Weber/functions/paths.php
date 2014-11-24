<?php

function userfiles_path()
{
    static $folder;
    if (!$folder) {
        $folder = normalize_path(public_path() . DIRECTORY_SEPARATOR . WB_USERFILES_FOLDER_NAME . DIRECTORY_SEPARATOR);
    }
    return $folder;
}

function userfiles_url()
{
    static $folder;
    if (!$folder) {
         $folder = site_url(WB_USERFILES_FOLDER_NAME . '/');
    }
    return $folder;
}

function modules_path()
{
    static $folder;
    if (!$folder) {
        $folder = (userfiles_path() . WB_MODULES_FOLDER_NAME . DIRECTORY_SEPARATOR);
    }
    return $folder;
}


function modules_url()
{
    static $folder;
    if (!$folder) {
        $folder = site_url(WB_USERFILES_FOLDER_NAME . '/' . WB_MODULES_FOLDER_NAME . '/');
    }
    return $folder;
}


function templates_path()
{
    static $folder;
    if (!$folder) {
        $folder = (userfiles_path() . WB_TEMPLATES_FOLDER_NAME . DIRECTORY_SEPARATOR);
    }
    return $folder;
}

function templates_url()
{
    static $folder;
    if (!$folder) {
        $folder = site_url(WB_USERFILES_FOLDER_NAME . '/' . WB_TEMPLATES_FOLDER_NAME . '/');
    }
    return $folder;
}


//Weber system url

function mw_includes_url()
{
    static $folder;
    if (!$folder) {
        $folder = modules_url() . WB_SYSTEM_MODULE_FOLDER . '/';
    }
    return $folder;
}

function mw_includes_path()
{
    static $folder;
    if (!$folder) {
        $folder = modules_path() . WB_SYSTEM_MODULE_FOLDER . '/';
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