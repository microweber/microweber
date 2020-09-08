<?php

namespace MicroweberPackages\Post;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Post\Observers\PostObserver;

class PostServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Post::observe(PostObserver::class);

        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');
    }

}
