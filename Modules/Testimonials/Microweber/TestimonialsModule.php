<?php

namespace Modules\Testimonials\Microweber;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Testimonials\Filament\TestimonialsModuleSettings;
use Modules\Testimonials\Models\Testimonial;

class TestimonialsModule extends BaseModule
{
    /**
     * Module configuration
     */
    public static string $name = 'Testimonials Module';
    public static string $module = 'testimonials';
    public static string $icon = 'modules.testimonials-icon';
    public static string $categories = 'content';
    public static int $position = 30;
    public static string $settingsComponent = TestimonialsModuleSettings::class;
    public static string $templatesNamespace = 'modules.testimonials::templates';

    /**
     * Render the testimonials module
     */
    public function render(): View
    {
        $viewData = $this->getViewData();
        $viewData['testimonials'] = $this->getTestimonials();
        
        $viewName = $this->getViewName($viewData['template'] ?? 'default');
        
        return view($viewName, $viewData);
    }

    /**
     * Get testimonials for the current context
     */
    protected function getTestimonials(): Collection
    {
        $relId = $this->getRelId();
        $relType = $this->getRelType();

        $testimonials = Testimonial::where('rel_type', $relType)
            ->where('rel_id', $relId)
            ->orderBy('position', 'asc')
            ->get();

        if ($testimonials->isEmpty()) {
            return collect($this->getDefaultTestimonials());
        }

        return $testimonials;
    }

    /**
     * Get default testimonials from JSON file
     */
    protected function getDefaultTestimonials(): array
    {
        $defaultContent = file_get_contents(module_path(self::$module) . '/default_content.json');
        $defaultContent = json_decode($defaultContent, true);
        
        if (!isset($defaultContent['testimonials'])) {
            return [];
        }

        return array_map(function($testimonial) {
            $testimonial['client_image'] = app()->url_manager->replace_site_url_back($testimonial['client_image']);
            return $testimonial;
        }, $defaultContent['testimonials']);
    }

    /**
     * Get relation ID from options or parameters
     */
    protected function getRelId(): ?string
    {
        return $this->getOption('rel_id') 
            ?? $this->params['rel_id'] 
            ?? $this->params['id'] 
            ?? null;
    }

    /**
     * Get relation type from options or parameters
     */
    protected function getRelType(): string
    {
        return $this->getOption('rel_type') 
            ?? $this->params['rel_type'] 
            ?? 'module';
    }
}
