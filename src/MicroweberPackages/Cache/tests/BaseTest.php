<?php

class BaseTest extends  \MicroweberPackages\Core\tests\TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        app()->get('config')->set('app.key', 'tQbgKF5NH5zMyGh4vCNypFAzx9trCkE6x');

        // Setup default database to use sqlite :memory:
        app()->get('config')->set('cache.default', 'file');
        app()->get('config')->set('cache.stores.file',
            [
                'driver' => 'file',
                'path' => storage_path('framework/cache'),
                'separator' => '~#~'
            ]
        );
    }

}
