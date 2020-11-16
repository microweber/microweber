<?php
namespace MicroweberPackages\Category\Facades;

use Illuminate\Support\Facades\Facade;

class CategoryFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'category';
    }
}