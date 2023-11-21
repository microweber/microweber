<?php

namespace MicroweberPackages\SiteStats;

use AlexWestergaard\PhpGa4\Analytics;
use AlexWestergaard\PhpGa4\Event\AddPaymentInfo;
use AlexWestergaard\PhpGa4\Event\AddShippingInfo;
use AlexWestergaard\PhpGa4\Event\AddToCart;
use AlexWestergaard\PhpGa4\Event\BeginCheckout;
use AlexWestergaard\PhpGa4\Event\Login;
use AlexWestergaard\PhpGa4\Event\PageView;
use AlexWestergaard\PhpGa4\Event\Purchase;
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

        $getStatsEvents = StatsEvent::where('is_sent', 0)->where('utm_visitor_id', $visitorId)->get();

        if ($getStatsEvents->count() > 0) {
            foreach ($getStatsEvents as $getStatsEvent) {

                try {
                    $eventData = json_decode($getStatsEvent->event_data, true);

                    $eventDataItemsObjects = [];
                    if (isset($eventData['items'])) {
                        foreach ($eventData['items'] as $eventDataItem) {
                            $eventDataItemsObjects[] = Item::new()
                                ->setItemId($eventDataItem['id'])
                                ->setItemName($eventDataItem['name'])
                                ->setPrice($eventDataItem['price'])
                                ->setQuantity($eventDataItem['quantity']);
                        }
                    }

                    $event = false;

                    if ($getStatsEvent->event_action == 'LOGIN') {
                        $event = Login::new();
                    }

                    if ($getStatsEvent->event_action == 'SIGN_UP') {
                        $event = Signup::new();
                    }

                    if ($getStatsEvent->event_action == 'ADD_SHIPPING_INFO') {

                        $event = AddShippingInfo::new();
                        $event->setCurrency($eventData['currency']);
                        //$event->setCoupon($eventData['discount']);
                        $event->setValue($eventData['total']);

                        if (!empty($eventDataItemsObjects)) {
                            foreach ($eventDataItemsObjects as $eventDataItem) {
                                $event->addItem($eventDataItem);
                            }
                        }
                    }

                    if ($getStatsEvent->event_action == 'ADD_PAYMENT_INFO') {

                        $event = AddPaymentInfo::new();
                        $event->setCurrency($eventData['currency']);
                        //$event->setCoupon($eventData['discount']);
                        $event->setValue($eventData['total']);

                        if (!empty($eventDataItemsObjects)) {
                            foreach ($eventDataItemsObjects as $eventDataItem) {
                                $event->addItem($eventDataItem);
                            }
                        }
                    }

                    if ($getStatsEvent->event_action == 'PURCHASE') {

                        $event = Purchase::new();
                        $event->setCurrency($eventData['currency']);
                        // $event->setCoupon($eventData['discount']);
                        $event->setValue($eventData['total']);
                        $event->setTransactionId($eventData['transaction_id']);

                        if (!empty($eventDataItemsObjects)) {
                            foreach ($eventDataItemsObjects as $eventDataItem) {
                                $event->addItem($eventDataItem);
                            }
                        }

                    }

                    if ($getStatsEvent->event_action == 'BEGIN_CHECKOUT') {

                        $event = BeginCheckout::new();
                        $event->setCurrency($eventData['currency']);
                        // $event->setCoupon($eventData['discount']);
                        $event->setValue($eventData['total']);

                        if (!empty($eventDataItemsObjects)) {
                            foreach ($eventDataItemsObjects as $eventDataItem) {
                                $event->addItem($eventDataItem);
                            }
                        }

                    }

                    if ($getStatsEvent->event_action == 'ADD_TO_CART') {

                        $event = AddToCart::new();
                        $event->setCurrency($eventData['currency']);
                        //$event->setCoupon($eventData['discount']);
                        $event->setValue($eventData['total']);

                        if (!empty($eventDataItemsObjects)) {
                            foreach ($eventDataItemsObjects as $eventDataItem) {
                                $event->addItem($eventDataItem);
                            }
                        }

                    }

                    if ($event) {
                        try {
                            $analytics->addEvent($event);
                            $postStatus = $analytics->post();
//
//                            dump($event);
//                            dump($postStatus);
                        } catch (\Exception $e) {
//                                        dump($e->getMessage());
//                                        dump($event);
                        }
                    }
                } catch (\TypeError $e) {
                   // dump($e);
                }

                $getStatsEvent->is_sent = 1;
                $getStatsEvent->save();
            }
        }

    }
}
