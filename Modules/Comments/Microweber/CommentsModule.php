<?php

namespace Modules\Comments\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use MicroweberPackages\Module\ModuleServiceProvider;
use Modules\Comments\Filament\CommentsModuleSettingsLiveEdit;


class CommentsModule extends BaseModule
{
    public static string $name = 'Comments';
    public static string $module = 'comments';
    public static string $icon = 'modules.comments-icon';
    public static string $categories = 'comments';
    public static int $position = 1;
    public static string $settingsComponent = CommentsModuleSettingsLiveEdit::class;
    public static string $templatesNamespace = 'modules.comments::templates';


    public function render()
    {
        $viewData = $this->getViewData();
        $comments = $this->getComments($viewData['content_id'] ?? null);
        $viewName = $this->getViewName($viewData['template'] ?? 'default');
        return view($viewName, $viewData);
    }
    public function getComments($contentId)
    {
        return mw()->comments_manager->get($contentId);
    }

}
