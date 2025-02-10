<?php

namespace Modules\LayoutContent\Microweber;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\LayoutContent\Filament\LayoutContentModuleSettings;
use Modules\LayoutContent\Models\LayoutContentItem;

class LayoutContentModule extends BaseModule
{
    /**
     * Module configuration
     */
    public static string $name = 'Layout Content Module';
    public static string $module = 'layout_content';
    public static string $icon = 'modules.layout_content-icon';
    public static string $categories = 'content';
    public static int $position = 30;
    public static string $settingsComponent = LayoutContentModuleSettings::class;
    public static string $templatesNamespace = 'modules.layout_content::templates';

    public $defaultSettings = [
        'title' => '',
        'description' => '',
        'align' => 'center',
        'maxColumns' => '3',
        'maxColumnsTablet' => '2',
        'maxColumnsMobile' => '1',
        'buttonLink' => '#',
        'buttonText' => '',
    ];

    /**
     * Render the testimonials module
     */
    public function render(): View
    {
        $viewData = $this->getViewData();
        foreach ($this->defaultSettings as $key => $value) {
            $viewData[$key] = $this->getOption($key, $value);
        }
        $viewData['contents'] = $this->getContents();

        $viewName = $this->getViewName($viewData['template'] ?? 'default');

        return view($viewName, $viewData);
    }

    /**
     * Get contents for the current context
     */
    protected function getContents(): Collection
    {
        $relId = $this->getRelId();
        $relType = $this->getRelType();

        $items = LayoutContentItem::where('rel_type', $relType)
            ->where('rel_id', $relId)
            ->orderBy('position', 'asc')
            ->get();

        if ($items->isEmpty()) {
            return collect($this->getDefaultContents());
        }

        return $items;
    }

    /**
     * Get default contents from JSON file
     */
    protected function getDefaultContents(): array
    {
        $defaultSettings = file_get_contents(module_path(self::$module) . '/default_settings.json');
        $defaultSettings = json_decode($defaultSettings, true);

        if (!isset($defaultSettings['contents'])) {
            return [];
        }

        return array_map(function($layoutContent) {
            $layoutContent['contents'] = app()->url_manager->replace_site_url_back($layoutContent['image']);
            return new LayoutContentItem($layoutContent);
        }, $defaultSettings['contents']);
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
