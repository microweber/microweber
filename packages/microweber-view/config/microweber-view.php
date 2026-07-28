<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Blade @module directive
    |--------------------------------------------------------------------------
    |
    | When true, registers the @module Blade directive and the <module />
    | tag precompiler. Standalone Laravel apps can leave this enabled and
    | bind a custom ModuleProcessorInterface implementation.
    |
    */
    'module_directive_enabled' => env('MW_VIEW_MODULE_DIRECTIVE', true),
];
