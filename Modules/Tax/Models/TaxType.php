<?php

namespace Modules\Tax\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;


class TaxType extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\Tax\Events\TaxTypeWasCreated::class,
        'updated' => \Modules\Tax\Events\TaxTypeWasUpdated::class,
        'deleted' => \Modules\Tax\Events\TaxTypeWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

    use CacheableQueryBuilderTrait;

    protected $table = 'tax_types';

    protected $fillable = [
        'id',
        'name',
        'type',
        'rate',
        'description',
        'settings'
    ];

    protected $casts = [
        'percent' => 'float',
        'settings' => 'array'
    ];

}
