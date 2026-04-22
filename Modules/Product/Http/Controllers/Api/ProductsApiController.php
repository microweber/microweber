<?php

declare(strict_types=1);

namespace Modules\Product\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Product\Http\Resources\ProductResource;
use Modules\Product\Models\Product;
use Symfony\Component\HttpFoundation\Response;

class ProductsApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/module/products",
     *     operationId="api.module.products.index",
     *     tags={"Products"},
     *     summary="List products",
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

            $query = Product::query();

            if (!$isAdmin) {
                $query->where('is_active', 1)->where('is_deleted', 0);
            }

            if ($request->filled('search')) {
                $term = (string) $request->input('search');
                $query->where(function ($q) use ($term) {
                    $q->where('title', 'like', "%{$term}%")
                        ->orWhere('url', 'like', "%{$term}%");
                });
            }

            if ($request->filled('category_id')) {
                $categoryId = (int) $request->input('category_id');
                $query->whereHas('categories', function ($q) use ($categoryId) {
                    $q->where('categories.id', $categoryId);
                });
            }

            $products = $query->orderByDesc('id')->paginate($limit);
            $products->appends($request->except('page'));

            return ProductResource::collection($products);
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
     *     path="/api/module/products",
     *     operationId="api.module.products.store",
     *     tags={"Products"},
     *     summary="Create a new product",
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
            'title' => 'required|string|max:500',
            'description' => 'nullable|string',
            'content_body' => 'nullable|string',
            'url' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
            'is_shop' => 'nullable|boolean',
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
            $data['content_type'] = 'product';
            $data['subtype'] = 'product';
            $data['is_active'] = array_key_exists('is_active', $data) ? (int) $data['is_active'] : 1;

            $product = Product::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => new ProductResource($product->fresh()),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/module/products/{id}",
     *     operationId="api.module.products.id.show",
     *     tags={"Products"},
     *     summary="Show a single product",
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
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $isAdmin = $request->user() !== null
            && (int) $request->user()->is_admin === 1;

        if (!$isAdmin && ((int) $product->is_active !== 1 || (int) $product->is_deleted === 1)) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/module/products/{id}",
     *     operationId="api.module.products.id.update",
     *     tags={"Products"},
     *     summary="Update a product",
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

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'content_body' => 'nullable|string',
            'url' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
            'is_shop' => 'nullable|boolean',
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
            if (array_key_exists('is_active', $data)) {
                $data['is_active'] = (int) $data['is_active'];
            }
            $product->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => new ProductResource($product->fresh()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/module/products/{id}",
     *     operationId="api.module.products.id.destroy",
     *     tags={"Products"},
     *     summary="Delete a product",
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

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully',
                'data' => ['id' => $id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
