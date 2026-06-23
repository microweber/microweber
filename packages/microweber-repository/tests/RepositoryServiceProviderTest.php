<?php

namespace MicroweberPackages\Repository\Tests;

use MicroweberPackages\Repository\RepositoryManager;

class RepositoryServiceProviderTest extends TestCase
{
    public function test_repository_manager_is_bound()
    {
        $this->assertTrue($this->app->bound('repository_manager'));
    }

    public function test_repository_manager_is_singleton()
    {
        $manager1 = $this->app->make('repository_manager');
        $manager2 = $this->app->make('repository_manager');

        $this->assertSame($manager1, $manager2);
    }

    public function test_repository_manager_is_correct_instance()
    {
        $manager = $this->app->make('repository_manager');

        $this->assertInstanceOf(RepositoryManager::class, $manager);
    }
}