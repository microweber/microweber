<?php

namespace MicroweberPackages\Pagination;


class PaginationServiceProvider extends \Illuminate\Pagination\PaginationServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'pagination');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/resources/views' => $this->app->resourcePath('views/vendor/pagination'),
            ], 'laravel-pagination');
        }

        parent::boot();
    }
}
