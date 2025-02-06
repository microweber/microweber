<?php

namespace Modules\Logo\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Logo\Filament\LogoModuleSettings;

class LogoModule extends BaseModule
{
    /**
     * Module configuration
     */
    public static string $name = 'Logo';
    public static string $module = 'logo';
    public static string $icon = 'modules.logo-icon';
    public static string $categories = 'miscellaneous';
    public static int $position = 39;
    public static string $settingsComponent = LogoModuleSettings::class;
    public static string $templatesNamespace = 'modules.logo::templates';

    /**
     * Default logo options
     */
    private const DEFAULT_OPTIONS = [
        'logoimage' => '',
        'text' => '',
        'text_color' => '#000',
        'font_family' => 'inherit',
        'font_size' => '30',
        'size' => '200'
    ];

    /**
     * Render the logo module
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $viewData = $this->prepareViewData();

        // If no logo image and no text, return simple link
        if (empty($viewData['logoimage']) && empty($viewData['text'])) {
            return '<a href="' . site_url() . '" class="text-2xl font-semibold">Site Logo</a>';
        }

        $template = $this->resolveTemplate($viewData);
        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }

    /**
     * Prepare view data with logo options
     *
     * @return array
     */
    private function prepareViewData(): array
    {
        $viewData = $this->getViewData();
        $logoOptions = $this->getOptions();

        return array_merge(
            $viewData,
            array_merge(self::DEFAULT_OPTIONS, array_intersect_key($logoOptions, self::DEFAULT_OPTIONS))
        );
    }

    /**
     * Resolve the template to use
     *
     * @param array $viewData
     * @return string
     */
    private function resolveTemplate(array $viewData): string
    {
        $template = $viewData['template'] ?? 'default';

        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            return 'default';
        }

        return $template;
    }
}
