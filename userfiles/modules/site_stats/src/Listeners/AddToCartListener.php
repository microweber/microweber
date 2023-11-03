<?php
namespace MicroweberPackages\Modules\SiteStats\Listeners;

use MicroweberPackages\SiteStats\Models\StatsEvent;

class AddToCartListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        // ...
    }

    /**
     * Handle the event.
     */
    public function handle($event): void
    {

//        +product: array:14 [
//        "rel_type" => "content"
//    "rel_id" => 4
//    "session_id" => "T686Vt1xej3ITdDb8Ogb6tSKSOZkoBdBv3RXD4Gp"
//    "no_cache" => 1
//    "disable_triggers" => 1
//    "order_completed" => 0
//    "custom_fields_data" => ""
//    "custom_fields_json" => "null"
//    "allow_html" => 1
//    "price" => 10.9
//    "limit" => 1
//    "title" => "Example product one"
//    "id" => 7
//    "qty" => 2
//

//        ga('send', 'event', {
//  eventCategory: 'Cart Actions',
//  eventAction: 'Remove from Cart',
//  eventLabel: 'Product Name or SKU', // Optional: Provide product-specific information.
//});
//ga('send', 'event', {
//  eventCategory: 'Cart Actions',
//  eventAction: 'Add to Cart',
//  eventLabel: 'Product Name or SKU', // Optional: Provide product-specific information.
//});
//
//        $newStatsEvent->event_category = 'user';
//        $newStatsEvent->event_action = 'logged';
//        $newStatsEvent->event_label = 'User logged';
//        $newStatsEvent->event_value = 1;
//        $newStatsEvent->utm_source = 'user';
//        $newStatsEvent->utm_medium = 'logged';
//        $newStatsEvent->utm_campaign = 'logged';
//        $newStatsEvent->utm_term = 'logged';
//        $newStatsEvent->utm_content = 'logged';

        $newStatsEvent = new StatsEvent();
        $newStatsEvent->event_category = 'Cart Actions';
        $newStatsEvent->event_action = 'Add to Cart';
        $newStatsEvent->event_label = $event->product['title'];
        $newStatsEvent->event_value = $event->product['qty'];
        $newStatsEvent->utm_source = 'cart';
        $newStatsEvent->utm_medium = 'add';
        $newStatsEvent->utm_campaign = 'add';
        $newStatsEvent->utm_term = 'add';
        $newStatsEvent->utm_content = 'add';
        $newStatsEvent->event_timestamp = date('Y-m-d H:i:s');
        $newStatsEvent->save();

    }
}
