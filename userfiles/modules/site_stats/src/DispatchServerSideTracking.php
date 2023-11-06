<?php

namespace MicroweberPackages\SiteStats;

use AlexWestergaard\PhpGa4\Analytics;
use AlexWestergaard\PhpGa4\Event\AddShippingInfo;
use AlexWestergaard\PhpGa4\Event\AddToCart;
use AlexWestergaard\PhpGa4\Event\BeginCheckout;
use AlexWestergaard\PhpGa4\Event\Login;
use AlexWestergaard\PhpGa4\Event\PageView;
use AlexWestergaard\PhpGa4\Event\Signup;
use AlexWestergaard\PhpGa4\Item;
use MicroweberPackages\SiteStats\Models\StatsEvent;

class DispatchServerSideTracking
{

    public function dispatch()
    {
        $visitorId = 0;
        $getUtmVisitorData = UtmVisitorData::getVisitorData();
        if (isset($getUtmVisitorData['utm_visitor_id'])) {
            $visitorId = $getUtmVisitorData['utm_visitor_id'];
        }

        $measurementId = get_option('google-measurement-id', 'website');
        $apiSecret = get_option('google-measurement-api-secret', 'website');

        $analytics = Analytics::new(
            $measurementId, $apiSecret
        );
        $analytics->setClientId($visitorId);

        $getStatsEvents = StatsEvent::where('utm_visitor_id', $visitorId)->get();

        if ($getStatsEvents->count() > 0) {
            foreach ($getStatsEvents as $getStatsEvent) {

                $eventData = json_decode($getStatsEvent->event_data, true);

                $event = false;

                if ($getStatsEvent->event_action == 'logged') {
                    $event = Login::new();
                }

                if ($getStatsEvent->event_action == 'register') {
                    $event = Signup::new();
                }

                if ($getStatsEvent->event_action == 'add_shipping_info') {
                    $event = AddShippingInfo::new();
                    $event->setCurrency(get_currency_code());
                    $event->setValue($getStatsEvent->event_value);
                }

                if ($getStatsEvent->event_action == 'begin_checkout') {
                    $event = BeginCheckout::new();
                    $event->setCurrency(get_currency_code());
                    $event->setValue($getStatsEvent->event_value);

                }

                if ($getStatsEvent->event_action == 'add_to_cart') {

                    $event = AddToCart::new()
                        ->setCurrency(get_currency_code())
                        ->setValue($getStatsEvent->event_value);

                    if (!empty($eventData)) {
                        foreach ($eventData as $product) {
                            if (isset($product['price'])) {

                                $newProductItem = new Item();
                                $newProductItem->setItemId($product['rel_id']);
                                $newProductItem->setItemName($product['title']);
                                $newProductItem->setPrice($product['price']);
                                $newProductItem->setQuantity($product['qty']);

                                $event->addItem($newProductItem);
                            }
                        }
                    }
                }

                if ($event) {
                    try {
                        $analytics->addEvent($event);
                        $send = $analytics->post();
                    } catch (\Exception $e) {
                      //  dump($e->getMessage());
                    }
                }

                $getStatsEvent->delete();

            }

        }
    }
}
