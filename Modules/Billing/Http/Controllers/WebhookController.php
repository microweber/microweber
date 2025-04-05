<?php

namespace Modules\Billing\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Cashier\Events\WebhookHandled;
use Laravel\Cashier\Events\WebhookReceived;
use Laravel\Cashier\Http\Middleware\VerifyWebhookSignature;

class WebhookController extends \Laravel\Cashier\Http\Controllers\WebhookController
{

    public function __construct()
    {
        if (config('cashier.webhook.secret')) {

            $this->middleware(VerifyWebhookSignature::class);
        }
    }

    public function handleWebhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        $method = 'handle' . Str::studly(str_replace('.', '_', $payload['type']));
         $custId = false;
        if(isset($payload['data']['object']['customer'])){
            $custId = $payload['data']['object']['customer'];
        } else if(isset($payload['data']['object']['object']) and $payload['data']['object']['object'] == 'customer'){
            $custId = $payload['data']['object']['id'];
        }
        $user = $this->getUserByStripeId($custId);
        if ($user) {
            WebhookReceived::dispatch($payload);
            if (method_exists($this, $method)) {
                $this->setMaxNetworkRetries();
                $response = $this->{$method}($payload);

                WebhookHandled::dispatch($payload);
                return $response;
            }
            return $this->missingMethod($payload);
        }
    }

}

