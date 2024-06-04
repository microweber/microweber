<?php

namespace MicroweberPackages\ContentData\tests;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\ContentData\Traits\ContentDataTrait;

class TestModel extends Model
{
    use ContentDataTrait;

    protected $table = 'content';

}
