<?php

namespace MicroweberPackages\Category\tests;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Category\Traits\CategoryTrait;

class ContentTestModelForCategories extends Model
{
    use CategoryTrait;

    protected $table = 'content';

}

