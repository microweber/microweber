<?php

declare(strict_types=1);

namespace Modules\Media\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Media\Http\Resources\MediaResource;
use Modules\Media\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class MediaApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/module/media",
     *     operationId="api.module.media.index",
     *     tags={"Media"},
     *     summary="List media items",
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $limit = (int) $request->get('limit', 30);
            $query = Media::query();

            if ($request->filled('rel_type')) {
                $query->where('rel_type', (string) $request->input('rel_type'));
            }
            if ($request->filled('rel_id')) {
                $query->where('rel_id', (string) $request->input('rel_id'));
            }
            if ($request->filled('media_type')) {
                $query->where('media_type', (string) $request->input('media_type'));
            }
            if ($request->has('folder_id')) {
                $folderId = $request->input('folder_id');
                if ($folderId === null || $folderId === '' || $folderId === 'null') {
                    $query->whereNull('folder_id');
                } else {
                    $query->where('folder_id', (int) $folderId);
                }
            }
            if ($request->filled('search')) {
                $term = (string) $request->input('search');
                $query->where(function ($q) use ($term) {
                    $q->where('title', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%");
                });
            }

            $media = $query
                ->orderBy('position')
                ->orderByDesc('id')
                ->paginate($limit);
            $media->appends($request->except('page'));

            return MediaResource::collection($media);
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
     *     path="/api/module/media",
     *     operationId="api.module.media.store",
     *     tags={"Media"},
     *     summary="Create a new media item",
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
            'filename' => 'required|string',
            'title' => 'nullable|string|max:1000',
            'description' => 'nullable|string',
            'media_type' => 'nullable|string|max:255',
            'rel_type' => 'nullable|string|max:255',
            'rel_id' => 'nullable|string|max:255',
            'folder_id' => 'nullable|integer|exists:media_folders,id',
            'position' => 'nullable|integer',
            'image_options' => 'nullable|array',
            'metadata' => 'nullable|array',
            'cdn_url' => 'nullable|string',
            'cdn_provider' => 'nullable|string|max:255',
            'cdn_metadata' => 'nullable|array',
            'is_synced_to_cdn' => 'nullable|boolean',
            'file_size' => 'nullable|integer',
            'file_hash' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $media = Media::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Media created successfully',
                'data' => new MediaResource($media->fresh()),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create media',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/module/media/{id}",
     *     operationId="api.module.media.id.show",
     *     tags={"Media"},
     *     summary="Show a single media item",
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
        $media = Media::find($id);

        if (!$media) {
            return response()->json([
                'success' => false,
                'message' => 'Media not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => new MediaResource($media),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/module/media/{id}",
     *     operationId="api.module.media.id.update",
     *     tags={"Media"},
     *     summary="Update a media item",
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

        $media = Media::find($id);

        if (!$media) {
            return response()->json([
                'success' => false,
                'message' => 'Media not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'filename' => 'nullable|string',
            'title' => 'nullable|string|max:1000',
            'description' => 'nullable|string',
            'media_type' => 'nullable|string|max:255',
            'rel_type' => 'nullable|string|max:255',
            'rel_id' => 'nullable|string|max:255',
            'folder_id' => 'nullable|integer|exists:media_folders,id',
            'position' => 'nullable|integer',
            'image_options' => 'nullable|array',
            'metadata' => 'nullable|array',
            'cdn_url' => 'nullable|string',
            'cdn_provider' => 'nullable|string|max:255',
            'cdn_metadata' => 'nullable|array',
            'is_synced_to_cdn' => 'nullable|boolean',
            'file_size' => 'nullable|integer',
            'file_hash' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $media->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Media updated successfully',
                'data' => new MediaResource($media->fresh()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update media',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/module/media/{id}",
     *     operationId="api.module.media.id.destroy",
     *     tags={"Media"},
     *     summary="Delete a media item",
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

        $media = Media::find($id);

        if (!$media) {
            return response()->json([
                'success' => false,
                'message' => 'Media not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $media->delete();

            return response()->json([
                'success' => true,
                'message' => 'Media deleted successfully',
                'data' => ['id' => $id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete media',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
