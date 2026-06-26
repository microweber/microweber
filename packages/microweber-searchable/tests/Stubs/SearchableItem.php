<?php

namespace MicroweberPackages\Searchable\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Searchable\HasSearchableTrait;

class SearchableItem extends Model
{
    use HasSearchableTrait;

    protected $table = 'searchable_items';

    protected $fillable = [
        'title',
        'description',
        'content',
        'email',
        'status',
        'secret',
    ];

    protected $searchable = [
        'title',
        'description',
        'content',
        'email',
        'status',
    ];

    protected $searchableByKeyword = [
        'title',
        'description',
        'content',
    ];
}