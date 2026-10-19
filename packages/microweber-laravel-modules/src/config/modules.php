<?php

use Nwidart\Modules\Activators\FileActivator;


return [

    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    |
    | Default module namespace.
    |
    */
    'namespace' => 'Modules',

    /*
    |--------------------------------------------------------------------------
    | Module Stubs
    |--------------------------------------------------------------------------
    |
    | Default module stubs.
    |
    */
    'stubs' => [
        'enabled' => false,
        'path' => dirname(dirname(__FILE__)) . '/stubs',
        //  'path' => base_path('src/MicroweberPackages/Module/stubs'),
        // 'path' => base_path('vendor/nwidart/laravel-modules/src/Commands/stubs'),
        'files' => [
            'routes/web' => 'routes/web.php',
            'routes/api' => 'routes/api.php',
            'views/index' => 'resources/views/index.blade.php',
            'views/master' => 'resources/views/layouts/master.blade.php',
            'scaffold/config' => 'config/config.php',
            'composer' => 'composer.json',
            'assets/js/app' => 'resources/assets/js/app.js',
            'assets/sass/app' => 'resources/assets/sass/app.scss',
            'assets/img/icon.svg' => 'resources/assets/img/icon.svg',
            'vite' => 'vite.config.js',
            'package' => 'package.json',
            'README' => 'README.md',
        ],
        'replacements' => [
            'routes/web' => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'CONTROLLER_NAMESPACE'],
            'routes/api' => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'CONTROLLER_NAMESPACE'],
            'vite' => ['LOWER_NAME', 'STUDLY_NAME'],
            'json' => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'PROVIDER_NAMESPACE'],
            'README' => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'PROVIDER_NAMESPACE'],
            'views/index' => ['LOWER_NAME', 'STUDLY_NAME'],
            'views/master' => ['LOWER_NAME', 'STUDLY_NAME'],
            'scaffold/config' => ['STUDLY_NAME', 'LOWER_NAME'],
            'composer' => [
                'LOWER_NAME',
                'STUDLY_NAME',
                'VENDOR',
                'AUTHOR_NAME',
                'AUTHOR_EMAIL',
                'MODULE_NAMESPACE',
                'PROVIDER_NAMESPACE',
                'APP_FOLDER_NAME',
            ]
        ],
        'gitkeep' => false,
    ],

    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Modules path
        |--------------------------------------------------------------------------
        |
        | This path is used to save the generated module.
        | This path will also be added automatically to the list of scanned folders.
        |
        */
        'modules' => base_path('Modules'),

        /*
        |--------------------------------------------------------------------------
        | Modules assets path
        |--------------------------------------------------------------------------
        |
        | Here you may update the modules' assets path.
        |
        */
        'assets' => public_path('modules'),

        /*
        |--------------------------------------------------------------------------
        | The migrations' path
        |--------------------------------------------------------------------------
        |
        | Where you run the 'module:publish-migration' command, where do you publish the
        | the migration files?
        |
        */
        'migration' => base_path('database/migrations'),

        /*
        |--------------------------------------------------------------------------
        | The app path
        |--------------------------------------------------------------------------
        |
        | app folder name
        | for example can change it to 'src' or 'App'
        */
        'app_folder' => '',

        /*
        |--------------------------------------------------------------------------
        | Generator path
        |--------------------------------------------------------------------------
        | Customise the paths where the folders will be generated.
        | Setting the generate key to false will not generate that folder
        */
        'generator' => [
            //
            'actions' => ['path' => 'Actions', 'generate' => false],
            'casts' => ['path' => 'Casts', 'generate' => false],
            'channels' => ['path' => 'Broadcasting', 'generate' => false],
            'class' => ['path' => 'Classes', 'generate' => false],
            'command' => ['path' => 'Console', 'generate' => false],
            'component-class' => ['path' => 'View/Components', 'generate' => false],
            'emails' => ['path' => 'Emails', 'generate' => false],
            'event' => ['path' => 'Events', 'generate' => false],
            'enums' => ['path' => 'Enums', 'generate' => false],
            'exceptions' => ['path' => 'Exceptions', 'generate' => false],
            'jobs' => ['path' => 'Jobs', 'generate' => false],
            'helpers' => ['path' => 'Helpers', 'generate' => false],
            'interfaces' => ['path' => 'Interfaces', 'generate' => false],
            'listener' => ['path' => 'Listeners', 'generate' => false],
            'model' => ['path' => 'Models', 'generate' => false],
            'notifications' => ['path' => 'Notifications', 'generate' => false],
            'observer' => ['path' => 'Observers', 'generate' => false],
            'policies' => ['path' => 'Policies', 'generate' => false],
            'provider' => ['path' => 'Providers', 'generate' => true],
            'repository' => ['path' => 'Repositories', 'generate' => false],
            'resource' => ['path' => 'Transformers', 'generate' => false],
            'route-provider' => ['path' => 'Providers', 'generate' => false],
            'rules' => ['path' => 'Rules', 'generate' => false],
            'services' => ['path' => 'Services', 'generate' => false],
            'scopes' => ['path' => 'Models/Scopes', 'generate' => false],
            'traits' => ['path' => 'Traits', 'generate' => false],

            // Http/
            'controller' => ['path' => 'Http/Controllers', 'generate' => false],
            'filter' => ['path' => 'Http/Middleware', 'generate' => false],
            'request' => ['path' => 'Http/Requests', 'generate' => false],

            // config/
            'config' => ['path' => 'config', 'generate' => true],

            // database/
            'factory' => ['path' => 'database/factories', 'generate' => false],
            'migration' => ['path' => 'database/migrations', 'generate' => true],
            'seeder' => ['path' => 'database/seeders', 'generate' => false],

            // lang/
            'lang' => ['path' => 'lang', 'generate' => false],

            // resource/
            'assets' => ['path' => 'resources/assets', 'generate' => true],
            'component-view' => ['path' => 'resources/views/components', 'generate' => false],
            'views' => ['path' => 'resources/views', 'generate' => true],

            // routes/
            'routes' => ['path' => 'routes', 'generate' => false],

            // tests/
            'test-feature' => ['path' => 'Tests/Feature', 'generate' => false],
            'test-unit' => ['path' => 'Tests/Unit', 'generate' => true],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Package commands
    |--------------------------------------------------------------------------
    |
    | Here you can define which commands will be visible and used in your
    | application. You can add your own commands to merge section.
    |
    */
    'commands' => \MicroweberPackages\LaravelModules\Providers\ModulesConsoleServiceProvider::defaultCommands()
        ->merge([
            // New commands go here
        ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | Scan Path
    |--------------------------------------------------------------------------
    |
    | Here you define which folder will be scanned. By default will scan vendor
    | directory. This is useful if you host the package in packagist website.
    |
    */
    'scan' => [
        // Scan is enabled so the extra drop-in module folders below are also
        // discovered. The primary base_path('Modules') root is always scanned
        // via getPath(); enabling scan only ADDS the extra roots. Empty extra
        // folders glob to nothing, so this is a no-op until a module is dropped
        // in — boot-safe and additive.
        'enabled' => true,
        // Extra drop-in module roots. Folder names are configurable via env
        // (MW_EXTRA_MODULES_PATH / MW_EXTRA_MODULES_PATH_2) and default to
        // ExtraModules/ and ExtraModules2/ at the repo root. Only existing
        // directories are kept, so a missing folder is silently skipped.
        'paths' => array_values(array_filter([
            base_path(env('MW_EXTRA_MODULES_PATH', 'ExtraModules')),
            base_path(env('MW_EXTRA_MODULES_PATH_2', 'ExtraModules2')),
        ], 'is_dir')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Composer File Template
    |--------------------------------------------------------------------------
    |
    | Here is the config for the composer.json file, generated by this package
    |
    */
    'composer' => [
        'vendor' => env('MODULE_VENDOR', 'modules'),
        'author' => [
            'name' => env('MODULE_AUTHOR_NAME', 'Microweber Team'),
            'email' => env('MODULE_AUTHOR_EMAIL', 'info@microweber.com'),
        ],
        'composer-output' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Here is the config for setting up the caching feature.
    |
    */
    'cache' => [
        'enabled' => env('MODULES_CACHE_ENABLED', true),
        'driver' => env('MODULES_CACHE_DRIVER', 'file'),
        'key' => env('MODULES_CACHE_KEY', 'laravel-modules'),
        'lifetime' => env('MODULES_CACHE_LIFETIME', 6000),
    ],

    /*
    |--------------------------------------------------------------------------
    | Choose what laravel-modules will register as custom namespaces.
    | Setting one to false will require you to register that part
    | in your own Service Provider class.
    |--------------------------------------------------------------------------
    */
    'register' => [
        'translations' => true,
        /**
         * load files on boot or register method
         */
        'files' => 'register',
    ],

    /*
    |--------------------------------------------------------------------------
    | Activators
    |--------------------------------------------------------------------------
    |
    | You can define new types of activators here, file, database, etc. The only
    | required parameter is 'class'.
    | The file activator will store the activation status in storage/installed_modules
    */
    'activators' => [
        'file' => [
            'class' => \MicroweberPackages\LaravelModules\Activators\LaravelModulesFileActivator::class,

            // Per-site on multisite: each domain gets its own
            // storage/sites/<domain>/modules_statuses.json (seeded from the base
            // file on first use), so disabling a module on one site never
            // affects another. Single-site keeps the shared file. The database
            // activator would avoid the disk entirely but currently crashes boot
            // (unbound modules.factory) and mis-seeds — opt in via MODULES_ACTIVATOR
            // only once fixed.
            'statuses-file' => (function_exists('mw_multisite_statuses_path')
                ? mw_multisite_statuses_path('modules_statuses.json')
                : storage_path('modules_statuses.json')),
            'cache-key' => 'modules_activator.installed',
            'cache-lifetime' => 604800,
        ],
        'database' => [
            'class' => \MicroweberPackages\LaravelModules\Activators\LaravelModulesDatabaseActivator::class,
            'table' => 'system_modules',
            // Distinct from the file activator's key so switching MODULES_ACTIVATOR
            // between file and database never serves the other's stale cache.
            'cache-key' => 'modules_db_activator.installed',
            'cache-lifetime' => 604800,
        ],
    ],

    // On a multisite domain, store module enable/disable state in the per-site
    // database (system_modules) — no local-disk writes, isolated per site. The
    // database activator self-seeds from the module.json manifests (preserving
    // any current file-activator state) on first use. Single-site keeps the file
    // activator (+ per-site statuses-file above as a fallback if forced to file).
    // An explicit MODULES_ACTIVATOR env always wins.
    'activator' => env('MODULES_ACTIVATOR')
        ?: ((function_exists('mw_multisite_active') && mw_multisite_active()) ? 'database' : 'file'),
];
