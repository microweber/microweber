<?php

namespace MicroweberPackages\Repository\Tests;

use MicroweberPackages\Repository\RepositoryManager;

class RepositoryServiceProviderTest extends TestCase
{
    public function test_repository_manager_is_bound()
    {
        $this->assertTrue($this->app->bound(\MicroweberPackages\Repository\RepositoryManager::class));
    }

    public function test_repository_manager_is_singleton()
    {
        $manager1 = $this->app->make(\MicroweberPackages\Repository\RepositoryManager::class);
        $manager2 = $this->app->make(\MicroweberPackages\Repository\RepositoryManager::class);

        $this->assertSame($manager1, $manager2);
    }

    public function test_repository_manager_is_correct_instance()
    {
        $manager = $this->app->make(\MicroweberPackages\Repository\RepositoryManager::class);

        $this->assertInstanceOf(RepositoryManager::class, $manager);
    }
}