<?php

declare(strict_types=1);

namespace MicroweberPackages\Pagination\Facades;

use MicroweberPackages\Pagination\PaginationFactory;

/**
 * @method static \MicroweberPackages\Pagination\Paginator make(array $options = [])
 * @method static \MicroweberPackages\Pagination\Paginator fromLaravel(\Illuminate\Pagination\LengthAwarePaginator $paginator, array $options = [])
 *
 * @see \MicroweberPackages\Pagination\PaginationFactory
 */
class Pagination extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PaginationFactory::class;
    }
}