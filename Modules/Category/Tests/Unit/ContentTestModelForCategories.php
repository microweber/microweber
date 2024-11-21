<?php

namespace Modules\Category\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Modules\Category\Traits\CategoryTrait;

class ContentTestModelForCategories extends Model
{
    use CategoryTrait;

    protected $table = 'content';

}

