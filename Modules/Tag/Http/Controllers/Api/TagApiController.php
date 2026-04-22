<?php

declare(strict_types=1);

namespace Modules\Tag\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Tag\Http\Resources\TagResource;
use Modules\Tag\Models\Tag;
use Symfony\Component\HttpFoundation\Response;

class TagApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/module/tags",
     *     operationId="api.module.tags.index",
     *     tags={"Content"},
     *     summary="List tags",
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $limit = (int) $request->get('limit', 30);
            $query = Tag::query();

            if ($request->filled('suggest')) {
                $query->where('suggest', $request->boolean('suggest'));
            }

            if ($request->filled('tag_group_id')) {
                $query->where('tag_group_id', (int) $request->input('tag_group_id'));
            }

            if ($request->filled('search')) {
                $term = (string) $request->input('search');
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                        ->orWhere('slug', 'like', "%{$term}%");
                });
            }

            $tags = $query->orderBy('name')->paginate($limit);
            $tags->appends($request->except('page'));

            return TagResource::collection($tags);
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
     *     path="/api/module/tags",
     *     operationId="api.module.tags.store",
     *     tags={"Content"},
     *     summary="Create a new tag",
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
            'name' => 'required|string|max:255|unique:tagging_tags,name',
            'description' => 'nullable|string',
            'suggest' => 'nullable|boolean',
            'tag_group_id' => 'nullable|integer|exists:tagging_tag_groups,id',
            'locale' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $tag = new Tag();
            $tag->name = $validator->validated()['name'];
            $tag->description = $validator->validated()['description'] ?? null;
            $tag->suggest = $validator->validated()['suggest'] ?? false;
            $tag->tag_group_id = $validator->validated()['tag_group_id'] ?? null;
            $tag->locale = $validator->validated()['locale'] ?? null;
            $tag->save();

            return response()->json([
                'success' => true,
                'message' => 'Tag created successfully',
                'data' => new TagResource($tag),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create tag',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/module/tags/{id}",
     *     operationId="api.module.tags.id.show",
     *     tags={"Content"},
     *     summary="Show a single tag",
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
        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Tag not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => new TagResource($tag),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/module/tags/{id}",
     *     operationId="api.module.tags.id.update",
     *     tags={"Content"},
     *     summary="Update a tag",
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

        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Tag not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255|unique:tagging_tags,name,' . $id,
            'description' => 'nullable|string',
            'suggest' => 'nullable|boolean',
            'tag_group_id' => 'nullable|integer|exists:tagging_tag_groups,id',
            'locale' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            foreach ($validator->validated() as $key => $value) {
                $tag->{$key} = $value;
            }
            $tag->save();

            return response()->json([
                'success' => true,
                'message' => 'Tag updated successfully',
                'data' => new TagResource($tag),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update tag',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/module/tags/{id}",
     *     operationId="api.module.tags.id.destroy",
     *     tags={"Content"},
     *     summary="Delete a tag",
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

        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Tag not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $tag->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tag deleted successfully',
                'data' => ['id' => $id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete tag',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
