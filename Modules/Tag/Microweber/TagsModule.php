<?php

namespace Modules\Tag\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Tag\Filament\TagsModuleSettings;

class TagsModule extends BaseModule
{
    public static string $name = 'Tags';
    public static string $module = 'tags';
    public static string $icon = 'modules.tag-icon';
    public static string $categories = 'content';
    public static int $position = 30;
    public static string $settingsComponent = TagsModuleSettings::class;
    public static string $templatesNamespace = 'modules.tag::templates';

    public function render()
    {
        $viewData = $this->getViewData();

        $cont_id = false;

        if (isset($viewData['content_id'])) {
            $cont_id = $viewData['content_id'];
        } elseif (isset($viewData['content-id'])) {
            $cont_id = $viewData['content-id'];
        }

        $root_page_id = $viewData['data-root-page-id'] ?? false;

        if (!$cont_id && $root_page_id) {
            $cont_id = $root_page_id;
        }

        $content_tags_data = false;

        $root_page_id_tags = false;
        if ($root_page_id) {
            $root_page_id_tags = $root_page_id;
        } else {
            $root_page_id_tags = main_page_id();
        }

        if ($root_page_id) {
            $tags_url_base = content_link($root_page_id_tags);
        } else {
            $tags_url_base = content_link($root_page_id_tags);
        }

        if ($cont_id) {
            $tags_url_base = content_link($cont_id);
            $content_tags_data = content_tags($cont_id, true);
        } else {
            $content_tags_data = content_tags(main_page_id(), true);
        }

        if ($tags_url_base) {
            // add end slash if none
            if (substr($tags_url_base, -1) != '/') {
                $tags_url_base = $tags_url_base . '/';
            }

            if ($tags_url_base == site_url()) {
                $tags_url_base = $tags_url_base . '?';
            }
        }

        $content_tags = []; // ALWAYS MUST BE ARRAY WITH STRING
        if ($content_tags_data) {
            foreach ($content_tags_data as $content_tag_data_item) {
                if (isset($content_tag_data_item['tag_name'])) {
                    $content_tags[] = $content_tag_data_item['tag_name'];
                }
            }
        }

        $viewData['content_tags'] = $content_tags;
        $viewData['tags_url_base'] = $tags_url_base;

        $template = $viewData['template'] ?? 'default';

        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
