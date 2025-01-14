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
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->attributes['content_type'] = 'test';
        $this->attributes['subtype'] = 'test';
    }
}
