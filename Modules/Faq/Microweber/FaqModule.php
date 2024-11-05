<?php

namespace Modules\Faq\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Faq\Filament\FaqModuleSettings;

class FaqModule extends BaseModule
{
    public static string $name = 'Faq';
    public static string $module = 'faq';
    public static string $icon = 'modules.faq-icon';
    public static string $categories = 'miscellaneous';
    public static int $position = 57;
    public static string $settingsComponent = FaqModuleSettings::class;

    public function render()
    {
        $viewData = $this->getViewData();
        $faqs = @json_decode($this->getOption('faqs'), true) ?? [];

        if (!$faqs) {
            $faqs = $this->getDefaultFaqs();
        }
        $viewData = array_merge($viewData, ['faqs' => $faqs]);

        return view('modules.faq::templates.default', $viewData);
    }

    public function getDefaultFaqs()
    {
        return [
            [
                'question' => 'What is this?',
                'answer' => 'This is a default FAQ answer.',
            ],
        ];
    }
}
