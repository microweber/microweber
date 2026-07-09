<?php

namespace MicroweberPackages\AppBootstrapCache\Tests\Fixtures;

use Illuminate\Foundation\Application;
use MicroweberPackages\AppBootstrapCache\HasVersionedBootstrapCache;

/**
 * A test Application subclass without an APP_VERSION constant.
 */
class TestApplication extends Application
{
    use HasVersionedBootstrapCache;
}