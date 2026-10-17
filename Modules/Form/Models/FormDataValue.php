<?php
namespace Modules\Form\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;

class FormDataValue extends Model
{
    use FiresModelLifecycleEvents;

    public $timestamps = false;
    protected $table = 'forms_data_values';

    public $fillable = [
      'form_data_id',
      'field_type',
      'field_key',
      'field_name',
      'field_value',
      'field_value_json',
    ];

    public $casts = [
        'field_value_json'=>'array'
    ];

    public function formData()
    {
        return $this->hasOne(FormData::class);
    }
}
