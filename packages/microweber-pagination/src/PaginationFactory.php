<?php

declare(strict_types=1);

namespace MicroweberPackages\Pagination;

/**
 * Factory for creating Paginator instances with application defaults.
 */
class PaginationFactory
{
    /**
     * Create a new Paginator with config-merged defaults.
     */
    public function make(array $options = []): Paginator
    {
        $defaults = [
            'theme'      => config('mw-pagination.theme', 'bootstrap'),
            'size'       => config('mw-pagination.size', 'md'),
            'onEachSide' => config('mw-pagination.on_each_side', 5),
            'pageName'   => config('mw-pagination.page_name', 'page'),
        ];

        return new Paginator(array_merge($defaults, $options));
    }

    /**
     * Create a Paginator from a Laravel LengthAwarePaginator.
     */
    public function fromLaravel(\Illuminate\Pagination\LengthAwarePaginator $paginator, array $options = []): Paginator
    {
        $defaults = [
            'theme'      => config('mw-pagination.theme', 'bootstrap'),
            'size'       => config('mw-pagination.size', 'md'),
            'onEachSide' => config('mw-pagination.on_each_side', 5),
            'pageName'   => config('mw-pagination.page_name', 'page'),
        ];

        return Paginator::fromLaravel($paginator, array_merge($defaults, $options));
    }
}