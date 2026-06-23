<?php

namespace MicroweberPackages\Repository\Tests;

use MicroweberPackages\Repository\RepositoryManager;

class RepositoryManagerTest extends TestCase
{
    public function test_manager_can_be_instantiated()
    {
        $manager = $this->app->make('repository_manager');
        $this->assertInstanceOf(RepositoryManager::class, $manager);
    }

    public function test_get_default_driver_returns_null()
    {
        $manager = $this->app->make('repository_manager');
        $this->assertNull($manager->getDefaultDriver());
    }
}