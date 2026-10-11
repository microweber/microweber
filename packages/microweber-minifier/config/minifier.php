<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Minification toggles
    |--------------------------------------------------------------------------
    */
    // Master switch is OFF by default on new installs — minification only kicks in
    // once an admin enables it on the Asset Minifier page (System Settings). The
    // per-type switches default ON so enabling the master switch "just works".
    'enabled' => env('MW_MINIFIER_ENABLED', env('MINIFIER_ENABLED', false)),
    'minify_js' => env('MW_MINIFIER_JS', env('MINIFIER_JS', true)),
    'minify_css' => env('MW_MINIFIER_CSS', env('MINIFIER_CSS', true)),

    /*
    |--------------------------------------------------------------------------
    | JavaScript options (JShrink-compatible)
    |--------------------------------------------------------------------------
    */
    'js' => [
        // Preserve /*! ... */ license comments when true
        'flaggedComments' => env('MINIFIER_JS_FLAGGED_COMMENTS', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | CSS options
    |--------------------------------------------------------------------------
    */
    'css' => [
        'remove_comments' => true,
        'shorten_zeros' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Route middleware
    |--------------------------------------------------------------------------
    |
    | The minifier HTTP endpoints are admin-only (they minify arbitrary
    | JS/CSS on demand) and are registered only in the testing environment.
    |
    */
    'middleware' => ['admin'],
];
