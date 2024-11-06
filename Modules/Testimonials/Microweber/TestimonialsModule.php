<?php

namespace Modules\Testimonials\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Testimonials\Filament\TestimonialsModuleSettings;
use Modules\Testimonials\Models\Testimonial;

class TestimonialsModule extends BaseModule
{
    public static string $name = 'Testimonials Module';
    public static string $module = 'testimonials';
    public static string $icon = 'modules.testimonials-icon';
    public static string $categories = 'content';
    public static int $position = 30;
    public static string $settingsComponent = TestimonialsModuleSettings::class;
    public static string $templatesNamespace = 'modules.testimonials::templates';

    public function render()
    {
        $viewData = $this->getViewData();
        $rel_type = $this->params['rel_type'] ?? 'module';
        $rel_id = $this->params['rel_id'] ?? $this->params['id'];
        $viewData['testimonials'] = Testimonial::where('rel_type', $rel_type)->where('rel_id', $rel_id)->orderBy('position', 'asc')->get();
        $template = $viewData['template'] ?? 'default';

        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
