<?php

namespace MicroweberPackages\Searchable\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Searchable\HasSearchableTrait;

/**
 * A model with only $searchable defined (no $searchableByKeyword).
 */
class MinimalSearchableItem extends Model
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
        'email',
    ];
}