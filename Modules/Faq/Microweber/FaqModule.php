<?php

namespace Modules\Faq\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;

class FaqModule extends BaseModule
{
    public static string $name = 'FAQ Module';
    public static string $icon = 'heroicon-o-question-mark-circle';
    public static string $categories = 'information, support';
    public static int $position = 1;

    public function render()
    {
        $faqs = \Modules\Faq\Models\FaqItem::all();
        return view('modules.faq::templates.default', compact('faqs'));
    }
}

