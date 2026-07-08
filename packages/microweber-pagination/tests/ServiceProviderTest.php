<?php

declare(strict_types=1);

namespace MicroweberPackages\Pagination\Tests;

use PHPUnit\Framework\Attributes\Test;

class ServiceProviderTest extends TestCase
{
    #[Test]
    public function views_are_registered(): void
    {
        $this->assertTrue(
            view()->exists('mw-pagination::bootstrap.default'),
            'Bootstrap default view should be registered'
        );
        $this->assertTrue(
            view()->exists('mw-pagination::bootstrap.flex'),
            'Bootstrap flex view should be registered'
        );
        $this->assertTrue(
            view()->exists('mw-pagination::bootstrap.dropdown'),
            'Bootstrap dropdown view should be registered'
        );
        $this->assertTrue(
            view()->exists('mw-pagination::tailwind.default'),
            'Tailwind default view should be registered'
        );
        $this->assertTrue(
            view()->exists('mw-pagination::tailwind.flex'),
            'Tailwind flex view should be registered'
        );
        $this->assertTrue(
            view()->exists('mw-pagination::tailwind.dropdown'),
            'Tailwind dropdown view should be registered'
        );
    }

    #[Test]
    public function config_is_merged(): void
    {
        $this->assertNotNull(config('mw-pagination'));
        $this->assertSame('bootstrap', config('mw-pagination.theme'));
        $this->assertSame('md', config('mw-pagination.size'));
        $this->assertSame(5, config('mw-pagination.on_each_side'));
        $this->assertSame('page', config('mw-pagination.page_name'));
    }
}