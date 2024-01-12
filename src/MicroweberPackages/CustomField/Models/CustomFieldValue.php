<?php
namespace MicroweberPackages\CustomField\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Multilanguage\Models\Traits\HasMultilanguageTrait;

class CustomFieldValue extends Model
{
    use CacheableQueryBuilderTrait;
  //  use HasMultilanguageTrait;

    protected $table = 'custom_fields_values';
    protected $primaryKey = 'id';

    public $translatable = ['value'];

    protected $fillable = [
        'custom_field_id',
        'value',
        'position'
    ];

    public $timestamps = false;

    public $cacheTagsToClear = ['custom_fields_values','repositories','content'];

   // public $translatable = ['value'];

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
