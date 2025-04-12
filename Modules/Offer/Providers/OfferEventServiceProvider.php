<?php

namespace Modules\Offer\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Modules\Content\Events\ContentWasCreated;
use Modules\Content\Events\ContentWasUpdated;
use Modules\Offer\Listeners\AddSpecialPriceProductListener;
use Modules\Offer\Listeners\EditSpecialPriceProductListener;
use Modules\Product\Events\ProductWasCreated;
use Modules\Product\Events\ProductWasUpdated;


class OfferEventServiceProvider extends EventServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
   /* protected $listen = [

        ContentWasCreated::class => [
            AddSpecialPriceProductListener::class
        ],
        ContentWasUpdated::class => [
            EditSpecialPriceProductListener::class
        ]
    ];*/

    public function register()
    {

        /* event_bind('mw.database.content.save.after.data', function ($special_price) {


             if (isset($special_price['id']) and $special_price['id']) {
                 if (isset($special_price['special_price']) and $special_price['special_price']) {
                     // $content = Product::findOrFail($special_price['id']);
                     $content = app()->make(Product::class);
                     $content = $content::findOrFail($special_price['id']);
                     // dd($content);
                     //   dd($content->getData());
                     $toSave = [];
                     //  $toSave['content_data'] = $content->getData();
                     $toSave['id'] = $special_price['id'];
                     $content->fill($special_price);
                     $content->save();
                     //  ProductWasUpdated::dispatch($content);
                     //event('eloquent.updated: \MicroweberPackages\Product\Models\Product', $content);

                     //
                     //   $user->save();

                     // event('eloquent.updated: App\User', $user);
                 }
             }


         });
 */

        parent::register();
    }
}
