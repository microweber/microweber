<?php

namespace Modules\HighlightCode\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\HighlightCode\Filament\HighlightCodeModuleSettings;

class HighlightCodeModule extends BaseModule
{
    public static string $name = 'HighlightCode';
    public static string $module = 'highlight_code';
    public static string $icon = 'heroicon-o-map';
    public static string $categories = 'other';
    public static int $position = 1;
    public static string $settingsComponent = HighlightCodeModuleSettings::class;
    public static string $templatesNamespace = 'modules.highlight_code::templates';

    public function render()
    {
        $viewData = $this->getViewData();

        $text = get_option('text', $this->params['id']);

        if($text == false) {
            $text = '<?php print "Hello Wordld"; ?>';
        }
        $viewData['text'] = $text;
        return view('modules.highlight_code::templates.default', $viewData);
     }

}
