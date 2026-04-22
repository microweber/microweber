<?php

declare(strict_types=1);

namespace Modules\Tax\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Tax\Http\Resources\TaxRateResource;
use Modules\Tax\Models\TaxRate;
use Symfony\Component\HttpFoundation\Response;

class TaxApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/module/tax",
     *     operationId="api.module.tax.index",
     *     tags={"Tax"},
     *     summary="List tax rates",
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

            $query = TaxRate::query();

            if (!$isAdmin) {
                $query->where('is_active', 1);
            }

            if ($request->filled('country_code')) {
                $query->where('country_code', strtoupper((string) $request->input('country_code')));
            }
            if ($request->filled('state_code')) {
                $query->where('state_code', (string) $request->input('state_code'));
            }
            if ($request->filled('type')) {
                $query->where('type', (string) $request->input('type'));
            }

            $rates = $query->orderByDesc('priority')->orderByDesc('id')->paginate($limit);
            $rates->appends($request->except('page'));

            return TaxRateResource::collection($rates);
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
     *     path="/api/module/tax",
     *     operationId="api.module.tax.store",
     *     tags={"Tax"},
     *     summary="Create a new tax rate",
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
            'description' => 'nullable|string',
            'country_code' => 'nullable|string|max:2',
            'state_code' => 'nullable|string|max:10',
            'zip_code_pattern' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:255',
            'type' => 'required|string|in:percentage,fixed',
            'rate' => 'required|numeric|min:0',
            'compound_tax' => 'nullable|boolean',
            'priority' => 'nullable|integer',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $rate = TaxRate::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Tax rate created successfully',
                'data' => new TaxRateResource($rate->fresh()),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create tax rate',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/module/tax/{id}",
     *     operationId="api.module.tax.id.show",
     *     tags={"Tax"},
     *     summary="Show a single tax rate",
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
        $rate = TaxRate::find($id);

        if (!$rate) {
            return response()->json([
                'success' => false,
                'message' => 'Tax rate not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $isAdmin = $request->user() !== null
            && (int) $request->user()->is_admin === 1;

        if (!$isAdmin && !$rate->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Tax rate not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => new TaxRateResource($rate),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/module/tax/{id}",
     *     operationId="api.module.tax.id.update",
     *     tags={"Tax"},
     *     summary="Update a tax rate",
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

        $rate = TaxRate::find($id);

        if (!$rate) {
            return response()->json([
                'success' => false,
                'message' => 'Tax rate not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'country_code' => 'nullable|string|max:2',
            'state_code' => 'nullable|string|max:10',
            'zip_code_pattern' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:255',
            'type' => 'nullable|string|in:percentage,fixed',
            'rate' => 'nullable|numeric|min:0',
            'compound_tax' => 'nullable|boolean',
            'priority' => 'nullable|integer',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $rate->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Tax rate updated successfully',
                'data' => new TaxRateResource($rate->fresh()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update tax rate',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/module/tax/{id}",
     *     operationId="api.module.tax.id.destroy",
     *     tags={"Tax"},
     *     summary="Delete a tax rate",
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

        $rate = TaxRate::find($id);

        if (!$rate) {
            return response()->json([
                'success' => false,
                'message' => 'Tax rate not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $rate->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tax rate deleted successfully',
                'data' => ['id' => $id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete tax rate',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
