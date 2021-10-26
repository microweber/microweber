<?php
namespace MicroweberPackages\Form\Models;

use Illuminate\Database\Eloquent\Model;

class FormData extends Model
{
    protected $table = 'forms_data';

    public function formDataValues()
    {
        return $this->hasMany(FormDataValue::class);
    }
}
