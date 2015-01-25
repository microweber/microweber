<?php


class Field extends BaseModel
{

    public $table = 'custom_fields';


    public function values()
    {
        return $this->morphMany('FieldValue', 'custom_field_id');
    }

}

