<?php
namespace MicroweberPackages\CustomField;

use Illuminate\Database\Eloquent\Model;

class CustomFieldValue extends Model
{
    protected $table = 'custom_fields_values';
    protected $primaryKey = 'id';

    protected $fillable = [
        'custom_field_id',
        'value',
        'position'
    ];

    public $timestamps = false;

    public function customField()
    {
        return $this->hasOne(CustomField::class);
    }
}
