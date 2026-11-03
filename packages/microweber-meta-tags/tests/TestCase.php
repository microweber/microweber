<?php

declare(strict_types=1);

namespace MicroweberPackages\MetaTags\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * The meta-tags service provider extends butschster/meta-tags' application
     * provider and wires the full CMS head/footer package registry, which needs
     * the integrated CMS runtime (is_cli(), app()->ui, …). Standalone coverage
     * therefore exercises the self-contained tag Entities directly rather than
     * booting the provider.
     */
    protected function getPackageProviders($app): array
    {
        return [];
    }
}
