<?php

declare(strict_types=1);

namespace Modules\Post\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use Modules\Post\Http\Resources\PostResource;
use Modules\Post\Repositories\PostApiRepository;
use Symfony\Component\HttpFoundation\Response;

class PostApiController extends AdminDefaultController
{
    public function __construct(
        private readonly PostApiRepository $post
    ) {
    }

    /**
     * Display a listing of posts.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $limit = $request->get('limit', 30);
        $query = $this->post->filter($request->all());

        $posts = $query->paginate($limit);
        $posts->appends($request->except('page'));

        return PostResource::collection($posts);
    }

    /**
     * Store a new post.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:500',
            'url' => 'nullable|string|max:500|unique:content,url',
            'content' => 'nullable|string',
            'description' => 'nullable|string',
            'content_body' => 'nullable|string',
            'content_meta_title' => 'nullable|string|max:500',
            'content_meta_keywords' => 'nullable|string|max:500',
            'content_meta_description' => 'nullable|string|max:1000',
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
            $post = $this->post->create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Post created successfully',
                'data' => new PostResource($post),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create post',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified post.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $post = $this->post->show($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => new PostResource($post),
        ]);
    }

    /**
     * Update the specified post.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $post = $this->post->show($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found',
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
            $updated = $this->post->update($validator->validated(), $id);

            return response()->json([
                'success' => true,
                'message' => 'Post updated successfully',
                'data' => new PostResource($updated),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update post',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified post.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $post = $this->post->show($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $this->post->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Post deleted successfully',
                'data' => ['id' => $id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete post',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
