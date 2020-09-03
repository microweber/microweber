<?php
namespace MicroweberPackages\CustomField\Models;

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
        return $this->belongsTo(CustomField::class);
    }

    public function save(array $options = [])
    {
        if(!isset($this->id)) {
            //Create
            $position = CustomFieldValue::where('custom_field_id', $this->custom_field_id)->max('position');
            $this->position = $position+1;
        }

        parent::save($options);
    }
}
