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

    public function render()
    {

        return view('modules.btn::templates.default', $this->templateData());
    }

    public function templateData()
    {
        $templateData = $this->initializeTemplateData();
        $moduleOptions = $this->getModuleOptions();

        if (!empty($moduleOptions)) {
            foreach ($moduleOptions as $btnOption) {
                $templateData[$btnOption['option_key']] = $btnOption['option_value'];
            }
        }

        $this->processLinkOptions($templateData);

        return $templateData;
    }

    private function initializeTemplateData(): array
    {
        return [
            'id' => $this->params['id'],
            'style' => '',
            'size' => '',
            'popupContent' => '',
            'url' => '',
            'blank' => '',
            'text' => '',
            'icon' => '',
            'iconPosition' => '',
            'action' => '',
            'attributes' => '',
        ];
    }

    private function getModuleOptions(): array
    {
        return get_module_options($this->params['id']);
    }

    private function processLinkOptions(array &$templateData): void
    {
        if (isset($templateData['link'])) {
            $btnOptionsLink = json_decode($templateData['link'], true);
            if (isset($btnOptionsLink['url'])) {
                $templateData['url'] = $btnOptionsLink['url'];
            }
            if (isset($btnOptionsLink['data']['id']) && isset($btnOptionsLink['data']['type']) && $btnOptionsLink['data']['type'] == 'category') {
                $templateData['url'] = category_link($btnOptionsLink['data']['id']);
            } elseif (isset($btnOptionsLink['data']['id'])) {
                $templateData['url'] = content_link($btnOptionsLink['data']['id']);
            }
        }
    }


}
