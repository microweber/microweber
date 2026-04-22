<?php

declare(strict_types=1);

namespace Modules\Category\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Category\Http\Resources\CategoryResource;
use Modules\Category\Models\Category;
use Symfony\Component\HttpFoundation\Response;

class CategoriesApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/module/categories",
     *     operationId="api.module.categories.index",
     *     tags={"Categories"},
     *     summary="List categorys",
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

            $query = Category::query();

            if (!$isAdmin) {
                $query->where('is_active', 1)
                    ->where('is_hidden', 0)
                    ->where('is_deleted', 0);
            }

            if ($request->filled('parent_id')) {
                $query->where('parent_id', (int) $request->input('parent_id'));
            }
            if ($request->filled('rel_type')) {
                $query->where('rel_type', (string) $request->input('rel_type'));
            }
            if ($request->filled('data_type')) {
                $query->where('data_type', (string) $request->input('data_type'));
            }
            if ($request->filled('search')) {
                $term = (string) $request->input('search');
                $query->where(function ($q) use ($term) {
                    $q->where('title', 'like', "%{$term}%")
                        ->orWhere('url', 'like', "%{$term}%");
                });
            }

            $categories = $query->orderBy('position')->orderByDesc('id')->paginate($limit);
            $categories->appends($request->except('page'));

            return CategoryResource::collection($categories);
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
     *     path="/api/module/categories",
     *     operationId="api.module.categories.store",
     *     tags={"Categories"},
     *     summary="Create a new category",
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
            'content' => 'nullable|string',
            'url' => 'nullable|string|max:500',
            'parent_id' => 'nullable|integer',
            'rel_type' => 'nullable|string|max:255',
            'rel_id' => 'nullable|integer',
            'data_type' => 'nullable|string|max:255',
            'position' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'is_hidden' => 'nullable|boolean',
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
            foreach (['is_active', 'is_hidden'] as $flag) {
                if (array_key_exists($flag, $data)) {
                    $data[$flag] = (int) $data[$flag];
                }
            }

            $category = Category::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'data' => new CategoryResource($category->fresh()),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create category',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/module/categories/{id}",
     *     operationId="api.module.categories.id.show",
     *     tags={"Categories"},
     *     summary="Show a single category",
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
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $isAdmin = $request->user() !== null
            && (int) $request->user()->is_admin === 1;

        if (!$isAdmin && ((int) $category->is_active !== 1
            || (int) $category->is_hidden === 1
            || (int) $category->is_deleted === 1)) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/module/categories/{id}",
     *     operationId="api.module.categories.id.update",
     *     tags={"Categories"},
     *     summary="Update a category",
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

        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'url' => 'nullable|string|max:500',
            'parent_id' => 'nullable|integer',
            'rel_type' => 'nullable|string|max:255',
            'rel_id' => 'nullable|integer',
            'data_type' => 'nullable|string|max:255',
            'position' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'is_hidden' => 'nullable|boolean',
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
            foreach (['is_active', 'is_hidden'] as $flag) {
                if (array_key_exists($flag, $data)) {
                    $data[$flag] = (int) $data[$flag];
                }
            }
            $category->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => new CategoryResource($category->fresh()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/module/categories/{id}",
     *     operationId="api.module.categories.id.destroy",
     *     tags={"Categories"},
     *     summary="Delete a category",
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

        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully',
                'data' => ['id' => $id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
