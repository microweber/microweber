<?php
namespace MicroweberPackages\Form\Models;

use Illuminate\Database\Eloquent\Model;

class FormData extends Model
{
    protected $table = 'forms_data';

    protected $casts = [
        'form_values' => 'array',
    ];

    public function formData()
    {
        return $this->hasMany(FormDataValue::class);
    }
}
