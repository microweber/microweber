<?php
namespace Modules\Form\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;

class FormList extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\Form\Events\FormListWasCreated::class,
        'updated' => \Modules\Form\Events\FormListWasUpdated::class,
        'deleted' => \Modules\Form\Events\FormListWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

    protected $table = 'forms_lists';

    public $timestamps = false;


    public function formsData()
    {
        return $this->hasMany(FormData::class, 'list_id');
    }
}
