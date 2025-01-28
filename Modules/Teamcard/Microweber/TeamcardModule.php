<?php

namespace Modules\Teamcard\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Teamcard\Filament\TeamcardModuleSettings;
use Modules\Teamcard\Models\Teamcard;

class TeamcardModule extends BaseModule
{
    public static string $name = 'Team Card';
    public static string $module = 'teamcard';
    public static string $icon = 'modules.teamcard-icon';
    public static string $categories = 'miscellaneous';
    public static int $position = 57;
    public static string $settingsComponent = TeamcardModuleSettings::class;
    public static string $templatesNamespace = 'modules.teamcard::templates';

    public function render()
    {
        $viewData = $this->getViewData();
        $rel_type = $this->params['rel_type'] ?? 'module';
        $rel_id = $this->params['rel_id'] ?? $this->params['id'];
        $teamcard = Teamcard::where('rel_type', $rel_type)->where('rel_id', $rel_id)->get();

        if ($teamcard->isEmpty()) {
            return collect($this->getDefaultTeamcard());
        }

        $viewData['teamcard'] = $teamcard;


        $template = $viewData['template'] ?? 'default';
        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }

    /**
     * Get default testimonials from JSON file
     */
    protected function getDefaultTeamcard(): array
    {
        $defaultContent = file_get_contents(module_path(self::$module) . '/default_content.json');
        $defaultContent = json_decode($defaultContent, true);

        if (!isset($defaultContent['teamcard'])) {
            return [];
        }

        return array_map(function($teamcard) {
            $teamcard['file'] = app()->url_manager->replace_site_url_back($teamcard['file']);
            return $teamcard;
        }, $defaultContent['teamcard']);
    }

}
