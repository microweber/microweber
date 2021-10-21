<?php
namespace MicroweberPackages\Form\Models;

use Illuminate\Database\Eloquent\Model;

class FormDataValue extends Model
{
    protected $table = 'forms_data_values';

    public $fillable = [
      'form_data_id',
      'field_type',
      'field_key',
      'field_name',
      'field_value',
    ];

    public $casts = [
        'field_value'=>'json'
    ];

    public function formData()
    {
        return $this->hasOne(FormData::class);
    }
}
