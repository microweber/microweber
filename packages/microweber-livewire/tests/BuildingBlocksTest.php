<?php

declare(strict_types=1);

namespace MicroweberPackages\Livewire\Tests;

use MicroweberPackages\Livewire\Auth\Access\AuthorizesRequests;
use MicroweberPackages\Livewire\Http\Controllers\CanPretendToBeAFileButNotInCliTrait;
use PHPUnit\Framework\Attributes\Test;

/**
 * Standalone unit coverage for the package's dependency-free building blocks.
 */
class BuildingBlocksTest extends TestCase
{
    #[Test]
    public function package_traits_are_autoloadable(): void
    {
        $this->assertTrue(
            trait_exists(AuthorizesRequests::class),
            'AuthorizesRequests trait should be autoloadable.'
        );
        $this->assertTrue(
            trait_exists(CanPretendToBeAFileButNotInCliTrait::class),
            'CanPretendToBeAFileButNotInCliTrait trait should be autoloadable.'
        );
    }

    #[Test]
    public function authorizes_requests_denies_by_default(): void
    {
        $subject = new class {
            use AuthorizesRequests;
        };

        // The package deliberately stubs authorize() to deny every ability
        // (host CMS re-implements real gating); prove that contract holds.
        $this->assertFalse($subject->authorize('any-ability'));
        $this->assertFalse($subject->authorize('edit', ['some-model']));
    }
}
