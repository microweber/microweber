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

        $relId = $this->getOption('rel_id') ??  $this->params['rel_id'] ?? $this->params['id'] ?? null;
        $relType = $this->getOption('rel_type') ?? $this->params['rel_type'] ?? 'module';



        $testimonials = Testimonial::where('rel_type', $relType)
                                            ->where('rel_id', $relId)
                                            ->orderBy('position', 'asc')
                                            ->get();

        if ($testimonials->count() == 0) {
            $defaultContent = file_get_contents(module_path(self::$module) . '/default_content.json');
            $defaultContent = json_decode($defaultContent, true);
            if (isset($defaultContent['testimonials'])) {
//                foreach ($defaultContent['testimonials'] as $testimonial) {
//                    $newTestimonial = new Testimonial();
//                    $newTestimonial->fill($testimonial);
//                    $newTestimonial->rel_id = $relId;
//                    $newTestimonial->rel_type = $relType;
//                    $newTestimonial->save();
//                }
                $testimonials = $defaultContent['testimonials'];
            }
        }

        $viewData['testimonials'] = collect($testimonials);

        $viewName = $this->getViewName($viewData['template'] ?? 'default');

        return view($viewName, $viewData);
    }
}
