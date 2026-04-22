<?php

declare(strict_types=1);

namespace Modules\Profile\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\Profile\Http\Resources\ProfileResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * Self-serve profile endpoints: any authenticated user can read/update
 * their own profile. Admin flags (is_admin, is_active, is_verified) are
 * never writable here — those live on /api/module/users for admin callers.
 */
class ProfileApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/module/profile",
     *     operationId="api.module.profile.show",
     *     tags={"Profile"},
     *     summary="Show the authenticated user profile",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — admin required")
     * )
     */
    public function show(Request $request): JsonResponse
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'success' => true,
            'data' => new ProfileResource($request->user()),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/module/profile",
     *     operationId="api.module.profile.update",
     *     tags={"Profile"},
     *     summary="Update the authenticated user profile",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="first_name", type="string"),
     *                 @OA\Property(property="last_name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="phone", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — admin required")
     * )
     */
    public function update(Request $request): JsonResponse
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'thumbnail' => 'nullable|string|max:500',
            'user_information' => 'nullable|string',
            'profile_url' => 'nullable|string|max:500',
            'website_url' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $user->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => new ProfileResource($user->fresh()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/module/profile/change-password",
     *     operationId="api.module.profile.change.password.changepassword",
     *     tags={"Profile"},
     *     summary="Change the authenticated user password",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"current_password", "new_password"},
     *                 @OA\Property(property="current_password", type="string"),
     *                 @OA\Property(property="new_password", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — admin required")
     * )
     */
    public function changePassword(Request $request): JsonResponse
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $request->user();
        $data = $validator->validated();

        if (!Hash::check($data['current_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $user->password = $data['new_password'];
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
