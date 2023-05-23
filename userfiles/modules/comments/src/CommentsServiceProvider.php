<?php
namespace MicroweberPackages\Modules\Comments;

use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use MicroweberPackages\Modules\Comments\Http\LiveWire\UserCommentListComponent;
use MicroweberPackages\Modules\Comments\Http\LiveWire\UserCommentReplyComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CommentsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-comments');
        $package->hasViews('microweber-module-comments');
    }

    public function register(): void
    {
        parent::register();

        $this->loadMigrationsFrom([
            __DIR__ . '/database/migrations',
        ]);
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');


        Livewire::component('comments::user-comment-reply', UserCommentReplyComponent::class);
        Livewire::component('comments::user-comment-list', UserCommentListComponent::class);

        View::addNamespace('comments', normalize_path((__DIR__) . '/resources/views'));

    }

}
