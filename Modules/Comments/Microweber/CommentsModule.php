<?php

namespace Modules\Comments\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use MicroweberPackages\Module\ModuleServiceProvider;
use Modules\Comments\Filament\CommentsModuleSettingsLiveEdit;
use Modules\Comments\Services\CommentsManager;

class CommentsModule extends BaseModule
{
    public static string $name = 'Comments';
    public static string $module = 'comments';
    public static string $icon = 'modules.comments-icon';
    public static string $categories = 'comments';
    public static int $position = 1;
    public static string $settingsComponent = CommentsModuleSettingsLiveEdit::class;
    public static string $templatesNamespace = 'modules.comments::templates';

    protected CommentsManager $commentsManager;


    public function render()
    {
        $this->commentsManager = app(CommentsManager::class);
        $viewData = $this->getViewData();

        // Check if comments should be shown for current content
        if (!$this->getOption('show_on_current_content', true)) {
            return '';
        }

        // Get comments based on rel_type and rel_id
        $relType = $viewData['rel_type'] ?? $this->getDefaultRelType();
        $relId = $viewData['rel_id'] ?? $viewData['content_id'] ?? content_id();

        if (!$relId) {
            return '';
        }

        $comments = $this->getComments($relType, $relId);
        $viewData['comments'] = $comments;
        $viewData['rel_type'] = $relType;
        $viewData['rel_id'] = $relId;
        $viewData['settings'] = $this->getSettings();

        $viewName = $this->getViewName($viewData['template'] ?? 'default');
        return view($viewName, $viewData);
    }

    protected function getDefaultRelType()
    {

        return morph_name(\Modules\Content\Models\Content::class);
    }

    public function getComments($relType, $relId)
    {
        return $this->commentsManager->get([
            'rel_type' => $relType,
            'rel_id' => $relId
        ]);
    }

    protected function getSettings()
    {
        return [
            'show_user_avatar' => $this->getOption('show_user_avatar', true),
            'show_on_current_content' => $this->getOption('show_on_current_content', true),
             'allow_replies' => $this->getOption('allow_replies', true),
            'require_login' => $this->getOption('require_login', false),
            'comments_per_page' => $this->getOption('comments_per_page', 10),
            'sort_order' => $this->getOption('sort_order', 'newest'),
        ];
    }
}
