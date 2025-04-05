<?php
namespace Modules\Billing\Listeners;

use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\WebhookReceived;

use Illuminate\Support\Facades\Notification;
use Modules\Billing\Notifications\UserPaymentFailedNotification;
use Modules\Billing\Notifications\UserPaymentCanceledNotification;
use MicroweberPackages\Notification\Models\NotificationMailLog;
use MicroweberPackages\User\Models\User;

class StripeEventListener
{
    /**
     * Handle received Stripe webhooks.
     *
     * @param  \Laravel\Cashier\Events\WebhookReceived  $event
     * @return void
     */
    public function handle(WebhookReceived $event)
    {
        if ($event->payload['type'] === 'invoice.payment_failed'
            || $event->payload['type'] === 'payment_intent.canceled'
        ) {

            $stripeId = false;
            if(isset($event->payload['data']['object']['customer'])){
                $stripeId = $event->payload['data']['object']['customer'];
            } else if(isset($event->payload['data']['object']['object']) and $event->payload['data']['object']['object'] == 'customer'){
                $stripeId = $event->payload['data']['object']['id'];
            }
            $user = Cashier::findBillable($stripeId);
            if ($user) {
                $findUser = User::where('id', $user['user_id'])->first();
                if ($findUser) {

                    if ($event->payload['type'] === 'payment_intent.canceled') {
                        $notification = new UserPaymentCanceledNotification([]);
                    } else {
                        $notification = new UserPaymentFailedNotification([]);
                    }

                    Notification::sendNow($user, $notification);

                    $mailLog = new NotificationMailLog();
                    $mailLog->type = 'UserPaymentFailedNotification';
                    $mailLog->notifiable_type = 'user';
                    $mailLog->notifiable_id = $findUser->id;
                    $mailLog->html = $notification->toMail($user)->render();
                    $mailLog->save();

                }

            }

        }
    }
}
