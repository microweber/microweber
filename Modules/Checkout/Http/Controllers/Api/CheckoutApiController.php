<?php

declare(strict_types=1);

namespace Modules\Checkout\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Checkout\Services\CheckoutService;
use Modules\Order\Models\Order;
use Symfony\Component\HttpFoundation\Response;

class CheckoutApiController extends Controller
{
    public function __construct(
        private readonly CheckoutService $checkoutService
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/module/checkout",
     *     operationId="api.module.checkout.index",
     *     tags={"Checkout"},
     *     summary="Get the current checkout state",
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $userInfo = $this->checkoutService->getUserInfo();
            $cart = app(\Modules\Cart\Services\CartService::class)->getCart();
            $totals = app(\Modules\Cart\Services\CartTotalsService::class)->totals();

            if (empty($cart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty',
                ], Response::HTTP_BAD_REQUEST);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'cart' => [
                        'items' => $cart,
                        'totals' => $totals,
                    ],
                    'customer' => $userInfo,
                    'shipping_methods' => $this->getShippingMethods(),
                    'payment_methods' => $this->getPaymentMethods(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get checkout data',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/module/checkout",
     *     operationId="api.module.checkout.store",
     *     tags={"Checkout"},
     *     summary="Place the order",
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'required|string|max:500',
            'address2' => 'nullable|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'shipping_provider_id' => 'nullable|integer',
            'payment_provider_id' => 'nullable|integer',
            'terms' => 'nullable|boolean',
            'notes' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = $request->all();

            // Process checkout
            $result = $this->checkoutService->checkout($data);

            if (isset($result['error'])) {
                return response()->json([
                    'success' => false,
                    'message' => is_array($result['error']) ? implode(', ', $result['error']) : $result['error'],
                ], Response::HTTP_BAD_REQUEST);
            }

            return response()->json([
                'success' => true,
                'message' => $result['success'] ?? 'Order placed successfully',
                'data' => [
                    'order_id' => $result['order_id'] ?? null,
                    'redirect' => $result['redirect'] ?? null,
                    'order_completed' => $result['order_completed'] ?? false,
                    'is_paid' => $result['is_paid'] ?? false,
                    'transaction_id' => $result['transaction_id'] ?? null,
                ],
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process checkout',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/module/checkout",
     *     operationId="api.module.checkout.update",
     *     tags={"Checkout"},
     *     summary="Update checkout fields",
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $data = $request->all();

            // Update session with customer data
            foreach ($data as $key => $value) {
                $this->checkoutService->setUserInfo($key, $value);
            }

            // Get updated user info
            $userInfo = $this->checkoutService->getUserInfo();

            return response()->json([
                'success' => true,
                'message' => 'Checkout data updated',
                'data' => [
                    'customer' => $userInfo,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update checkout data',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/module/checkout/validate",
     *     operationId="api.module.checkout.validate.validate",
     *     tags={"Checkout"},
     *     summary="Validate checkout before placing the order",
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function validate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'shipping_provider_id' => 'nullable|integer',
            'payment_provider_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            // Validate cart
            $cart = app(\Modules\Cart\Services\CartService::class)->getCart();

            if (empty($cart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty',
                    'errors' => ['cart' => ['Your cart is empty']],
                ], Response::HTTP_BAD_REQUEST);
            }

            // Validate shipping provider if provided
            $shippingProviderId = $request->get('shipping_provider_id');
            if ($shippingProviderId) {
                $provider = app()->shipping_method_manager->getProviderById($shippingProviderId);
                if (!$provider) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid shipping provider',
                        'errors' => ['shipping_provider_id' => ['Invalid shipping provider selected']],
                    ], Response::HTTP_BAD_REQUEST);
                }
            }

            // Validate payment provider if provided
            $paymentProviderId = $request->get('payment_provider_id');
            if ($paymentProviderId) {
                $provider = app()->payment_method_manager->getProviderById($paymentProviderId);
                if (!$provider) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid payment provider',
                        'errors' => ['payment_provider_id' => ['Invalid payment provider selected']],
                    ], Response::HTTP_BAD_REQUEST);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Checkout data is valid',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/module/checkout/shipping-methods",
     *     operationId="api.module.checkout.shipping.methods.shippingmethods",
     *     tags={"Checkout"},
     *     summary="List available shipping methods",
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function shippingMethods(): JsonResponse
    {
        try {
            $methods = $this->getShippingMethods();

            return response()->json([
                'success' => true,
                'data' => $methods,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get shipping methods',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/module/checkout/payment-methods",
     *     operationId="api.module.checkout.payment.methods.paymentmethods",
     *     tags={"Checkout"},
     *     summary="List available payment methods",
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function paymentMethods(): JsonResponse
    {
        try {
            $methods = $this->getPaymentMethods();

            return response()->json([
                'success' => true,
                'data' => $methods,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get payment methods',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/module/checkout/calculate-shipping",
     *     operationId="api.module.checkout.calculate.shipping.calculateshipping",
     *     tags={"Checkout"},
     *     summary="Calculate shipping cost for a cart",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="country", type="string"),
     *                 @OA\Property(property="zip", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function calculateShipping(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'shipping_provider_id' => 'required|integer',
            'country' => 'nullable|string',
            'city' => 'nullable|string',
            'zip' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $shippingProviderId = $request->get('shipping_provider_id');
            $data = [
                'country' => $request->get('country'),
                'city' => $request->get('city'),
                'zip' => $request->get('zip'),
            ];

            $cost = $this->checkoutService->getShippingCost($data);

            return response()->json([
                'success' => true,
                'data' => [
                    'shipping_provider_id' => $shippingProviderId,
                    'cost' => $cost,
                    'currency' => get_currency_code() ?? 'USD',
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate shipping',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/module/checkout/order/{orderReferenceId}",
     *     operationId="api.module.checkout.order.orderreferenceid.orderstatus",
     *     tags={"Checkout"},
     *     summary="Look up the status of a placed order",
     *     @OA\Parameter(
     *         name="orderReferenceId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function orderStatus(string $orderReferenceId): JsonResponse
    {
        try {
            $order = Order::where('order_reference_id', $orderReferenceId)
                ->orWhere('id', $orderReferenceId)
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found',
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'order_id' => $order->id,
                    'order_reference_id' => $order->order_reference_id,
                    'status' => $order->order_status,
                    'is_paid' => (bool) $order->is_paid,
                    'payment_status' => $order->payment_status,
                    'amount' => $order->amount,
                    'currency' => $order->currency,
                    'created_at' => $order->created_at?->toIso8601String(),
                    'updated_at' => $order->updated_at?->toIso8601String(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get order status',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get available shipping methods.
     *
     * @return array
     */
    private function getShippingMethods(): array
    {
        $methods = [];

        if (function_exists('app') && app()->shipping_method_manager) {
            $providers = app()->shipping_method_manager->getProviders();
            foreach ($providers as $provider) {
                if (isset($provider['is_active']) && $provider['is_active']) {
                    $methods[] = [
                        'id' => $provider['id'],
                        'provider' => $provider['provider'],
                        'name' => $provider['name'] ?? $provider['provider'],
                        'description' => $provider['description'] ?? null,
                        'is_active' => $provider['is_active'],
                    ];
                }
            }
        }

        return $methods;
    }

    /**
     * Get available payment methods.
     *
     * @return array
     */
    private function getPaymentMethods(): array
    {
        $methods = [];

        if (function_exists('app') && app()->payment_method_manager) {
            $providers = app()->payment_method_manager->getProviders();
            foreach ($providers as $provider) {
                if (isset($provider['is_active']) && $provider['is_active']) {
                    $methods[] = [
                        'id' => $provider['id'],
                        'provider' => $provider['provider'],
                        'name' => $provider['name'] ?? $provider['provider'],
                        'description' => $provider['description'] ?? null,
                        'is_active' => $provider['is_active'],
                        'supports_webhooks' => $provider['supports_webhooks'] ?? false,
                    ];
                }
            }
        }

        return $methods;
    }
}
