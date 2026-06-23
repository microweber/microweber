<?php

namespace MicroweberPackages\Repository\Tests\Stubs;

use MicroweberPackages\Repository\Repositories\CachingModelRepository;

class TestCachingRepository extends CachingModelRepository
{
    protected string $modelClass = TestModel::class;

    public function getAllItems(): array
    {
        return $this->cached(__FUNCTION__, func_get_args(), function () {
            return TestModel::all()->toArray();
        });
    }
}