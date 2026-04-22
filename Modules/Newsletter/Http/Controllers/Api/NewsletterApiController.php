<?php

declare(strict_types=1);

namespace Modules\Newsletter\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Newsletter\Http\Resources\NewsletterSubscriberResource;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Symfony\Component\HttpFoundation\Response;

/**
 * Subscribing to a newsletter list is a public action — anyone should be
 * able to self-subscribe by email. Listing and destructive ops are gated
 * to admin because the subscriber list is personal data.
 */
class NewsletterApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/module/newsletter",
     *     operationId="api.module.newsletter.index",
     *     tags={"Newsletter"},
     *     summary="List subscribers",
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        if ($deny = $this->requireAdmin($request)) {
            return $deny;
        }

        try {
            $limit = (int) $request->get('limit', 30);
            $query = NewsletterSubscriber::query();

            if ($request->filled('list_id')) {
                $query->where('list_id', (int) $request->input('list_id'));
            }
            if ($request->filled('status')) {
                $query->where('status', (string) $request->input('status'));
            }
            if ($request->filled('is_subscribed')) {
                $query->where('is_subscribed', (int) $request->boolean('is_subscribed'));
            }
            if ($request->filled('search')) {
                $term = (string) $request->input('search');
                $query->where(function ($q) use ($term) {
                    $q->where('email', 'like', "%{$term}%")
                        ->orWhere('name', 'like', "%{$term}%");
                });
            }

            $subscribers = $query->orderByDesc('id')->paginate($limit);
            $subscribers->appends($request->except('page'));

            return NewsletterSubscriberResource::collection($subscribers);
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
     *     path="/api/module/newsletter",
     *     operationId="api.module.newsletter.store",
     *     tags={"Newsletter"},
     *     summary="Create a new subscriber",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — admin required")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
            'list_id' => 'nullable|integer',
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
            $email = strtolower(trim($data['email']));

            $existing = NewsletterSubscriber::where('email', $email)
                ->when(!empty($data['list_id']), fn ($q) => $q->where('list_id', (int) $data['list_id']))
                ->first();

            if ($existing) {
                $existing->update([
                    'status' => 'active',
                    'is_subscribed' => true,
                    'subscribed_at' => now(),
                    'unsubscribed_at' => null,
                    'name' => $data['name'] ?? $existing->name,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Already subscribed',
                    'data' => new NewsletterSubscriberResource($existing->fresh()),
                ], Response::HTTP_OK);
            }

            $subscriber = NewsletterSubscriber::create([
                'email' => $email,
                'name' => $data['name'] ?? null,
                'list_id' => $data['list_id'] ?? null,
                'status' => 'active',
                'is_subscribed' => true,
                'subscribed_at' => now(),
                'confirmation_code' => (string) Str::uuid(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subscribed successfully',
                'data' => new NewsletterSubscriberResource($subscriber->fresh()),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to subscribe',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/module/newsletter/{id}",
     *     operationId="api.module.newsletter.id.show",
     *     tags={"Newsletter"},
     *     summary="Show a single subscriber",
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
        if ($deny = $this->requireAdmin($request)) {
            return $deny;
        }

        $subscriber = NewsletterSubscriber::find($id);

        if (!$subscriber) {
            return response()->json([
                'success' => false,
                'message' => 'Subscriber not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => new NewsletterSubscriberResource($subscriber),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/module/newsletter/{id}",
     *     operationId="api.module.newsletter.id.update",
     *     tags={"Newsletter"},
     *     summary="Update a subscriber",
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
        if ($deny = $this->requireAdmin($request)) {
            return $deny;
        }

        $subscriber = NewsletterSubscriber::find($id);

        if (!$subscriber) {
            return response()->json([
                'success' => false,
                'message' => 'Subscriber not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|max:255',
            'name' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
            'is_subscribed' => 'nullable|boolean',
            'list_id' => 'nullable|integer',
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
            if (array_key_exists('is_subscribed', $data)) {
                $data['is_subscribed'] = (bool) $data['is_subscribed'];
            }
            $subscriber->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Subscriber updated successfully',
                'data' => new NewsletterSubscriberResource($subscriber->fresh()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update subscriber',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/module/newsletter/{id}",
     *     operationId="api.module.newsletter.id.destroy",
     *     tags={"Newsletter"},
     *     summary="Delete a subscriber",
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
        if ($deny = $this->requireAdmin($request)) {
            return $deny;
        }

        $subscriber = NewsletterSubscriber::find($id);

        if (!$subscriber) {
            return response()->json([
                'success' => false,
                'message' => 'Subscriber not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $subscriber->delete();

            return response()->json([
                'success' => true,
                'message' => 'Subscriber deleted successfully',
                'data' => ['id' => $id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete subscriber',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/module/newsletter/unsubscribe",
     *     operationId="api.module.newsletter.unsubscribe.unsubscribe",
     *     tags={"Newsletter"},
     *     summary="Self-unsubscribe by email",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"email"},
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="list_id", type="integer"),
     *                 @OA\Property(property="confirmation_code", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'list_id' => 'nullable|integer',
            'confirmation_code' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $validator->validated();
        $query = NewsletterSubscriber::where('email', strtolower(trim($data['email'])));

        if (!empty($data['list_id'])) {
            $query->where('list_id', (int) $data['list_id']);
        }
        if (!empty($data['confirmation_code'])) {
            $query->where('confirmation_code', $data['confirmation_code']);
        }

        $count = $query->update([
            'status' => 'unsubscribed',
            'is_subscribed' => false,
            'unsubscribed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Unsubscribed',
            'data' => ['updated' => $count],
        ]);
    }

    private function requireAdmin(Request $request): ?JsonResponse
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.',
            ], Response::HTTP_UNAUTHORIZED);
        }
        if ((int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        return null;
    }
}
