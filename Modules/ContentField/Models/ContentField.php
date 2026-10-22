<?php

namespace Modules\ContentField\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\HasCreatedByFieldsTrait;
use MicroweberPackages\Multilanguage\Models\Traits\HasMultilanguageTrait;

class ContentField extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\ContentField\Events\ContentFieldWasCreated::class,
        'updated' => \Modules\ContentField\Events\ContentFieldWasUpdated::class,
        'deleted' => \Modules\ContentField\Events\ContentFieldWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

    use HasCreatedByFieldsTrait;
    use HasMultilanguageTrait;

    protected $table = 'content_fields';
    public $timestamps = true;

    public $translatable = ['value'];

    protected $fillable = [
        'rel_type',
        'rel_id',
        'field',
        'value',
    ];

}
