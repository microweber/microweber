<?php

namespace MicroweberPackages\Repository\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Repository\Traits\FilterableByParams;

class TestModel extends Model
{
    use FilterableByParams;

    protected $table = 'test_items';
    protected $guarded = [];
    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'status',
        'position',
    ];

    public function getSearchable(): array
    {
        return ['title', 'description', 'status'];
    }
}