<?php

namespace Modules\Pictures\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Media\Models\Media;
use Modules\Pictures\Filament\PicturesModuleSettings;

class PicturesModule extends BaseModule
{
    public static string $name = 'Pictures Module';
    public static string $module = 'pictures';
    public static string $icon = 'modules.pictures-icon';
    public static string $categories = 'media';
    public static int $position = 30;
    public static string $settingsComponent = PicturesModuleSettings::class;
    public static string $templatesNamespace = 'modules.pictures::templates';

    public function render()
    {
        $viewData = $this->getViewData();
        $viewName = $this->getViewName($viewData['template'] ?? 'default');


        $params = $this->getParams();
        $relType = $params['rel'] ?? $params['data-rel'] ?? 'module';
        $relId = $this->getModuleId();


        $picturesFromContent = $this->getOption('data-use-from-post');

        $openedFromContentPageId = content_id();

        if ($relType == 'content') {
            if (!isset($params['content_id']) and $openedFromContentPageId) {
                $params['content_id'] = $openedFromContentPageId;
            }
        }

        if (isset($params['content_id'])) {
            $openedFromContentPageId = $params['content_id'];

            if ($openedFromContentPageId) {
                $relType = morph_name(\Modules\Content\Models\Content::class);
                $relId = $openedFromContentPageId;
            }

        } else if ($picturesFromContent == 'y' and $openedFromContentPageId) {
            $relType = morph_name(\Modules\Content\Models\Content::class);
            $relId = $openedFromContentPageId;
        }


        $data = Media::query()
            ->where('rel_type', $relType)
            ->where('rel_id', $relId)
            ->orderBy('position', 'asc')->get()
            ->toArray();
        $viewData['data'] = $data;

        if (empty($data)) {
            $viewData['no_img'] = true;
        } else {
            $viewData['no_img'] = false;
        }

        return view($viewName, $viewData);
    }
}
