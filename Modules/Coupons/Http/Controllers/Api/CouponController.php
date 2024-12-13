<?php

namespace Modules\Coupons\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Coupons\Models\Coupon;
use Modules\Coupons\Models\CouponLog;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function apply(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => __('Invalid coupon code provided.')
            ]);
        }

        if (!get_option('enable_coupons', 'shop')) {
            return response()->json([
                'error' => true,
                'message' => __('The coupon code usage is disabled.')
            ]);
        }

        $coupon = Coupon::where('coupon_code', $request->coupon_code)
            ->where('is_active', 1)
            ->first();

        if (!$coupon) {
            return response()->json([
                'error' => true,
                'message' => __('The coupon code is not valid.')
            ]);
        }

        $cartTotal = cart_sum(true);
        if ($coupon->total_amount && $cartTotal < $coupon->total_amount) {
            return response()->json([
                'error' => true,
                'message' => __('The coupon can\'t be applied because the minimum total amount is ') . currency_format($coupon->total_amount)
            ]);
        }

        $cart = get_cart([
            'session_id' => app()->user_manager->session_id(),
            'order_completed' => 0,
            'for_checkout' => true,
        ]);

        if (empty($cart)) {
            return response()->json([
                'error' => true,
                'message' => __('The coupon can\'t be applied. The shopping cart is empty.')
            ]);
        }

        // Check customer usage limits
        $customerIp = request()->ip();
        if (!$coupon->isValidForCustomer(auth()->user()?->email ?? '', $customerIp)) {
            return response()->json([
                'error' => true,
                'message' => __('The coupon cannot be applied cause maximum uses exceeded.')
            ]);
        }

        // Store coupon in session
        Session::put('coupon_code', $coupon->coupon_code);
        Session::put('coupon_id', $coupon->id);
        Session::put('discount_value', $coupon->discount_value);
        Session::put('discount_type', $coupon->discount_type);
        Session::put('applied_coupon_data', $coupon->toArray());

        return response()->json([
            'success' => true,
            'message' => __('Coupon code applied.')
        ]);
    }

    public function delete(Request $request): JsonResponse
    {
        if (!auth()->user()?->isAdmin()) {
            return response()->json([
                'status' => 'failed',
                'message' => __('Unauthorized')
            ]);
        }

        $validator = Validator::make($request->all(), [
            'coupon_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => __('Invalid coupon ID')
            ]);
        }

        $coupon = Coupon::find($request->coupon_id);
        if (!$coupon) {
            return response()->json([
                'status' => 'failed',
                'message' => __('Coupon not found')
            ]);
        }

        $coupon->delete();

        return response()->json([
            'status' => 'success',
            'message' => __('Coupon deleted successfully')
        ]);
    }

    public function save(Request $request): JsonResponse
    {
        if (!auth()->user()?->isAdmin()) {
            return response()->json([
                'error' => true,
                'message' => __('Unauthorized')
            ]);
        }

        $validator = Validator::make($request->all(), [
            'coupon_name' => 'required|string|max:255',
            'coupon_code' => 'required|string|max:255',
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'uses_per_coupon' => 'nullable|integer|min:0',
            'uses_per_customer' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first()
            ]);
        }

        // Check if coupon code exists
        $existingCoupon = Coupon::where('coupon_code', $request->coupon_code)
            ->where('id', '!=', $request->id)
            ->first();

        if ($existingCoupon) {
            return response()->json([
                'error' => true,
                'message' => __('This coupon code already exists. Please try with another.')
            ]);
        }

        $coupon = Coupon::updateOrCreate(
            ['id' => $request->id],
            $request->all()
        );

        return response()->json([
            'success' => true,
            'message' => __('Coupon saved successfully'),
            'coupon_id' => $coupon->id
        ]);
    }

    public function deleteSession(): JsonResponse
    {
        Session::forget([
            'coupon_code',
            'coupon_id',
            'discount_value',
            'discount_type',
            'applied_coupon_data'
        ]);

        return response()->json([
            'success' => true
        ]);
    }
}
