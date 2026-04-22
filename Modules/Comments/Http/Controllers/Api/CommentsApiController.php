<?php

declare(strict_types=1);

namespace Modules\Comments\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Comments\Http\Resources\CommentResource;
use Modules\Comments\Models\Comment;
use Symfony\Component\HttpFoundation\Response;

class CommentsApiController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $limit = (int) $request->get('limit', 30);
            $isAdmin = $request->user() !== null
                && (int) $request->user()->is_admin === 1;

            $query = Comment::query();

            // Non-admin callers only see moderated, non-spam comments.
            if (!$isAdmin) {
                $query->published();
            }

            if ($request->filled('rel_type')) {
                $query->where('rel_type', (string) $request->input('rel_type'));
            }
            if ($request->filled('rel_id')) {
                $query->where('rel_id', (string) $request->input('rel_id'));
            }
            if ($request->filled('reply_to_comment_id')) {
                $query->where('reply_to_comment_id', (int) $request->input('reply_to_comment_id'));
            }

            $comments = $query->orderByDesc('created_at')->paginate($limit);
            $comments->appends($request->except('page'));

            return CommentResource::collection($comments);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid query parameters',
                'data' => [],
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function store(Request $request): JsonResponse
    {
        if (!$request->user() || (int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $validator = Validator::make($request->all(), [
            'rel_type' => 'required|string|max:255',
            'rel_id' => 'required|string|max:255',
            'comment_body' => 'required|string',
            'comment_subject' => 'nullable|string|max:255',
            'comment_name' => 'nullable|string|max:255',
            'comment_email' => 'nullable|email|max:255',
            'comment_website' => 'nullable|string|max:255',
            'reply_to_comment_id' => 'nullable|integer|exists:comments,id',
            'is_moderated' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'is_spam' => 'nullable|boolean',
            'created_by' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $comment = Comment::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Comment created successfully',
                'data' => new CommentResource($comment->fresh()),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create comment',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $isAdmin = $request->user() !== null
            && (int) $request->user()->is_admin === 1;

        // Non-admin callers cannot see unmoderated or spam comments.
        if (!$isAdmin && ((int) $comment->is_moderated !== 1 || (int) $comment->is_spam === 1)) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => new CommentResource($comment),
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        if (!$request->user() || (int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'comment_body' => 'nullable|string',
            'comment_subject' => 'nullable|string|max:255',
            'comment_name' => 'nullable|string|max:255',
            'comment_email' => 'nullable|email|max:255',
            'comment_website' => 'nullable|string|max:255',
            'is_moderated' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'is_spam' => 'nullable|boolean',
            'is_reported' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $comment->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Comment updated successfully',
                'data' => new CommentResource($comment->fresh()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update comment',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        if (!$request->user() || (int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $comment->deleteWithReplies();

            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully',
                'data' => ['id' => $id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete comment',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
