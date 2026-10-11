<?php

declare(strict_types=1);

namespace MicroweberPackages\User\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use MicroweberPackages\User\Http\Resources\UserApiResource;
use MicroweberPackages\User\Models\User;
use Symfony\Component\HttpFoundation\Response;

class UsersApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/module/users",
     *     operationId="api.module.users.index",
     *     tags={"Users"},
     *     summary="List users",
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
            $query = User::query();

            if ($request->filled('is_admin')) {
                $query->where('is_admin', (int) $request->boolean('is_admin'));
            }
            if ($request->filled('is_active')) {
                $query->where('is_active', (int) $request->boolean('is_active'));
            }
            if ($request->filled('search')) {
                $term = (string) $request->input('search');
                $query->where(function ($q) use ($term) {
                    $q->where('email', 'like', "%{$term}%")
                        ->orWhere('username', 'like', "%{$term}%")
                        ->orWhere('first_name', 'like', "%{$term}%")
                        ->orWhere('last_name', 'like', "%{$term}%");
                });
            }

            $users = $query->orderByDesc('id')->paginate($limit);
            $users->appends($request->except('page'));

            return UserApiResource::collection($users);
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
     *     path="/api/module/users",
     *     operationId="api.module.users.store",
     *     tags={"Users"},
     *     summary="Create a new user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — admin required")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        if ($deny = $this->requireAdmin($request)) {
            return $deny;
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|unique:users,email',
            'username' => 'nullable|string|max:255|unique:users,username',
            'password' => 'required|string|min:6|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'is_admin' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'is_verified' => 'nullable|boolean',
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
            foreach (['is_admin', 'is_active', 'is_verified'] as $flag) {
                if (array_key_exists($flag, $data)) {
                    $data[$flag] = (int) $data[$flag];
                }
            }

            $user = User::create($data);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => new UserApiResource($user->fresh()),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/module/users/{id}",
     *     operationId="api.module.users.id.show",
     *     tags={"Users"},
     *     summary="Show a single user",
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

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => new UserApiResource($user),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/module/users/{id}",
     *     operationId="api.module.users.id.update",
     *     tags={"Users"},
     *     summary="Update a user",
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

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|max:255|unique:users,email,' . $id,
            'username' => 'nullable|string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|string|min:6|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'is_admin' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'is_verified' => 'nullable|boolean',
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
            foreach (['is_admin', 'is_active', 'is_verified'] as $flag) {
                if (array_key_exists($flag, $data)) {
                    $data[$flag] = (int) $data[$flag];
                }
            }
            if (empty($data['password'])) {
                unset($data['password']);
            }

            $user->update($data);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => new UserApiResource($user->fresh()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/module/users/{id}",
     *     operationId="api.module.users.id.destroy",
     *     tags={"Users"},
     *     summary="Delete a user",
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

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        if ((int) $user->id === (int) $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account via the API.',
            ], Response::HTTP_CONFLICT);
        }

        try {
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully',
                'data' => ['id' => $id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
