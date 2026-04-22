<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Modules\Page\Http\Resources\PageResource;
use Modules\Page\Models\Page;
use Symfony\Component\HttpFoundation\Response;

class PageApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/module/pages",
     *     operationId="api.module.pages.index",
     *     tags={"Content"},
     *     summary="List pages",
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $limit = $request->get('limit', 30);
            $query = Page::filter($request->all());

            $pages = $query->paginate($limit);
            $pages->appends($request->except('page'));

            return PageResource::collection($pages);
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
     *     path="/api/module/pages",
     *     operationId="api.module.pages.store",
     *     tags={"Content"},
     *     summary="Create a new page",
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
            'url' => 'nullable|string|max:500|unique:content,url',
            'content' => 'nullable|string',
            'description' => 'nullable|string',
            'content_body' => 'nullable|string',
            'content_meta_title' => 'nullable|string|max:500',
            'content_meta_keywords' => 'nullable|string|max:500',
            'content_meta_description' => 'nullable|string|max:1000',
            'is_home' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'require_login' => 'nullable|boolean',
            'parent' => 'nullable|integer',
            'layout_file' => 'nullable|string|max:500',
            'active_site_template' => 'nullable|string|max:500',
            'position' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $page = Page::create($validator->validated());

            $addToMenus = $request->input('add_content_to_menu');
            if (!empty($addToMenus) && is_array($addToMenus)) {
                foreach ($addToMenus as $menuId) {
                    if (!app()->menu_manager->is_in_menu($menuId, $page->id)) {
                        app()->content_manager->helpers->add_content_to_menu($page->id, $menuId);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Page created successfully',
                'data' => new PageResource($page),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create page',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/module/pages/{id}",
     *     operationId="api.module.pages.id.show",
     *     tags={"Content"},
     *     summary="Show a single page",
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
        $page = Page::find($id);

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => new PageResource($page),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/module/pages/{id}",
     *     operationId="api.module.pages.id.update",
     *     tags={"Content"},
     *     summary="Update a page",
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

        $page = Page::find($id);

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:500',
            'url' => 'nullable|string|max:500|unique:content,url,' . $id,
            'content' => 'nullable|string',
            'description' => 'nullable|string',
            'content_body' => 'nullable|string',
            'content_meta_title' => 'nullable|string|max:500',
            'content_meta_keywords' => 'nullable|string|max:500',
            'content_meta_description' => 'nullable|string|max:1000',
            'is_home' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'require_login' => 'nullable|boolean',
            'parent' => 'nullable|integer',
            'layout_file' => 'nullable|string|max:500',
            'active_site_template' => 'nullable|string|max:500',
            'position' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $page->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Page updated successfully',
                'data' => new PageResource($page),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update page',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/module/pages/{id}",
     *     operationId="api.module.pages.id.destroy",
     *     tags={"Content"},
     *     summary="Delete a page",
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

        $page = Page::find($id);

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $page->update(['is_deleted' => 1]);

            return response()->json([
                'success' => true,
                'message' => 'Page deleted successfully',
                'data' => ['id' => $id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete page',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
