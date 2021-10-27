<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Comment;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;


class CommentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        include_once (__DIR__.'/helpers/comments_helpers.php');

        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');

        View::addNamespace('comment', __DIR__.'/resources/views');
    }
}
