<?php

namespace Modules\Attributes\Concerns;


use Modules\Attributes\Models\Attribute;

trait HasAttributes
{
    public function getAttributesList()
    {
        return Attribute::where('rel_type', get_class($this))
            ->where('rel_id', $this->id)
            ->get()
            ->pluck('attribute_value', 'attribute_name')
            ->toArray();
    }

    public function setAttribute($name, $value)
    {
        $attribute = Attribute::updateOrCreate(
            [
                'rel_type' => get_class($this),
                'rel_id' => $this->id,
                'attribute_name' => $name,
            ],
            [
                'attribute_value' => $value,
            ]
        );

        return $attribute;
    }

    public function getAttribute($name)
    {
        $attribute = Attribute::where('rel_type', get_class($this))
            ->where('rel_id', $this->id)
            ->where('attribute_name', $name)
            ->first();

        return $attribute ? $attribute->attribute_value : null;
    }
}
