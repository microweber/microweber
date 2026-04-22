<?php

declare(strict_types=1);

namespace Modules\Coupons\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Coupons\Http\Resources\CouponResource;
use Modules\Coupons\Models\Coupon;
use Symfony\Component\HttpFoundation\Response;

class CouponsApiController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $limit = (int) $request->get('limit', 30);
            $isAdmin = $request->user() !== null
                && (int) $request->user()->is_admin === 1;

            $query = Coupon::query();

            // Public callers only see active coupons valid for "now" — this
            // prevents scraping expired/future promos.
            if (!$isAdmin) {
                $now = now();
                $query->where('is_active', 1)
                    ->where(function ($q) use ($now) {
                        $q->whereNull('valid_from')->orWhere('valid_from', '<=', $now);
                    })
                    ->where(function ($q) use ($now) {
                        $q->whereNull('valid_to')->orWhere('valid_to', '>=', $now);
                    });
            }

            if ($request->filled('search')) {
                $term = (string) $request->input('search');
                $query->where(function ($q) use ($term) {
                    $q->where('coupon_code', 'like', "%{$term}%")
                        ->orWhere('coupon_name', 'like', "%{$term}%");
                });
            }
            if ($request->filled('discount_type')) {
                $query->where('discount_type', (string) $request->input('discount_type'));
            }

            $coupons = $query->orderByDesc('id')->paginate($limit);
            $coupons->appends($request->except('page'));

            return CouponResource::collection($coupons);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid query parameters',
                'data' => [],
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function store(Request $request): JsonResponse
    {
        if (!$request->user() || (int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required|string|max:255|unique:cart_coupons,coupon_code',
            'coupon_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|string|in:fixed_amount,percentage',
            'discount_value' => 'required|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'uses_per_coupon' => 'nullable|integer|min:0',
            'uses_per_customer' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'free_shipping' => 'nullable|boolean',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $coupon = Coupon::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Coupon created successfully',
                'data' => new CouponResource($coupon->fresh()),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create coupon',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $isAdmin = $request->user() !== null
            && (int) $request->user()->is_admin === 1;

        if (!$isAdmin && !$coupon->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => new CouponResource($coupon),
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        if (!$request->user() || (int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'coupon_code' => 'nullable|string|max:255|unique:cart_coupons,coupon_code,' . $id,
            'coupon_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'nullable|string|in:fixed_amount,percentage',
            'discount_value' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'uses_per_coupon' => 'nullable|integer|min:0',
            'uses_per_customer' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'free_shipping' => 'nullable|boolean',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $coupon->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Coupon updated successfully',
                'data' => new CouponResource($coupon->fresh()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update coupon',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        if (!$request->user() || (int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $coupon->delete();

            return response()->json([
                'success' => true,
                'message' => 'Coupon deleted successfully',
                'data' => ['id' => $id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete coupon',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
