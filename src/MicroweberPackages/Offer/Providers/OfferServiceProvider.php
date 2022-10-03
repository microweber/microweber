<?php

namespace MicroweberPackages\Offer\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Offer\Models\Offer;
use MicroweberPackages\Offer\Repositories\OfferRepository;

class OfferServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        require_once (__DIR__.'/offers_functions.php');
    }

    public function register()
    {
        $this->app->resolving(\MicroweberPackages\Repository\RepositoryManager::class, function (\MicroweberPackages\Repository\RepositoryManager $repositoryManager) {
            $repositoryManager->extend(Offer::class, function () {
                return new OfferRepository();
            });
        });


        /**
         * @property OfferRepository   $offer_repository
         */
        $this->app->bind('offer_repository', function () {
            return $this->app->repository_manager->driver(Offer::class);;
        });



    }
}
