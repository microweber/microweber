<?php

declare(strict_types=1);

namespace Modules\Content\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Modules\Content\Http\Resources\ContentResource;
use Modules\Content\Models\Content;
use Symfony\Component\HttpFoundation\Response;

class ContentApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/module/content",
     *     operationId="api.module.content.index",
     *     tags={"Content"},
     *     summary="List content items",
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $limit = $request->get('limit', 30);
            $query = Content::filter($request->all());

            // Handle pagination
            $content = $query->paginate($limit);
            $content->appends($request->except('page'));

            return ContentResource::collection($content);
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
     *     path="/api/module/content",
     *     operationId="api.module.content.store",
     *     tags={"Content"},
     *     summary="Create a new content item",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — admin required")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        if (!$request->user() || !$request->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:500',
            'url' => ['nullable', 'string', 'max:500', \Illuminate\Validation\Rule::unique('content', 'url')],
            'content_type' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'multilanguage' => 'nullable',
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
            if (isset($data['content'])) {
                $data['content'] = xss_clean($data['content']);
            }
            $content = Content::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Content created successfully',
                'data' => new ContentResource($content),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create content',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/module/content/{id}",
     *     operationId="api.module.content.id.show",
     *     tags={"Content"},
     *     summary="Show a single content item",
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
    public function show(int $id): JsonResponse
    {
        $content = Content::find($id);

        if (!$content) {
            return response()->json([
                'success' => false,
                'message' => 'Content not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => new ContentResource($content),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/module/content/{id}",
     *     operationId="api.module.content.id.update",
     *     tags={"Content"},
     *     summary="Update a content item",
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
        if (!$request->user() || !$request->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $content = Content::find($id);

        if (!$content) {
            return response()->json([
                'success' => false,
                'message' => 'Content not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:500',
            'url' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $content->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Content updated successfully',
                'data' => new ContentResource($content),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update content',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/module/content/{id}",
     *     operationId="api.module.content.id.destroy",
     *     tags={"Content"},
     *     summary="Delete a content item",
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
        if (!$request->user() || !$request->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $content = Content::find($id);

        if (!$content) {
            return response()->json([
                'success' => false,
                'message' => 'Content not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $content->update(['is_deleted' => 1]);

            return response()->json([
                'success' => true,
                'message' => 'Content deleted successfully',
                'data' => ['id' => $id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete content',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get admin JS tree JSON.
     *
     * @param array $params
     * @return mixed
     */
    public function get_admin_js_tree_json(array $params = []): mixed
    {
        return app()->category_manager->get_admin_js_tree_json($params);
    }
}
