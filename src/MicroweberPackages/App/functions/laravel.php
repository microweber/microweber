<?php



function livewire_component_exists($alias): bool
{
    try {
        return app('livewire.factory')->exists($alias);
    } catch (\Throwable $e) {
        return false;
    }
}

if (!function_exists('morph_name')) {
//from https://laracasts.com/discuss/channels/eloquent/getting-models-morph-class-without-an-instance
    function morph_name($morphableType)
    {
        switch ($morphableType) {
            case 'content':
                return \Modules\Content\Models\Content::class;
            case 'categories':
                return \Modules\Category\Models\Category::class;
            case 'media':
                return \Modules\Media\Models\Media::class;
            case 'menus':
                return \Modules\Menu\Models\Menu::class;
            case 'tags':
                return \Modules\Tag\Models\Tag::class;
            case 'custom_fields':
                return \Modules\CustomFields\Models\CustomField::class;
            case 'content_fields':
                return \Modules\ContentField\Models\ContentField::class;

            case 'content_data':
                return \Modules\ContentData\Models\ContentData::class;
            case 'custom_fields_values':
                return \Modules\CustomFields\Models\CustomFieldValue::class;

        }


        if (class_exists($morphableType)) {
            return (new $morphableType)->getMorphClass();
        }


        return $morphableType;
    }

}
if (!function_exists('is_collection')) {

    function is_collection($var)
    {
        return is_object($var) && $var instanceof \Illuminate\Support\Collection;
    }
}
