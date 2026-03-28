<?php

namespace Modules\Tag\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use Modules\Customer\Models\Customer;
use Modules\Tag\Database\Factories\TagFactory;

/**
 * @package Conner\Tagging\Models
 * @property string id
 * @property string name
 * @property string slug
 * @property bool suggest
 * @property integer count
 * @property integer tag_group_id
 * @property TagGroup group
 * @property string description
 * @method static suggested()
 * @method static inGroup(string $group)
 */
class Tag extends \Conner\Tagging\Model\Tag
{
    use CacheableQueryBuilderTrait;
    use HasFactory;

    protected static function newFactory()
    {
        return TagFactory::new();
    }

    /**
     * Get the customers associated with this tag.
     */
    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'customer_tags', 'tag_id', 'customer_id')
            ->withPivot('created_at');
    }
}
