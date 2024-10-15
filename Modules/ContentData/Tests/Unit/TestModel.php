<?php

namespace Modules\ContentData\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Modules\ContentData\Traits\ContentDataTrait;

/*
* @internal
*/
class TestModel extends Model
{
    use ContentDataTrait;

    protected $table = 'content';

}
