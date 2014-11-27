<?php

function userfiles_path()
{
    static $folder;
    if (!$folder) {
        $folder = normalize_path(public_path() . DIRECTORY_SEPARATOR . MW_USERFILES_FOLDER_NAME . DIRECTORY_SEPARATOR);
    }
    return $folder;
}

function userfiles_url()
{
    static $folder;
    if (!$folder) {
        $folder = site_url(MW_USERFILES_FOLDER_NAME . '/');
    }
    return $folder;
}
function media_base_url()
{
    static $folder;
    if (!$folder) {
        $folder = userfiles_url().(MW_MEDIA_FOLDER_NAME . '/');
    }
    return $folder;
}

function media_base_path()
{
    static $folder;
    if (!$folder) {
        $folder = userfiles_path().(MW_MEDIA_FOLDER_NAME . DIRECTORY_SEPARATOR);
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


function modules_url()
{
    static $folder;
    if (!$folder) {
        $folder = site_url(MW_USERFILES_FOLDER_NAME . '/' . MW_MODULES_FOLDER_NAME . '/');
    }
    return $folder;
}


function templates_path()
{
    static $folder;
    if (!$folder) {
        $folder = (userfiles_path() . MW_TEMPLATES_FOLDER_NAME . DIRECTORY_SEPARATOR);
    }
    return $folder;
}

function templates_url()
{
    static $folder;
    if (!$folder) {
        $folder = site_url(MW_USERFILES_FOLDER_NAME . '/' . MW_TEMPLATES_FOLDER_NAME . '/');
    }
    return $folder;
}

function admin_url()
{
    static $folder;
    if (!$folder) {
        $folder = site_url('admin/');
    }
    return $folder;
}


//Microweber system url

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