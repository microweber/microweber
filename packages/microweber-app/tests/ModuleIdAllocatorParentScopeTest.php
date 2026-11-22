<?php

declare(strict_types=1);

namespace MicroweberPackages\App\StandaloneTests;

use MicroweberPackages\App\Utils\ParserHelpers\ModuleIdAllocator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * A module nested inside another module and carrying no explicit id must scope
 * its generated id under the PARENT module id (parity with the legacy parser),
 * and the dedup counter must key on the final base id so two DISTINCT bases do
 * not steal a "--N" suffix from each other.
 */
final class ModuleIdAllocatorParentScopeTest extends TestCase
{
    private function alloc(): ModuleIdAllocator
    {
        return new ModuleIdAllocator();
    }

    #[Test]
    public function nested_idless_module_gets_the_parent_prefix(): void
    {
        $a = $this->alloc();
        $id = $a->allocate('custom_fields', null, '', null, null, 'global', 'cf-a');
        $this->assertSame('cf-a-custom-fields', $id);
    }

    #[Test]
    public function top_level_idless_module_keeps_the_generic_base(): void
    {
        $a = $this->alloc();
        $id = $a->allocate('btn', null, '', null, null, 'global', null);
        $this->assertSame('module-btn', $id);
    }

    #[Test]
    public function two_parents_yield_distinct_unscoped_ids_no_double_dash(): void
    {
        $a = $this->alloc();
        $first = $a->allocate('custom_fields', null, '', null, null, 'global', 'cf-a');
        $second = $a->allocate('custom_fields', null, '', null, null, 'global', 'cf-b');
        $this->assertSame('cf-a-custom-fields', $first);
        $this->assertSame('cf-b-custom-fields', $second, 'A distinct base must NOT inherit a --N from the sibling.');
    }

    #[Test]
    public function genuine_same_base_repeats_still_get_a_suffix(): void
    {
        $a = $this->alloc();
        $first = $a->allocate('btn', null, '', null, null, 'global', null);
        $second = $a->allocate('btn', null, '', null, null, 'global', null);
        $this->assertSame('module-btn', $first);
        $this->assertSame('module-btn--1', $second);
    }

    #[Test]
    public function explicit_id_is_never_rewritten(): void
    {
        $a = $this->alloc();
        $this->assertSame('my-fixed-id', $a->allocate('btn', 'my-fixed-id', '', null, null, 'global', 'parent-x'));
    }
}
