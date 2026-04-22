<?php

declare(strict_types=1);

namespace Modules\Shipping\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Shipping\Http\Resources\ShippingProviderResource;
use Modules\Shipping\Models\ShippingProvider;
use Symfony\Component\HttpFoundation\Response;

class ShippingApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/module/shipping",
     *     operationId="api.module.shipping.index",
     *     tags={"Shipping"},
     *     summary="List shipping methods",
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $limit = (int) $request->get('limit', 30);
            $isAdmin = $request->user() !== null
                && (int) $request->user()->is_admin === 1;

            $query = ShippingProvider::query();

            if (!$isAdmin) {
                $query->where('is_active', 1);
            }

            if ($request->filled('search')) {
                $term = (string) $request->input('search');
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                        ->orWhere('provider', 'like', "%{$term}%");
                });
            }

            $providers = $query->orderBy('position')->orderByDesc('id')->paginate($limit);
            $providers->appends($request->except('page'));

            return ShippingProviderResource::collection($providers);
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
     *     path="/api/module/shipping",
     *     operationId="api.module.shipping.store",
     *     tags={"Shipping"},
     *     summary="Create a new shipping method",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — admin required")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        if (!$request->user() || (int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'provider' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
            'position' => 'nullable|integer',
            'settings' => 'nullable|array',
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
            if (isset($data['settings']) && is_array($data['settings'])) {
                $data['settings'] = json_encode($data['settings']);
            }

            $provider = ShippingProvider::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Shipping provider created successfully',
                'data' => new ShippingProviderResource($provider->fresh()),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create shipping provider',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/module/shipping/{id}",
     *     operationId="api.module.shipping.id.show",
     *     tags={"Shipping"},
     *     summary="Show a single shipping method",
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
        $provider = ShippingProvider::find($id);

        if (!$provider) {
            return response()->json([
                'success' => false,
                'message' => 'Shipping provider not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $isAdmin = $request->user() !== null
            && (int) $request->user()->is_admin === 1;

        if (!$isAdmin && !$provider->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Shipping provider not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => new ShippingProviderResource($provider),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/module/shipping/{id}",
     *     operationId="api.module.shipping.id.update",
     *     tags={"Shipping"},
     *     summary="Update a shipping method",
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
        if (!$request->user() || (int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $provider = ShippingProvider::find($id);

        if (!$provider) {
            return response()->json([
                'success' => false,
                'message' => 'Shipping provider not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'provider' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
            'position' => 'nullable|integer',
            'settings' => 'nullable|array',
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
            if (isset($data['settings']) && is_array($data['settings'])) {
                $data['settings'] = json_encode($data['settings']);
            }

            $provider->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Shipping provider updated successfully',
                'data' => new ShippingProviderResource($provider->fresh()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update shipping provider',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/module/shipping/{id}",
     *     operationId="api.module.shipping.id.destroy",
     *     tags={"Shipping"},
     *     summary="Delete a shipping method",
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
        if (!$request->user() || (int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $provider = ShippingProvider::find($id);

        if (!$provider) {
            return response()->json([
                'success' => false,
                'message' => 'Shipping provider not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $provider->delete();

            return response()->json([
                'success' => true,
                'message' => 'Shipping provider deleted successfully',
                'data' => ['id' => $id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete shipping provider',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
