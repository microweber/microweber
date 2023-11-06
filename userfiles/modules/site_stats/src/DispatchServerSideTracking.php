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
    public $visitorId = '';
    public $session_id = '';

    public function setVisitorId($id)
    {
        $this->visitorId = $id;
    }

    public function setSessionId($id)
    {
        $this->session_id = $id;
    }

    public function dispatch()
    {
        $measurementId = get_option('google-measurement-id', 'website');
        $apiSecret = get_option('google-measurement-api-secret', 'website');

        $analytics = Analytics::new(
            $measurementId, $apiSecret
        );
        $analytics->setClientId($this->visitorId);

        $getStatsEvents = StatsEvent::where('session_id', $this->session_id)->get();

        if ($getStatsEvents->count() > 0) {
            foreach ($getStatsEvents as $getStatsEvent) {

                $eventData = json_decode($getStatsEvent->event_data, true);

                // dump($getStatsEvent->event_action);

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
                    $analytics->addEvent($event);
                    $send = $analytics->post();
                    if ($send) {
                        $getStatsEvent->delete();
                    }
                }

            }

        }
    }
}
