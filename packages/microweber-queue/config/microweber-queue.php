<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default chunk size for Bus dispatch
    |--------------------------------------------------------------------------
    |
    | Large workloads (e.g. newsletter bulk sends) are split into chunks of
    | this size so each queued job stays under the worker timeout.
    |
    */
    'chunk_size' => (int) env('MICROWEBER_QUEUE_CHUNK_SIZE', 100),

    /*
    |--------------------------------------------------------------------------
    | Max attempts when processing reserved jobs via ProcessQueueController
    |--------------------------------------------------------------------------
    */
    'process_max_attempts' => (int) env('MICROWEBER_QUEUE_PROCESS_MAX_ATTEMPTS', 10),

    /*
    |--------------------------------------------------------------------------
    | How many pending jobs to reserve per ProcessQueueController run
    |--------------------------------------------------------------------------
    */
    'process_limit' => (int) env('MICROWEBER_QUEUE_PROCESS_LIMIT', 10),

    /*
    |--------------------------------------------------------------------------
    | Default queue name used by ChunkedDispatcher when none is given
    |--------------------------------------------------------------------------
    */
    'default_queue' => env('MICROWEBER_QUEUE_DEFAULT', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Allowed job class prefixes for safe unserialization when processing
    |--------------------------------------------------------------------------
    |
    | Exact class names listed here, plus any class whose FQCN starts with one
    | of the prefixes, may be unserialized from the jobs payload.
    |
    */
    'allowed_job_classes' => [
        \Illuminate\Queue\CallQueuedClosure::class,
        \Illuminate\Broadcasting\BroadcastEvent::class,
        \Illuminate\Events\CallQueuedListener::class,
        \Illuminate\Mail\SendQueuedMailable::class,
        \Illuminate\Notifications\SendQueuedNotifications::class,
        \Illuminate\Queue\CallQueuedHandler::class,
    ],

    'allowed_job_class_prefixes' => [
        'App\\Jobs\\',
        'Modules\\',
        'MicroweberPackages\\',
    ],

    /*
    |--------------------------------------------------------------------------
    | Filament admin
    |--------------------------------------------------------------------------
    */
    'filament' => [
        'enabled' => true,
        'navigation_group' => 'System Settings',
        'navigation_sort' => 80,
    ],
];
