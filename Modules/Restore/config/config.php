<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Allow PHP Files Upload
    |--------------------------------------------------------------------------
    |
    | This value determines whether PHP files can be uploaded during restore
    | operations. When disabled, PHP files in backup zips will be filtered out
    | for security reasons.
    |
    */
    'allow_php_files_upload' => env('MW_ALLOW_PHP_FILES_UPLOAD', false),

];
