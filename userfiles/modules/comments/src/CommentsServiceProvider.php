<?php
namespace MicroweberPackages\Modules\Comments;

use Illuminate\Support\Facades\View;
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

        View::addNamespace('comments', normalize_path((__DIR__) . '/resources/views'));

    }

}
