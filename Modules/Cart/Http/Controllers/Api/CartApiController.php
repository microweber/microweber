<?php

declare(strict_types=1);

namespace Modules\Cart\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Cart\Services\CartService;
use Modules\Cart\Services\CartTotalsService;
use Symfony\Component\HttpFoundation\Response;

class CartApiController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly CartTotalsService $cartTotalsService
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/module/cart",
     *     operationId="api.module.cart.index",
     *     tags={"Cart"},
     *     summary="List cart items",
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $cart = $this->cartService->getCart($request->all());
            $totals = $this->cartTotalsService->totals();

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $cart,
                    'totals' => $totals,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve cart',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/module/cart",
     *     operationId="api.module.cart.store",
     *     tags={"Cart"},
     *     summary="Create a new cart item",
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        // audit-test 2026-05-07 Cart deep-pass finding #4 (BUG HIGH):
        // controller had no `price` validation rule, so an attacker could
        // submit any negative or huge value. The CartService server-side
        // gate (processCustomFields loose-equality compare) is the real
        // security boundary, but the API edge needs to refuse obviously-
        // malformed prices. Bound to nullable|numeric|min:0|max:999999.
        // Deeper fix (compute canonical price server-side, ignore client
        // $data['price']) tracked under TICKET-AP.
        $validator = Validator::make($request->all(), [
            'content_id' => 'required|integer|exists:content,id',
            'qty' => 'nullable|integer|min:1',
            'title' => 'nullable|string|max:500',
            'price' => 'nullable|numeric|min:0|max:999999',
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
            $data['for'] = morph_name(\Modules\Content\Models\Content::class);
            $data['for_id'] = $data['content_id'];

            $result = $this->cartService->updateCart($data);

            if (isset($result['error'])) {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'],
                ], Response::HTTP_BAD_REQUEST);
            }

            return response()->json([
                'success' => true,
                'message' => $result['success'] ?? 'Item added to cart',
                'data' => $result,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to cart',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/module/cart/{id}",
     *     operationId="api.module.cart.id.update",
     *     tags={"Cart"},
     *     summary="Update a cart item",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'qty' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = [
                'id' => $id,
                'qty' => $request->get('qty'),
            ];

            $result = $this->cartService->updateItemQty($data);

            if (isset($result['error'])) {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'],
                ], Response::HTTP_BAD_REQUEST);
            }

            return response()->json([
                'success' => true,
                'message' => $result['success'] ?? 'Item quantity updated',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart item',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/module/cart/{id}",
     *     operationId="api.module.cart.id.destroy",
     *     tags={"Cart"},
     *     summary="Delete a cart item",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $result = $this->cartService->removeItem($id);

            if (isset($result['error'])) {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'],
                ], Response::HTTP_BAD_REQUEST);
            }

            return response()->json([
                'success' => true,
                'message' => $result['success'] ?? 'Item removed from cart',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove cart item',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/module/cart/empty",
     *     operationId="api.module.cart.empty.empty",
     *     tags={"Cart"},
     *     summary="Empty the cart",
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function empty(): JsonResponse
    {
        try {
            $result = $this->cartService->emptyCart();

            return response()->json([
                'success' => true,
                'message' => $result['success'] ?? 'Cart emptied',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to empty cart',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/module/cart/totals",
     *     operationId="api.module.cart.totals.totals",
     *     tags={"Cart"},
     *     summary="Compute cart totals",
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function totals(): JsonResponse
    {
        try {
            $totals = $this->cartTotalsService->totals();

            return response()->json([
                'success' => true,
                'data' => $totals,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get cart totals',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/module/cart/coupon",
     *     operationId="api.module.cart.coupon.applycoupon",
     *     tags={"Cart"},
     *     summary="Apply a coupon to the cart",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"code"},
     *                 @OA\Property(property="code", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function applyCoupon(Request $request): JsonResponse
    {
        // audit-test 2026-05-07 PM TASK-006 / TICKET-AO Gotcha #3:
        // Widened from `[\w-]+` to `[\w\-+.]+` so codes containing `+` or `.`
        // (e.g. "BUY1GET1+FREE", "v2.0-LAUNCH") pass the API edge. The cycle-36
        // tightening (min:3|max:64) is preserved.
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required|string|min:3|max:64|regex:/^[\w\-+.]+$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $couponCode = $request->get('coupon_code');

            // audit-test 2026-05-07 PM TASK-006 / TICKET-AO (SECURITY MEDIUM):
            // Was `applyCoupon($couponCode)` single-arg — the underlying
            // CouponService received NO email, NO IP, NO context, so:
            //   * per-IP and per-email rate limits silently didn't apply
            //     (`uses_per_customer` and `isValidForCustomer` short-circuit
            //     on null email/IP — see CouponService:204, 241).
            //   * Context-driven coupon rules (product_ids, category_ids,
            //     customer_group_id) silently passed (Gotcha #1).
            // Now threads Auth::user()?->email + $request->ip() + the
            // cart-derived context built by CartCouponService::buildCouponContext()
            // — same shape as the legacy `coupon_apply()` helper.
            // For guests, `Auth::user()?->email` is null; CouponService skips
            // email-keyed rate limit checks (verified at lines 204 + 241) so
            // the IP-only path applies cleanly.
            $cartCouponService = app(\Modules\Cart\Services\CartCouponService::class);
            $couponResult = $cartCouponService->applyCoupon(
                $couponCode,
                Auth::user()?->email,
                $request->ip(),
                $cartCouponService->buildCouponContext()
            );

            if (!$couponResult) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired coupon code',
                ], Response::HTTP_BAD_REQUEST);
            }

            $totals = $this->cartTotalsService->totals();

            return response()->json([
                'success' => true,
                'message' => 'Coupon applied successfully',
                'data' => [
                    'coupon' => $couponResult,
                    'totals' => $totals,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to apply coupon',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/module/cart/coupon",
     *     operationId="api.module.cart.coupon.removecoupon",
     *     tags={"Cart"},
     *     summary="Remove the applied coupon",
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function removeCoupon(): JsonResponse
    {
        try {
            // audit-test 2026-05-07 Cart deep-pass finding #2 (SECURITY HIGH):
            // the prior `session_forget('coupon_data'); session_forget('coupon_code');`
            // poked at presumed session keys directly — but CouponService
            // stores the data under different (nested) keys. Result: clicking
            // "Remove coupon" returned success but the discount kept applying
            // on subsequent totals() calls (state-inconsistency / silent
            // permanent discount after coupon validity ended). Route through
            // the service's clearCouponSession() which knows the actual keys.
            app(\Modules\Cart\Services\CartCouponService::class)->clearCouponSession();

            $totals = $this->cartTotalsService->totals();

            return response()->json([
                'success' => true,
                'message' => 'Coupon removed',
                'data' => [
                    'totals' => $totals,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove coupon',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
