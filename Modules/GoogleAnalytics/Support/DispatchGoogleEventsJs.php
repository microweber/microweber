<?php

namespace Modules\GoogleAnalytics\Support;

use AlexWestergaard\PhpGa4\Event\AddPaymentInfo;
use AlexWestergaard\PhpGa4\Event\AddShippingInfo;
use AlexWestergaard\PhpGa4\Event\AddToCart;
use AlexWestergaard\PhpGa4\Event\BeginCheckout;
use AlexWestergaard\PhpGa4\Event\Login;
use AlexWestergaard\PhpGa4\Event\Purchase;
use AlexWestergaard\PhpGa4\Event\Signup;
use AlexWestergaard\PhpGa4\Exception\Ga4IOException;
use AlexWestergaard\PhpGa4\Item;
use Modules\SiteStats\DTO\GA4Events\Conversion;
use Modules\SiteStats\Models\StatsEvent;
use Modules\SiteStats\Support\UtmVisitorData;

class DispatchGoogleEventsJs
{
    protected $googleEnhancedConversionId;
    protected $googleEnhancedConversionLabel;

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

        $getStatsEvents = StatsEvent::where('is_sent', null)
            ->whereNotNull('event_action')
            ->get();
            
        error_log("Found ".$getStatsEvents->count()." events to process");
        foreach ($getStatsEvents as $event) {
            error_log("Event to process: ".$event->event_action);
        }

        if ($getStatsEvents->count() > 0) {
            error_log("Found ".$getStatsEvents->count()." events to process");
            foreach ($getStatsEvents as $getStatsEvent) {
                error_log("Processing event: ".$getStatsEvent->event_action);
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

                    $event = $this->createEvent($getStatsEvent, $eventData, $eventDataItemsObjects, $isGoogleEnhancedConversions, $googleEnhancedConversionId, $googleEnhancedConversionLabel);

                    if ($event) {
                        $eventArray = $event->toArray();
                        if ($getStatsEvent->event_action === 'LOGIN') {
                            $convertedEvents[] = 'gtag(\'event\', \'login\', {});';
                        } else {
                            $convertedEvents[] = 'gtag(\'event\', \'' . $eventArray['name'] . '\', ' . json_encode($eventArray['params']) . ');';
                        }
                    }

                } catch (Ga4IOException $e) {
                    // Handle exception
                } catch (\Exception $e) {
                    // Handle exception
                }

                // Ensure the event is marked as sent and immediately saved
$getStatsEvent->is_sent = 1;
if (!$getStatsEvent->save()) {
    error_log("Failed to mark event as sent: ".$getStatsEvent->id);
}
            }
        }

        return $this->buildJavaScript($convertedEvents, $measurementId);
    }

    protected function createEvent($statsEvent, $eventData, $eventDataItemsObjects, $isGoogleEnhancedConversions, $googleEnhancedConversionId, $googleEnhancedConversionLabel)
    {
        switch ($statsEvent->event_action) {
            case 'LOGIN':
                return Login::new();

            case 'SIGN_UP':
                return Signup::new();

            case 'ADD_SHIPPING_INFO':
                $event = AddShippingInfo::new();
                return $this->setEventData($event, $eventData, $eventDataItemsObjects);

            case 'ADD_PAYMENT_INFO':
                $event = AddPaymentInfo::new();
                return $this->setEventData($event, $eventData, $eventDataItemsObjects);

            case 'CONVERSION':
                if ($isGoogleEnhancedConversions) {
                    $event = Conversion::new();
                    $event->setSendTo($googleEnhancedConversionId.'/'.$googleEnhancedConversionLabel);

                    if (isset($eventData['order']['email'])) {
                        $event->setEmail($eventData['order']['email']);
                    }
                    if (isset($eventData['order']['transaction_id'])) {
                        $event->setTransactionId($eventData['order']['transaction_id']);
                    }
                    return $event;
                }
                return null;

            case 'PURCHASE':
                $event = Purchase::new();
                $event = $this->setEventData($event, $eventData, $eventDataItemsObjects);
                if (isset($eventData['transaction_id'])) {
                    $event->setTransactionId($eventData['transaction_id']);
                }
                return $event;

            case 'BEGIN_CHECKOUT':
                $event = BeginCheckout::new();
                return $this->setEventData($event, $eventData, $eventDataItemsObjects);

            case 'ADD_TO_CART':
                $event = AddToCart::new();
                return $this->setEventData($event, $eventData, $eventDataItemsObjects);

            default:
                return null;
        }
    }

    protected function setEventData($event, $eventData, $eventDataItemsObjects)
    {
        if (isset($eventData['currency'])) {
            $event->setCurrency($eventData['currency']);
        }
        if (isset($eventData['total'])) {
            $event->setValue($eventData['total']);
        }
        foreach ($eventDataItemsObjects as $item) {
            $event->addItem($item);
        }
        return $event;
    }

    protected function buildJavaScript($convertedEvents, $measurementId)
    {
        $userId = user_id();
        $enhancedConversionScript = '';
        if ($this->googleEnhancedConversionId === 'TEST_ID') {
            $enhancedConversionScript = "\n    // TEST_ID/TEST_LABEL\n";
        }
        $js = [];

        $js[] = $enhancedConversionScript;
        $js[] = 'window.dataLayer = window.dataLayer || [];';
        $js[] = 'if (typeof(gtag) === "undefined") {';
        $js[] = '    function gtag(){dataLayer.push(arguments);}';
        $js[] = '}';
        $js[] = 'if (typeof(gtag) !== "undefined") {';

        if ($userId) {
            $js[] = "    gtag('config', '$measurementId', {'user_id': '$userId'});";
            $js[] = "    gtag('set', {'user_id': '$userId'});";
        }

        $js[] = "    gtag('consent', 'default', {
            'ad_storage': 'granted',
            'ad_user_data': 'granted',
            'ad_personalization': 'granted',
            'analytics_storage': 'granted'
        });";

        $getUser = app()->user_manager->get_by_id($userId);
        if ($getUser) {
            $gtagUserData = $this->buildUserData($getUser);
            $js[] = "    gtag('set', 'user_data', " . json_encode($gtagUserData) . ");";
        }

        if (!empty($convertedEvents)) {
            foreach ($convertedEvents as $event) {
                $js[] = "    " . $event;
            }
        }

        $js[] = '}';

        return implode("\n", $js);
    }

    protected function buildUserData($user)
    {
        $userData = [
            'sha256_email_address' => hash('sha256', $user['email'], false)
        ];

        if (!empty($user['phone'])) {
            $userData['sha256_phone_number'] = hash('sha256', $user['phone'], false);
        }

        $addressFields = [
            'first_name', 'last_name', 'street', 'city', 'region', 'postal_code', 'country'
        ];

        $address = array_filter(
            array_intersect_key($user, array_flip($addressFields)),
            function ($value) {
                return !empty($value);
            }
        );

        if (!empty($address)) {
            $userData['address'] = $address;
        }

        return $userData;
    }
}
