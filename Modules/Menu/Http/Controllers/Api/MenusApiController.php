<?php

declare(strict_types=1);

namespace Modules\Menu\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Menu\Http\Resources\MenuResource;
use Modules\Menu\Models\Menu;
use Symfony\Component\HttpFoundation\Response;

class MenusApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/module/menus",
     *     operationId="api.module.menus.index",
     *     tags={"Menus"},
     *     summary="List menus",
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

            $query = Menu::query();

            // Non-admin callers only see active rows; admin sees everything.
            if (!$isAdmin) {
                $query->where(function ($q) {
                    $q->where('is_active', 1)->orWhereNull('is_active');
                });
            }

            if ($request->filled('item_type')) {
                $query->where('item_type', (string) $request->input('item_type'));
            }
            if ($request->filled('parent_id')) {
                $query->where('parent_id', (int) $request->input('parent_id'));
            }
            if ($request->filled('search')) {
                $term = (string) $request->input('search');
                $query->where('title', 'like', "%{$term}%");
            }

            $menus = $query
                ->orderBy('position')
                ->orderBy('id')
                ->paginate($limit);
            $menus->appends($request->except('page'));

            return MenuResource::collection($menus);
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
     *     path="/api/module/menus",
     *     operationId="api.module.menus.store",
     *     tags={"Menus"},
     *     summary="Create a new menu",
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
            'title' => 'required|string|max:1000',
            'item_type' => 'nullable|string|max:255',
            'parent_id' => 'nullable|integer',
            'content_id' => 'nullable|integer',
            'categories_id' => 'nullable|integer',
            'position' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'description' => 'nullable|string',
            'url' => 'nullable|string',
            'url_target' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'default_image' => 'nullable|string',
            'rollover_image' => 'nullable|string',
            'enable_mega_menu' => 'nullable|boolean',
            'menu_item_template' => 'nullable|string|max:255',
            'mega_menu_settings' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $validator->validated();
        $data['item_type'] = $data['item_type'] ?? 'menu';
        $data['parent_id'] = $data['parent_id'] ?? 0;
        $data['is_active'] = array_key_exists('is_active', $data) ? (int) $data['is_active'] : 1;

        try {
            $menu = Menu::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Menu created successfully',
                'data' => new MenuResource($menu->fresh()),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create menu',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/module/menus/{id}",
     *     operationId="api.module.menus.id.show",
     *     tags={"Menus"},
     *     summary="Show a single menu",
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
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json([
                'success' => false,
                'message' => 'Menu not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $isAdmin = $request->user() !== null
            && (int) $request->user()->is_admin === 1;

        if (!$isAdmin && $menu->is_active !== null && (int) $menu->is_active === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Menu not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => new MenuResource($menu),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/module/menus/{id}",
     *     operationId="api.module.menus.id.update",
     *     tags={"Menus"},
     *     summary="Update a menu",
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

        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json([
                'success' => false,
                'message' => 'Menu not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:1000',
            'item_type' => 'nullable|string|max:255',
            'parent_id' => 'nullable|integer',
            'content_id' => 'nullable|integer',
            'categories_id' => 'nullable|integer',
            'position' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'description' => 'nullable|string',
            'url' => 'nullable|string',
            'url_target' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'default_image' => 'nullable|string',
            'rollover_image' => 'nullable|string',
            'enable_mega_menu' => 'nullable|boolean',
            'menu_item_template' => 'nullable|string|max:255',
            'mega_menu_settings' => 'nullable|array',
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
            $menu->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Menu updated successfully',
                'data' => new MenuResource($menu->fresh()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update menu',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/module/menus/{id}",
     *     operationId="api.module.menus.id.destroy",
     *     tags={"Menus"},
     *     summary="Delete a menu",
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

        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json([
                'success' => false,
                'message' => 'Menu not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            DB::transaction(function () use ($menu) {
                // Deleting a menu container cascades to its items (mirrors
                // the admin MenusList delete action).
                if ($menu->item_type === 'menu') {
                    Menu::where('parent_id', $menu->id)->delete();
                }
                $menu->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Menu deleted successfully',
                'data' => ['id' => $id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete menu',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
