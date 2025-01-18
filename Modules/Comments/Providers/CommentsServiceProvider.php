<?php

namespace Modules\Comments\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Comments\Filament\Resources\CommentResource;
use Modules\Comments\Livewire\UserCommentListComponent;
use Modules\Comments\Livewire\UserCommentPreviewComponent;
use Modules\Comments\Livewire\UserCommentReplyComponent;
use Modules\Comments\Livewire\Editors\TextareaComponent;
use Modules\Comments\Livewire\Modals\ReplyModal;
use Modules\Comments\Livewire\Modals\EditModal;
use Modules\Comments\Livewire\Editors\EasyMdeComponent;
use Modules\Comments\Models\GatedComment;
use Modules\Comments\Policies\CommentPolicy;
use Modules\Comments\Filament\CommentsModuleSettings;
use Modules\Comments\Filament\Pages\CommentsModuleSettingsAdmin;
use Modules\Comments\Models\Comment;
use Modules\Comments\Services\CommentsManager;

class CommentsServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Comments';

    protected string $moduleNameLower = 'comments';

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));
        
        $this->app->singleton('comments_manager', function () {
            return new CommentsManager();
        });

        $this->app->singleton(\Modules\Comments\Services\AvatarProvider::class, function ($app) {
            return new \Modules\Comments\Services\AvatarProvider();
        });

        Blade::componentNamespace('\\Modules\\Comments\\View\\Components', 'comments');

        // Register filament page for Microweber module settings
       FilamentRegistry::registerResource(CommentResource::class);
       FilamentRegistry::registerPage(CommentsModuleSettingsAdmin::class);
        FilamentRegistry::registerPage(CommentsModuleSettings::class);

        Gate::policy(GatedComment::class, CommentPolicy::class);

        Livewire::component('comments::user-comment-reply', UserCommentReplyComponent::class);
        Livewire::component('comments::user-comment-list', UserCommentListComponent::class);
        Livewire::component('comments::user-comment-preview', UserCommentPreviewComponent::class);

        // Register editor components
        Livewire::component('comments::editors.textarea', TextareaComponent::class);
        Livewire::component('comments::editors.easy-mde', EasyMdeComponent::class);
        
        // Register modal components
        Livewire::component('comments::modals.reply-modal', ReplyModal::class);
        Livewire::component('comments::modals.edit-modal', EditModal::class);

        // Register Microweber module
        Microweber::module(\Modules\Comments\Microweber\CommentsModule::class);
    }
}
