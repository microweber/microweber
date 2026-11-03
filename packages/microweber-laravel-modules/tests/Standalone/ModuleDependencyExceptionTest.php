<?php

declare(strict_types=1);

namespace MicroweberPackages\LaravelModules\Tests\Standalone;

use MicroweberPackages\LaravelModules\Contracts\LaravelModulesCacheRepositoryContract;
use MicroweberPackages\LaravelModules\Exceptions\ModuleDependencyException;
use PHPUnit\Framework\Attributes\Test;

/**
 * Exercises the real dependency-resolution exception logic — the factory
 * methods that every dependency check in the package raises through.
 */
class ModuleDependencyExceptionTest extends TestCase
{
    #[Test]
    public function missing_dependency_factory_builds_message_and_issue(): void
    {
        $e = ModuleDependencyException::missingDependency('Shop', 'Cart', '^2.0');

        $this->assertInstanceOf(ModuleDependencyException::class, $e);
        $this->assertSame('Shop', $e->getModuleName());
        $this->assertStringContainsString("Module 'Shop' requires 'Cart'", $e->getMessage());
        $this->assertStringContainsString('^2.0', $e->getMessage());

        $issues = $e->getIssues();
        $this->assertCount(1, $issues);
        $this->assertSame('missing_dependency', $issues[0]['type']);
        $this->assertSame('Cart', $issues[0]['module']);
        $this->assertSame('^2.0', $issues[0]['constraint']);
    }

    #[Test]
    public function version_mismatch_and_conflict_factories_tag_their_issue_type(): void
    {
        $mismatch = ModuleDependencyException::versionMismatch('Shop', 'Cart', '1.0.0', '^2.0');
        $this->assertSame('version_mismatch', $mismatch->getIssues()[0]['type']);
        $this->assertSame('1.0.0', $mismatch->getIssues()[0]['installed_version']);

        $conflict = ModuleDependencyException::conflict('Shop', 'LegacyCart', '3.0.0', '<2.0');
        $this->assertSame('conflict', $conflict->getIssues()[0]['type']);
        $this->assertSame('LegacyCart', $conflict->getIssues()[0]['module']);
    }

    #[Test]
    public function circular_dependency_factory_renders_the_chain(): void
    {
        $e = ModuleDependencyException::circularDependency('C', ['A', 'B']);

        $this->assertSame('C', $e->getModuleName());
        $this->assertStringContainsString('A -> B -> C', $e->getMessage());
        $this->assertSame(['A', 'B'], $e->getIssues()[0]['chain']);
    }

    #[Test]
    public function cache_repository_contract_is_an_interface(): void
    {
        $this->assertTrue(
            interface_exists(LaravelModulesCacheRepositoryContract::class),
            'The cache repository contract should be an autoloadable interface.'
        );
    }
}
