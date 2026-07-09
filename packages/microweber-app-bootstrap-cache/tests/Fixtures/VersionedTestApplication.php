<?php

namespace MicroweberPackages\AppBootstrapCache\Tests\Fixtures;

use Illuminate\Foundation\Application;
use MicroweberPackages\AppBootstrapCache\HasVersionedBootstrapCache;

/**
 * A test Application subclass with an APP_VERSION constant.
 */
class VersionedTestApplication extends Application
{
    use HasVersionedBootstrapCache;

    const APP_VERSION = '4.0-dev17';
}