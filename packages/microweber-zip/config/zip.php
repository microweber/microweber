<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Zip-bomb protection limits
    |--------------------------------------------------------------------------
    |
    | These limits are enforced by ZipBombGuard before and during extraction.
    | Tune them for your environment; set a limit to 0 to disable that check.
    |
    */

    'max_files' => (int) env('ZIP_MAX_FILES', 100_000),

    // 1 GiB total uncompressed payload
    'max_total_uncompressed_bytes' => (int) env('ZIP_MAX_TOTAL_UNCOMPRESSED', 1_073_741_824),

    // 512 MiB for a single entry
    'max_single_file_uncompressed_bytes' => (int) env('ZIP_MAX_SINGLE_FILE', 536_870_912),

    // compressed:uncompressed ratio ceiling (e.g. 100 means 100:1)
    'max_compression_ratio' => (float) env('ZIP_MAX_COMPRESSION_RATIO', 100.0),

    'max_path_length' => (int) env('ZIP_MAX_PATH_LENGTH', 512),

    /*
    |--------------------------------------------------------------------------
    | Extraction defaults
    |--------------------------------------------------------------------------
    */

    'skip_directories' => ['__MACOSX', '.DS_Store'],

    'apply_chmod' => 0755,

];
