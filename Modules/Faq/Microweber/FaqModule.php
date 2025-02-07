<?php

namespace Modules\Faq\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Accordion\Models\Accordion;
use Modules\Faq\Filament\FaqModuleSettings;
use Modules\Faq\Models\Faq;

class FaqModule extends BaseModule
{
    public static string $name = 'Faq';
    public static string $module = 'faq';
    public static string $icon = 'modules.faq-icon';
    public static string $categories = 'miscellaneous';
    public static int $position = 57;
    public static string $settingsComponent = FaqModuleSettings::class;
    public static string $templatesNamespace = 'modules.faq::templates';


    public function render()
    {
        $viewData = $this->getViewData();
        $rel_type = $this->params['rel_type'] ?? 'module';
        $rel_id = $this->params['rel_id'] ?? $this->params['id'];
        $viewData['faqs'] = Faq::where('rel_type', $rel_type)->where('rel_id', $rel_id)->orderBy('position', 'asc')->get();
        $viewData['defaults'] = [
            [
                'question' => 'Open settings and type your question',
                'answer' => 'Open settings and type your answer'
            ]
        ];
        $template = $viewData['template'] ?? 'default';

        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }


}
