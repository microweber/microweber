<?php

namespace MicroweberPackages\Post;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Database\Observers\BaseModelObserver;
use MicroweberPackages\Post\Models\Post;
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
        Post::observe(BaseModelObserver::class);
        Post::observe(PostObserver::class);

        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
    }

}
