<?php

namespace Modules\Checkout\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Models\Order;
use Modules\Payment\Enums\PaymentStatus;
use Modules\Payment\Models\Payment;
use Modules\Payment\Repositories\PaymentMethodManager;
use Modules\Payment\Events\PaymentWasProcessed;

class CheckoutPaymentController extends Controller
{


    public function return(Request $request)
    {

        $payment = $this->handlePaymentResponse($request);

        if ($payment and $payment->status === 'completed') {

            return redirect()->route('filament.checkout.resources.checkout.success')
                ->with('success', 'Payment completed successfully');
        }

        return redirect()->route('filament.checkout.resources.checkout.failed')
            ->with('error', 'Payment failed');
    }

    public function cancel(Request $request)
    {
        return redirect()->route('filament.checkout.resources.checkout.cancelled')
            ->with('info', 'Payment was cancelled');
    }

    public function notify(Request $request)
    {
        $payment = $this->handlePaymentResponse($request);

        return response()->json(['status' => 'success']);
    }

    protected function handlePaymentResponse(Request $request)
    {

        $vkey = $request->get('_vkey_url');
        $payment_verify_token = $request->get('payment_verify_token');
        $order_reference_id = $request->get('order_reference_id');

        if (!$order_reference_id) {
            return response()->json(['status' => 'error', 'message' => 'Invalid request']);
        }

        if (!$vkey || !$payment_verify_token) {
            return response()->json(['status' => 'error', 'message' => 'Invalid request']);
        }
        $encrypter = new \Illuminate\Encryption\Encrypter(md5(\Config::get('app.key') . $payment_verify_token),
            \Config::get('app.cipher'));

        $decrypt_data = $encrypter->decrypt($vkey);
        if (md5($payment_verify_token) != $decrypt_data) {
            return response()->json(['status' => 'error', 'message' => 'Invalid request']);
        }


        $order = Order::where('payment_verify_token', $request->get('payment_verify_token'))
            ->where('order_reference_id', $order_reference_id)->firstOrFail();


        $verify_request = $request->all();

        $verify_request['order'] = $order;

        if (!$order->payment_provider) {
            return false;
        }
        if (!$order->payment_provider_id) {
            return false;
        }

        $verifyPaymentResponce = app()->payment_method_manager->verifyPayment($order->payment_provider_id, $verify_request);
        $isPaymentCompletedTrue = isset($verifyPaymentResponce['status']) && $verifyPaymentResponce['status'] == 'completed';
         if ($isPaymentCompletedTrue) {

            $order->transaction_id = $verifyPaymentResponce['transactionId'];
            $order->payment_amount = $verifyPaymentResponce['amount'];
            $order->payment_currency = $verifyPaymentResponce['currency'];
            $order->is_paid = 1;
            $order->payment_status = 'completed';
            $order->payment_data = $verifyPaymentResponce['providerResponse'];
            $order->save();

           // app()->checkout_manager->mark_order_as_paid($order->id);

            // Create payment record
            $payment = Payment::create([
                'rel_type' => morph_name(Order::class),
                'rel_id' => $order->id,
                'amount' => $order->amount,
                'currency' => $order->currency,
                'status' => 'completed',
                'payment_provider' => $order->payment_provider,
                'payment_provider_id' => $order->payment_provider_id,
                'payment_data' => $verifyPaymentResponce['providerResponse'],
                'transaction_id' => $order->transaction_id,
            ]);

            event(new PaymentWasProcessed($payment));
            return $payment;
        }


    }
}
