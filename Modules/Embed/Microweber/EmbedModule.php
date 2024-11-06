<?php

namespace Modules\Embed\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Embed\Filament\EmbedModuleSettings;

class EmbedModule extends BaseModule
{
    public static string $name = 'Embed';
    public static string $module = 'embed';
    public static string $icon = 'modules.embed-icon';
    public static string $categories = 'miscellaneous';
    public static int $position = 38;
    public static string $settingsComponent = EmbedModuleSettings::class;
    public static string $templatesNamespace = 'modules.embed::templates';

    public function render()
    {
        $viewData = $this->getViewData();

        $sourceCode = get_option('source_code', $this->params['id']);
        $hideInLiveEdit = get_option('hide_in_live_edit', $this->params['id']);
        $isLiveEdit = is_live_edit();

        if ($hideInLiveEdit && $isLiveEdit) {
            return lnotif(_e('Click to edit Embed Code', true));
        }

        if(trim($sourceCode) == ''){
            return lnotif(_e('Click to edit Embed Code', true));
        }

        if ($sourceCode) {
            $viewData['source_code'] = $sourceCode;
        }

        $template = $viewData['template'] ?? 'default';
        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
