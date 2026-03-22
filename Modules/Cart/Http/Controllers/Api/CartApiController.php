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
     * Get the current cart.
     *
     * @param Request $request
     * @return JsonResponse
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
     * Add item to cart.
     *
     * @param Request $request
     * @return JsonResponse
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
     * Update cart item quantity.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
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
     * Remove item from cart.
     *
     * @param int $id
     * @return JsonResponse
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
     * Empty the cart.
     *
     * @return JsonResponse
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
     * Get cart totals.
     *
     * @return JsonResponse
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
     * Apply coupon to cart.
     *
     * @param Request $request
     * @return JsonResponse
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
     * Remove coupon from cart.
     *
     * @return JsonResponse
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
