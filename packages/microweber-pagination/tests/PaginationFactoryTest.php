<?php

declare(strict_types=1);

namespace MicroweberPackages\Pagination\Tests;

use Illuminate\Pagination\LengthAwarePaginator;
use MicroweberPackages\Pagination\PaginationFactory;
use MicroweberPackages\Pagination\Paginator;
use PHPUnit\Framework\Attributes\Test;

class PaginationFactoryTest extends TestCase
{
    #[Test]
    public function make_returns_paginator_with_config_defaults(): void
    {
        $factory = new PaginationFactory();
        $paginator = $factory->make([
            'currentPage' => 5,
            'lastPage'    => 100,
            'baseUrl'     => 'https://example.com',
        ]);

        $this->assertInstanceOf(Paginator::class, $paginator);
        $this->assertSame(5, $paginator->getCurrentPage());
        $this->assertSame(100, $paginator->getLastPage());
    }

    #[Test]
    public function make_applies_config_values(): void
    {
        $this->app['config']->set('mw-pagination.theme', 'tailwind');
        $this->app['config']->set('mw-pagination.size', 'lg');
        $this->app['config']->set('mw-pagination.on_each_side', 3);

        $factory = new PaginationFactory();
        $paginator = $factory->make([
            'currentPage' => 1,
            'lastPage'    => 10,
        ]);

        $this->assertSame('tailwind', $paginator->getTheme());
        $this->assertSame('lg', $paginator->getSize());
        $this->assertSame(3, $paginator->getOnEachSide());
    }

    #[Test]
    public function make_allows_option_override_of_config(): void
    {
        $this->app['config']->set('mw-pagination.theme', 'bootstrap');

        $factory = new PaginationFactory();
        $paginator = $factory->make([
            'currentPage' => 1,
            'lastPage'    => 10,
            'theme'       => 'tailwind-flex',
        ]);

        $this->assertSame('tailwind-flex', $paginator->getTheme());
    }

    #[Test]
    public function from_laravel_creates_from_length_aware_paginator(): void
    {
        $items = collect(range(1, 50));
        $laravelPaginator = new LengthAwarePaginator(
            $items->forPage(3, 10),
            50,
            10,
            3,
            ['path' => 'https://example.com/items']
        );

        $factory = new PaginationFactory();
        $paginator = $factory->fromLaravel($laravelPaginator);

        $this->assertInstanceOf(Paginator::class, $paginator);
        $this->assertSame(3, $paginator->getCurrentPage());
        $this->assertSame(5, $paginator->getLastPage());
    }
}