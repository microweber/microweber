<?php

namespace MicroweberPackages\Offer\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

    ];

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
                    //event('eloquent.updated: \Modules\Product\Models\Product', $content);

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
