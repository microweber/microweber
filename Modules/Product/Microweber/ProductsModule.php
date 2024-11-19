<?php

namespace Modules\Product\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Product\Filament\ProductsModuleSettings;
use Modules\Product\Models\Product;
use \MicroweberPackages\Option\Models\Option;

class ProductsModule extends BaseModule
{
    public static string $name = 'Products Module';
    public static string $module = 'shop/products';
    public static string $icon = 'modules.product-icon';
    public static string $categories = 'products';
    public static int $position = 30;
    public static string $settingsComponent = ProductsModuleSettings::class;
    public static string $templatesNamespace = 'modules.product::templates';

    public function render()
    {
        $viewData = $this->getViewData();

        $viewData['data'] = static::getQueryBuilderFromOptions($viewData['options'])->get();

        $template = $viewData['template'] ?? 'default';

        // Populate schema_org_item_type_tag and other attributes
        $viewData['schema_org_item_type_tag'] = $this->getSchemaOrgItemTypeTag($viewData['options']);
        $viewData['show_fields'] = $this->getShowFields($viewData['options']);
        $viewData['character_limit'] = $this->getCharacterLimit($viewData['options']);
        $viewData['title_character_limit'] = $this->getTitleCharacterLimit($viewData['options']);
        $viewData['tn'] = $this->getThumbnailSize($viewData['options']);
        $viewData['read_more_text'] = $this->getReadMoreText($viewData['options']);

        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }
        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }

    public static function getQueryBuilderFromOptions($optionsArray = []): \Illuminate\Database\Eloquent\Builder
    {
        return Product::query()->where('is_active', 1);
    }

    private function getSchemaOrgItemTypeTag($options)
    {
        $schema_org_item_type = 'Product';
        if (isset($options['content_type']) && $options['content_type'] == 'page') {
            $schema_org_item_type = 'WebPage';
        } elseif (isset($options['content_type']) && $options['content_type'] == 'post') {
            $schema_org_item_type = 'Article';
        }
        return 'http://schema.org/' . ucfirst($schema_org_item_type);
    }

    private function getShowFields($options)
    {
        $show_fields = [];
        if (isset($options['data-show-thumbnail']) && $options['data-show-thumbnail']) {
            $show_fields[] = 'thumbnail';
        }
        if (isset($options['data-show-title']) && $options['data-show-title']) {
            $show_fields[] = 'title';
        }
        if (isset($options['data-show-description']) && $options['data-show-description']) {
            $show_fields[] = 'description';
        }
        if (isset($options['data-show-read-more']) && $options['data-show-read-more']) {
            $show_fields[] = 'read_more';
        }
        if (isset($options['data-show-date']) && $options['data-show-date']) {
            $show_fields[] = 'date';
        }
        return $show_fields;
    }

    private function getCharacterLimit($options)
    {
        return isset($options['data-character-limit']) ? intval($options['data-character-limit']) : 120;
    }

    private function getTitleCharacterLimit($options)
    {
        return isset($options['data-title-limit']) ? intval($options['data-title-limit']) : 200;
    }

    private function getThumbnailSize($options)
    {
        $tn_size = [150];
        if (isset($options['data-thumbnail-size'])) {
            $temp = explode('x', strtolower($options['data-thumbnail-size']));
            if (!empty($temp)) {
                $tn_size = $temp;
            }
        }
        if (!isset($tn_size[0]) || $tn_size[0] == 150) {
            $tn_size[0] = 350;
        }
        if (!isset($tn_size[1])) {
            $tn_size[1] = $tn_size[0];
        }
        return $tn_size;
    }

    private function getReadMoreText($options)
    {
        return isset($options['data-read-more-text']) ? $options['data-read-more-text'] : 'Read More';
    }
}
