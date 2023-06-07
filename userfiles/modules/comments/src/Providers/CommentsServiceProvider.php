<?php

namespace MicroweberPackages\Modules\Comments\Providers;

use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use MicroweberPackages\Modules\Comments\Http\LiveWire\UserCommentListComponent;
use MicroweberPackages\Modules\Comments\Http\LiveWire\UserCommentPreviewComponent;
use MicroweberPackages\Modules\Comments\Http\LiveWire\UserCommentReplyComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CommentsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-comments');
        $package->hasViews('microweber-module-comments');
        $package->hasRoute('api');
        $package->runsMigrations(true);
    }

    public function register(): void
    {
        Livewire::component('comments::user-comment-reply', UserCommentReplyComponent::class);
        Livewire::component('comments::user-comment-list', UserCommentListComponent::class);
        Livewire::component('comments::user-comment-preview', UserCommentPreviewComponent::class);

        $this->loadMigrationsFrom(normalize_path(__DIR__) . '/../database/migrations/');
        $this->loadRoutesFrom(normalize_path(__DIR__) . '/../routes/admin.php');

        View::addNamespace('comments', normalize_path(__DIR__) . '/../resources/views');

        parent::register();

    }

}
