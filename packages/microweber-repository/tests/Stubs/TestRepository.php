<?php

namespace MicroweberPackages\Repository\Tests\Stubs;

use MicroweberPackages\Repository\Repositories\AbstractRepository;

class TestRepository extends AbstractRepository
{
    public $model = TestModel::class;
}