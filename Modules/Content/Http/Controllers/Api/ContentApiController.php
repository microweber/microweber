<?php

declare(strict_types=1);

namespace Modules\Content\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Modules\Content\Http\Resources\ContentResource;
use Modules\Content\Repositories\ContentRepositoryApi;
use Symfony\Component\HttpFoundation\Response;

class ContentApiController extends Controller
{
    public function __construct(
        private readonly ContentRepositoryApi $content
    ) {
    }

    /**
     * Display a listing of content.
     *
     * @param Request $request
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $limit = $request->get('limit', 30);
            $query = $this->content->filter($request->all());

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
     * Store content in database.
     *
     * @param Request $request
     * @return JsonResponse
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
            $content = $this->content->create($validator->validated());

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
     * Display the specified content.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $content = $this->content->show($id);

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
     * Update the specified content.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        if (!$request->user() || !$request->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $content = $this->content->show($id);

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
            $updated = $this->content->update($validator->validated(), $id);

            return response()->json([
                'success' => true,
                'message' => 'Content updated successfully',
                'data' => new ContentResource($updated),
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
     * Remove the specified content.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        if (!$request->user() || !$request->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $content = $this->content->show($id);

        if (!$content) {
            return response()->json([
                'success' => false,
                'message' => 'Content not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $this->content->delete($id);

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
