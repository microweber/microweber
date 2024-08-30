<?php

namespace MicroweberPackages\Modules\GoogleAnalytics;


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
use AlexWestergaard\PhpGa4\Exception\Ga4IOException;
use MicroweberPackages\Modules\SiteStats\DTO\GA4Events\Conversion;
use MicroweberPackages\Modules\SiteStats\Models\StatsEvent;
use MicroweberPackages\Modules\SiteStats\UtmVisitorData;

class DispatchGoogleEventsJs
{

    public function convertEvents()
    {
        $convertedEvents = [];

        $visitorId = 0;
        $getUtmVisitorData = UtmVisitorData::getVisitorData();
        if (isset($getUtmVisitorData['utm_visitor_id'])) {
            $visitorId = $getUtmVisitorData['utm_visitor_id'];
        }

        $measurementId = get_option('google-measurement-id', 'website');
        $apiSecret = get_option('google-measurement-api-secret', 'website');
        $isGoogleEnhancedConversions = get_option('google-enhanced-conversions-enabled', 'website');
        $googleEnhancedConversionId = get_option('google-enhanced-conversion-id', 'website');
        $googleEnhancedConversionLabel = get_option('google-enhanced-conversion-label', 'website');

        $getStatsEvents = StatsEvent::where('is_sent', null)->where('utm_visitor_id', $visitorId)->get();

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

                    if ($getStatsEvent->event_action == 'CONVERSION') {
                        if ($isGoogleEnhancedConversions) {
                            $event = Conversion::new();
                            $event->setSendTo($googleEnhancedConversionId.'/'.$googleEnhancedConversionLabel);

                            if (isset($eventData['order']['email'])) {
                                $event->setEmail($eventData['order']['email']);
                            }
                            if (isset($eventData['order']['transaction_id'])) {
                                $event->setTransactionId($eventData['order']['transaction_id']);
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
                        $eventArray = $event->toArray();
                        $convertedEvents[] = 'gtag(\'event\', \'' . $eventArray['name'] . '\', ' . json_encode($eventArray['params']) . ');';
                    }

                } catch (Ga4IOException $e) {

                } catch (Exception $e) {
                  //  dump($e);
                }

                $getStatsEvent->is_sent = 1;
                $getStatsEvent->save();
            }
        }

        $userId = user_id();

        $convertedEventsJs = '';
        $convertedEventsJs .= 'window.dataLayer = window.dataLayer || [];' . "\n";
        $convertedEventsJs .= 'if (typeof(gtag) === "undefined") {' . "\n";
        $convertedEventsJs .= 'function gtag(){dataLayer.push(arguments);}' . "\n";
        $convertedEventsJs .= '}' . "\n";
        $convertedEventsJs .= 'if (typeof(gtag) !== "undefined") {' . "\n";

        if ($userId) {
            $convertedEventsJs .= "gtag('config', '$measurementId', {'user_id': '$userId'}); \n";
            $convertedEventsJs .= "gtag('set', {'user_id': '$userId'}); \n";
        }
        $convertedEventsJs .= "gtag('consent', 'default', {
              'ad_storage': 'granted',
              'ad_user_data': 'granted',
              'ad_personalization': 'granted',
              'analytics_storage': 'granted'
            });" . "\n";


        $getUser = app()->user_manager->get_by_id($userId);
        if ($getUser) {

            $gtagUserData = [];
            $gtagUserData['sha256_email_address'] = hash('sha256', $getUser['email'], false);

            if (!empty($getUser['phone'])) {
                $gtagUserData['sha256_phone_number'] = hash('sha256', $getUser['phone'], false);
            }

            $userDataAddress = [];
            if (!empty($getUser['first_name'])) {
                $userDataAddress['first_name'] = $getUser['first_name'];
            }
            if (!empty($getUser['last_name'])) {
                $userDataAddress['last_name'] = $getUser['last_name'];
            }
            if (!empty($getUser['street'])) {
                $userDataAddress['street'] = $getUser['street'];
            }
            if (!empty($getUser['city'])) {
                $userDataAddress['city'] = $getUser['city'];
            }
            if (!empty($getUser['region'])) {
                $userDataAddress['region'] = $getUser['region'];
            }
            if (!empty($getUser['postal_code'])) {
                $userDataAddress['postal_code'] = $getUser['postal_code'];
            }
            if (!empty($getUser['country'])) {
                $userDataAddress['country'] = $getUser['country'];
            }

            if (!empty($userDataAddress)) {
                $gtagUserData['address'] = $userDataAddress;
            }

            $convertedEventsJs .= "gtag('set', 'user_data', " . json_encode($gtagUserData) . "); \n";
        }

        if (!empty($convertedEvents)) {
            $convertedEventsJs .= implode("\n\n", $convertedEvents);
        }

        $convertedEventsJs .= "\n" . '}';

        return $convertedEventsJs;

    }
}
