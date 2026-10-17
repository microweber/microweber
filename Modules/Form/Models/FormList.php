<?php
namespace Modules\Form\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;

class FormList extends Model
{
    use FiresModelLifecycleEvents;

    protected $table = 'forms_lists';

    public $timestamps = false;


    public function formsData()
    {
        return $this->hasMany(FormData::class, 'list_id');
    }
}
