<?php

namespace Modules\Customer\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use Modules\Tag\Models\Tag;

/**
 * Customer Tag Pivot Model
 *
 * @property int $id
 * @property int $customer_id
 * @property int $tag_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \Modules\Customer\Models\Customer $customer
 * @property-read \Modules\Tag\Models\Tag $tag
 */
class CustomerTag extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\Customer\Events\CustomerTagWasCreated::class,
        'updated' => \Modules\Customer\Events\CustomerTagWasUpdated::class,
        'deleted' => \Modules\Customer\Events\CustomerTagWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

    use HasFactory;
    use CacheableQueryBuilderTrait;

    protected $table = 'customer_tags';

    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'tag_id',
    ];

    protected $casts = [
        'customer_id' => 'integer',
        'tag_id' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Get the customer that owns this tag.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the tag associated with this record.
     */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}
