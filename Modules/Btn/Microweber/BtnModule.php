<?php

namespace Modules\Btn\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Btn\Filament\BtnModuleSettings;

class BtnModule extends BaseModule
{
    public static string $name = 'Button';
    public static string $icon = 'heroicon-o-rectangle-stack';
    public static string $categories = 'other';
    public static int $position = 2;
    public static string $settingsComponent = BtnModuleSettings::class;

    public static string $templatesNamespace = 'modules.btn::templates';
    public function render()
    {

        return view('modules.btn::templates.default', $this->templateData());
    }

    public function templateData()
    {
        $templateData = [];
        $templateData['id'] = $this->params['id'];
        $templateData['style'] = '';
        $templateData['size'] = '';
        $templateData['popupContent'] = '';
        $templateData['url'] = '';
        $templateData['blank'] = '';
        $templateData['text'] = 'Button';
        $templateData['icon'] = '';
        $templateData['iconPosition'] = '';
        $templateData['action'] = '';
        $templateData['attributes'] = '';

        $moduleOptions = get_module_options($this->params['id']);

        if (!empty($moduleOptions)) {
            foreach ($moduleOptions as $btnOption) {
                $templateData[$btnOption['option_key']] = $btnOption['option_value'];
            }
        }
        if (isset($templateData['link'])) {
            $btnOptionsLink = json_decode($templateData['link'], true);
            if (isset($btnOptionsLink['url'])) {
                $templateData['url'] = $btnOptionsLink['url'];
            }
            if (isset($btnOptionsLink['data']['id']) && isset($btnOptionsLink['data']['type']) && $btnOptionsLink['data']['type'] == 'category') {
                $templateData['url'] = category_link($btnOptionsLink['data']['id']);
            } else {
                if (isset($btnOptionsLink['data']['id'])) {
                    $templateData['url'] = content_link($btnOptionsLink['data']['id']);
                }
            }
        }

        return $templateData;

    }


}
