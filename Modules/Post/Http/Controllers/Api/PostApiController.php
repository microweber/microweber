<?php

declare(strict_types=1);

namespace Modules\Post\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Modules\Post\Http\Resources\PostResource;
use Modules\Post\Models\Post;
use Symfony\Component\HttpFoundation\Response;

class PostApiController extends Controller
{
    /**
     * Display a listing of posts.
     *
     * @param Request $request
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $limit = $request->get('limit', 30);
            $query = Post::filter($request->all());

            $posts = $query->paginate($limit);
            $posts->appends($request->except('page'));

            return PostResource::collection($posts);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid query parameters',
                'data' => [],
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a new post.
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
            $post = Post::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Post created successfully',
                'data' => new PostResource($post),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create post',
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
        $post = Post::find($id);

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
        if (!$request->user() || !$request->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $post = Post::find($id);

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
            $post->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Post updated successfully',
                'data' => new PostResource($post),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update post',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified post.
     *
     * @param Request $request
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

        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $post->update(['is_deleted' => 1]);

            return response()->json([
                'success' => true,
                'message' => 'Post deleted successfully',
                'data' => ['id' => $id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete post',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
