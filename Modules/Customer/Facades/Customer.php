<?php

namespace Modules\Customer\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Customer\Services\CustomerManager;

class Customer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CustomerManager::class;
    }
}