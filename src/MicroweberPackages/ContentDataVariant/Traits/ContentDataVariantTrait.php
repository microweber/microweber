<?php
namespace MicroweberPackages\ContentDataVariant\Traits;

use MicroweberPackages\ContentDataVariant\Models\ContentDataVariant;

trait ContentDataVariantTrait
{
    public function contentDataVariant()
    {
        return $this->morphMany(ContentDataVariant::class, 'rel');
    }

    public function scopeWhereContentDataVariant($query, $whereArr)
    {
        // If you want to select multiple fields, we must use whereHas in foreach
        foreach ($whereArr as $variant) {
            $query->whereHas('contentDataVariant', function ($query) use ($variant) {
                $query->where('custom_field_id', $variant['custom_field_id'])
                        ->where('custom_field_value_id', $variant['custom_field_value_id']);
            });
        }

        return $query;
    }
}
