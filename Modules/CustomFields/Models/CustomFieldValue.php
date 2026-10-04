<?php
namespace Modules\CustomFields\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

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
        // Only auto-assign a position on create when one wasn't explicitly set
        // (Filament's repeater ->orderColumn('position') supplies it on reorder).
        if (!isset($this->id) && !array_key_exists('position', $this->getAttributes())) {
            $position = CustomFieldValue::where('custom_field_id', $this->custom_field_id)->max('position');
            $this->position = $position + 1;
        }

        // MUST return parent::save()'s bool. Filament's relationship repeater does
        // `$record = $relationship->save($record)`, and Eloquent's HasMany::save()
        // returns `$model->save() ? $model : false`. Without this return, save()
        // yielded null -> the relationship returned false -> callAfterCreate() got
        // false and threw a TypeError when saving a custom field with values.
        return parent::save($options);
    }
}
