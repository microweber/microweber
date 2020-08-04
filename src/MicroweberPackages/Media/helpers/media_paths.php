<?php
function media_uploads_url()
{
    $environment = App::environment();
    $folder = media_base_url() . ($environment . '/');
    return $folder;
}

function media_uploads_path()
{
    $environment = App::environment();
    $folder = media_base_path() . ($environment . DIRECTORY_SEPARATOR);
    return $folder;
}

function media_base_url()
{
    static $folder;
    if (!$folder) {
        $folder = userfiles_url() . (MW_MEDIA_FOLDER_NAME . '/');
    }

    return $folder;
}

function media_base_path()
{
    static $folder;
    if (!$folder) {
        $folder = userfiles_path() . (MW_MEDIA_FOLDER_NAME . DIRECTORY_SEPARATOR);
    }

    return $folder;
}