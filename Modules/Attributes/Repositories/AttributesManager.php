<?php

namespace Modules\Attributes\Repositories;

use Modules\Attributes\Models\Attribute;

class AttributesManager
{

    public function getValues($params)
    {
        $attributes = $this->get($params);

        if (!empty($attributes)) {
            $res = [];
            foreach ($attributes as $attribute) {
                if (isset($attribute['attribute_name']) && isset($attribute['attribute_value'])) {
                    $res[$attribute['attribute_name']] = $attribute['attribute_value'];
                }
            }

            return $res;
        }

        return [];
    }

    public function get($data = [])
    {
        if (is_string($data)) {
            $data = parse_params($data);
        }

        $query = Attribute::query();

        if (isset($data['rel_type'])) {
            $query->where('rel_type', $data['rel_type']);
        }

        if (isset($data['rel_id'])) {
            $query->where('rel_id', $data['rel_id']);
        }

        if (isset($data['attribute_name'])) {
            $query->where('attribute_name', $data['attribute_name']);
        }

        if (isset($data['one']) && $data['one']) {
            return $query->first();
        }

        return $query->get()->toArray();
    }

    public function save($data)
    {
        if (!is_array($data)) {
            $data = parse_params($data);
        }

        if (!isset($data['id'])) {
            if (!isset($data['attribute_name'])) {
                return ['error' => "You must set 'attribute_name' parameter"];
            }
            if (!isset($data['attribute_value'])) {
                return ['error' => "You must set 'attribute_value' parameter"];
            }
        }

        if (isset($data['attribute_value']) && is_array($data['attribute_value'])) {
            $data['attribute_value'] = json_encode($data['attribute_value']);
            $data['attribute_type'] = 'array';
        }

        $attribute = Attribute::updateOrCreate(
            [
                'rel_type' => $data['rel_type'] ?? morph_name(\Modules\Content\Models\Content::class),
                'rel_id' => $data['rel_id'] ?? $data['content_id'] ?? null,
                'attribute_name' => $data['attribute_name'],
            ],
            $data
        );

        return $attribute;
    }


    public function delete($data)
    {
        if (!is_array($data)) {
            $data = parse_params($data);
        }

        if (isset($data['id'])) {
            $attribute = Attribute::find($data['id']);
            if ($attribute) {
                $attribute->delete();
            }
        } else {
            $query = Attribute::query();

            if (isset($data['rel_type'])) {
                $query->where('rel_type', $data['rel_type']);
            }

            if (isset($data['rel_id'])) {
                $query->where('rel_id', $data['rel_id']);
            }

            if (isset($data['attribute_name'])) {
                $query->where('attribute_name', $data['attribute_name']);
            }

            $query->delete();
        }
    }

}
