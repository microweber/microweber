<?php

namespace MicroweberPackages\Searchable\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Searchable\HasSearchableTrait;

/**
 * A model that uses the trait but defines no $searchable property.
 */
class EmptySearchableItem extends Model
{
    use HasSearchableTrait;

    protected $table = 'searchable_items';

    protected $fillable = [
        'title',
        'description',
    ];
}