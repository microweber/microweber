<?php
namespace MicroweberPackages\SiteStats\Models;

use Illuminate\Database\Eloquent\Model;

class UserAttribute extends Model
{
    protected $table = 'attributes';

    public static function getUtmDetails($userId)
    {
        $attributes = [];
        if ($userId) {
            $getAttributes = UserAttribute::where('rel_id', $userId)
                ->where('rel_type', 'user')
                ->get();
            if ($getAttributes->count() > 0) {
                foreach ($getAttributes as $attribute) {
                    $attributes[$attribute->attribute_name] = $attribute->attribute_value;
                }
            }
        }

        return $attributes;
    }

    public static function saveAttribute($userId, $attributeName,$attributeValue)
    {
        if ($userId) {
            $checkAttribute = UserAttribute::where('attribute_name', $attributeName)
                ->where('attribute_value', $attributeValue)
                ->where('rel_id', $userId)
                ->where('rel_type', 'user')
                ->first();

            if (!$checkAttribute) {
                $saveAttribute = new UserAttribute();
                $saveAttribute->rel_id = $userId;
                $saveAttribute->rel_type = 'user';
                $saveAttribute->attribute_name = $attributeName;
                $saveAttribute->attribute_value = $attributeValue;
                $saveAttribute->save();
            }
        }
    }
}
