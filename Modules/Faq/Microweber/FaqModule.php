<?php

namespace Modules\Faq\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
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

        $relType = $this->getRelType();
        $relId = $this->getRelId();

        $faqs = Faq::where('is_active', true)
            ->byRelation($relType, $relId)
            ->orderBy('position')
            ->get();

        if ($faqs->isEmpty() && !$relType && !$relId) {
            $faqs = collect($this->getDefaultFaqs());
        }

        $viewData = array_merge($viewData, [
            'faqs' => $faqs,
            'rel_type' => $relType,
            'rel_id' => $relId
        ]);

        return view(static::$templatesNamespace . '.default', $viewData);
    }

    protected function getRelType()
    {
        return $this->getOption('rel_type') ?: $this->params['rel_type'] ?: request()->get('rel_type');
    }

    protected function getRelId()
    {
        return $this->getOption('rel_id') ?: $this->params['rel_id'] ?: request()->get('rel_id');
    }

    public function getDefaultFaqs()
    {
        return [
            [
                'question' => 'What is this?',
                'answer' => 'This is a default FAQ answer.',
                'position' => 0,
                'is_active' => true
            ],
        ];
    }
}
