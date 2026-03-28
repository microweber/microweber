<?php

declare(strict_types=1);

namespace Modules\Product\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Modules\Product\Http\Resources\ProductResource;
use Modules\Product\Models\Product;
use Symfony\Component\HttpFoundation\Response;

class ProductPublicApiController extends Controller
{
    /**
     * Display a listing of products.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Product::query()
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->where('content_type', 'product');

            // Apply filters
            if ($request->has('category')) {
                $query->whereHas('categories', function ($q) use ($request) {
                    $q->where('title', $request->get('category'));
                });
            }

            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            if ($request->has('min_price')) {
                $query->whereHas('customField', function ($q) use ($request) {
                    $q->where('type', 'price')
                        ->where('value', '>=', $request->get('min_price'));
                });
            }

            if ($request->has('max_price')) {
                $query->whereHas('customField', function ($q) use ($request) {
                    $q->where('type', 'price')
                        ->where('value', '<=', $request->get('max_price'));
                });
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $allowedSortFields = ['title', 'created_at', 'updated_at', 'position'];

            if (in_array($sortBy, $allowedSortFields)) {
                $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
            }

            // Pagination
            $limit = $request->get('limit', 30);
            $limit = min($limit, 100); // Max 100 items per page

            $products = $query->paginate($limit);
            $products->appends($request->except('page'));

            // Wrap in success response
            return response()->json([
                'success' => true,
                'data' => ProductResource::collection($products)->response()->getData(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve products',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified product.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $product = Product::where('id', $id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->where('content_type', 'product')
                ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], Response::HTTP_NOT_FOUND);
            }

            // Load relationships
            $product->load(['customField', 'media']);

            return response()->json([
                'success' => true,
                'data' => new ProductResource($product),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve product',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get product by slug.
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function bySlug(string $slug): JsonResponse
    {
        try {
            $product = Product::where('url', $slug)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->where('content_type', 'product')
                ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], Response::HTTP_NOT_FOUND);
            }

            // Load relationships
            $product->load(['customField', 'media']);

            return response()->json([
                'success' => true,
                'data' => new ProductResource($product),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve product',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get featured products.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function featured(Request $request): JsonResponse
    {
        try {
            $limit = min($request->get('limit', 10), 50);

            $products = Product::query()
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->where('content_type', 'product')
                ->where('is_featured', 1)
                ->orderBy('position', 'asc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => ProductResource::collection($products),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve featured products',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get products by category.
     *
     * @param Request $request
     * @param string $categorySlug
     * @return JsonResponse
     */
    public function byCategory(Request $request, string $categorySlug): JsonResponse
    {
        try {
            $limit = min($request->get('limit', 30), 100);

            $query = Product::query()
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->where('content_type', 'product')
                ->whereHas('categoryItems', function ($q) use ($categorySlug) {
                    $q->whereHas('category', function ($cq) use ($categorySlug) {
                        $cq->where('url', $categorySlug)
                            ->orWhere('title', $categorySlug);
                    });
                });

            $products = $query->paginate($limit);
            $products->appends($request->except('page'));

            return response()->json([
                'success' => true,
                'data' => ProductResource::collection($products),
                'meta' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve products',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
