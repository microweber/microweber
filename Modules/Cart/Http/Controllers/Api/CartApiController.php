<?php

declare(strict_types=1);

namespace Modules\Cart\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
        $validator = Validator::make($request->all(), [
            'content_id' => 'required|integer|exists:content,id',
            'qty' => 'nullable|integer|min:1',
            'title' => 'nullable|string|max:500',
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
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required|string|max:255',
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

            // Get cart totals service to apply coupon
            $couponResult = app(\Modules\Cart\Services\CartCouponService::class)
                ->applyCoupon($couponCode);

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
            // Clear coupon session data
            if (function_exists('session_forget')) {
                session_forget('coupon_data');
                session_forget('coupon_code');
            }

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
