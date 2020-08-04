<?php
namespace MicroweberPackages\Field;

class Field extends Model
{
    public $table = 'custom_fields';

    public function values()
    {
        return $this->morphMany('FieldValue', 'custom_field_id');
    }
}
