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

namespace MicroweberPackages\Admin\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Admin\Http\Livewire\Filament\TopNavigationActions;


class AdminServiceProvider extends ServiceProvider
{
    public function register()
    {

        $this->app->register(AdminRouteServiceProvider::class);

        // Icons now live in microweber-packages/svg-icons (SvgIconsServiceProvider).

        View::addNamespace('admin', __DIR__.'/../resources/views');

//        \App::bind(AdminManager::class,function() {
//            return new AdminManager();
//        });

        Blade::directive('dispatchGlobalBrowserEvents', function () {
            return "<script>
           window.addEventListener('dispatch-global-browser-event', event => {
                if(mw && mw.top && typeof mw.top === 'function' && mw.top().app) {
                    mw.top().app.dispatch('dispatch-global-browser-event', {
                        'event': event.detail.event,
                        'data': event.detail.data
                    });
                 }
            });


           setTimeout(function() {

               if(mw && mw.top && typeof mw.top === 'function' && mw.top().app) {
                   mw.top().app.on('dispatch-global-browser-event', eventData => {
                   window.Livewire.dispatch(eventData.event, eventData.data);
                    });
               }


           }, 300);
</script>";
        });

    }

    public function boot()
    {

        Livewire::component('admin-top-navigation-actions', TopNavigationActions::class);

    }
}
