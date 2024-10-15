<?php
function media_uploads_url()
{
    $environment = app()->environment();
    $folder = media_base_url() . ('default/');

    if(mw_is_multisite()) {
        $folder = media_base_url() . ($environment . '/');
    }


    return $folder;
}

function media_uploads_path()
{
    $environment = app()->environment();
    $folder = media_base_path() . ('default' . DIRECTORY_SEPARATOR);

    if(mw_is_multisite()) {
        $folder = media_base_path() . ($environment . DIRECTORY_SEPARATOR);
    }

    return $folder;
}

if (!function_exists('media_base_url')) {
    function media_base_url()
    {
        static $folder;

        if (!$folder) {
            $folder = userfiles_url() . (MW_MEDIA_FOLDER_NAME . '/');
        }

        return $folder;
    }
}


function media_base_path()
{
    static $folder;
    if (!$folder) {
        $folder = userfiles_path() . (MW_MEDIA_FOLDER_NAME . DIRECTORY_SEPARATOR);
    }

    return $folder;
}
