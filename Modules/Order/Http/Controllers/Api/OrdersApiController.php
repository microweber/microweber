<?php

declare(strict_types=1);

namespace Modules\Order\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Order\Http\Resources\OrderResource;
use Modules\Order\Models\Order;
use Symfony\Component\HttpFoundation\Response;

class OrdersApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/module/orders",
     *     operationId="api.module.orders.index",
     *     tags={"Orders"},
     *     summary="List orders",
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.',
            ], Response::HTTP_UNAUTHORIZED);
        }
        if ((int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        try {
            $limit = (int) $request->get('limit', 30);
            $query = Order::query();

            if ($request->filled('order_status')) {
                $query->where('order_status', (string) $request->input('order_status'));
            }
            if ($request->filled('is_paid')) {
                $query->where('is_paid', (int) $request->boolean('is_paid'));
            }
            if ($request->filled('customer_id')) {
                $query->where('customer_id', (int) $request->input('customer_id'));
            }
            if ($request->filled('email')) {
                $query->where('email', (string) $request->input('email'));
            }
            if ($request->filled('search')) {
                $term = (string) $request->input('search');
                $query->where(function ($q) use ($term) {
                    $q->where('order_reference_id', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%")
                        ->orWhere('first_name', 'like', "%{$term}%")
                        ->orWhere('last_name', 'like', "%{$term}%");
                });
            }

            $orders = $query->orderByDesc('id')->paginate($limit);
            $orders->appends($request->except('page'));

            return OrderResource::collection($orders);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid query parameters',
                'data' => [],
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/module/orders",
     *     operationId="api.module.orders.store",
     *     tags={"Orders"},
     *     summary="Create a new order",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — admin required")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.',
            ], Response::HTTP_UNAUTHORIZED);
        }
        if ((int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric',
            'currency' => 'nullable|string|max:10',
            'currency_code' => 'nullable|string|max:10',
            'order_status' => 'nullable|string|max:50',
            'is_paid' => 'nullable|boolean',
            'customer_id' => 'nullable|integer',
            'order_reference_id' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = $validator->validated();
            if (empty($data['order_reference_id'])) {
                $data['order_reference_id'] = 'ORDER-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);
            }
            if (array_key_exists('is_paid', $data)) {
                $data['is_paid'] = (int) $data['is_paid'];
            }

            $order = Order::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => new OrderResource($order->fresh()),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/module/orders/{id}",
     *     operationId="api.module.orders.id.show",
     *     tags={"Orders"},
     *     summary="Show a single order",
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
    public function show(Request $request, int $id): JsonResponse
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.',
            ], Response::HTTP_UNAUTHORIZED);
        }
        if ((int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => new OrderResource($order),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/module/orders/{id}",
     *     operationId="api.module.orders.id.update",
     *     tags={"Orders"},
     *     summary="Update a order",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — admin required")
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.',
            ], Response::HTTP_UNAUTHORIZED);
        }
        if ((int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric',
            'currency' => 'nullable|string|max:10',
            'order_status' => 'nullable|string|max:50',
            'is_paid' => 'nullable|boolean',
            'payment_status' => 'nullable|string|max:50',
            'transaction_id' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = $validator->validated();
            if (array_key_exists('is_paid', $data)) {
                $data['is_paid'] = (int) $data['is_paid'];
            }
            $order->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully',
                'data' => new OrderResource($order->fresh()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/module/orders/{id}",
     *     operationId="api.module.orders.id.destroy",
     *     tags={"Orders"},
     *     summary="Delete a order",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — admin required")
     * )
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.',
            ], Response::HTTP_UNAUTHORIZED);
        }
        if ((int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully',
                'data' => ['id' => $id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
