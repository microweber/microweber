<?php

namespace Modules\Teamcard\Microweber;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Teamcard\Filament\TeamcardModuleSettings;
use Modules\Teamcard\Models\Teamcard;

/**
 * Team Card Module
 *
 * Displays team member cards with their information and images
 */
class TeamcardModule extends BaseModule
{
    /**
     * Module configuration
     */
    public static string $name = 'Team Card';
    public static string $module = 'teamcard';
    public static string $icon = 'modules.teamcard-icon';
    public static string $categories = 'miscellaneous';
    public static int $position = 57;
    public static string $settingsComponent = TeamcardModuleSettings::class;
    public static string $templatesNamespace = 'modules.teamcard::templates';

    /**
     * Render the team card module
     */
    public function render(): View
    {
        $viewData = $this->getViewData();
        $viewData['teamcard'] = $this->getTeamCards();

        $viewName = $this->getViewName($viewData['template'] ?? 'default');

        return view($viewName, $viewData);
    }

    /**
     * Get team cards for the current context
     */
    protected function getTeamCards(): Collection
    {
        $relId = $this->getRelId();
        $relType = $this->getRelType();

        $teamCards = Teamcard::where('rel_type', $relType)
            ->where('rel_id', $relId)
            ->get();

        if ($teamCards->isEmpty()) {
            return collect($this->getDefaultTeamcard());
        }

        return $teamCards;
    }

    /**
     * Get default team cards from JSON file
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
