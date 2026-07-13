<?php

declare(strict_types=1);

namespace MicroweberPackages\Passport\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Stateless API auth controller that issues and revokes Passport
 * personal-access tokens via a JSON API.
 *
 * Works with any Eloquent User model that uses the Passport
 * HasApiTokens trait.
 */
class AuthController
{
    /**
     * Issue a personal-access token in exchange for email + password.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $email = $request->input('email') ?: $request->input('username');

        if (!$email) {
            return response()->json(['message' => 'Email or username is required'], 422);
        }

        $userModel = config('auth.providers.users.model');
        $user = $userModel::where('email', $email)->first();

        if (!$user) {
            $user = $userModel::where('username', $email)->first();
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Passport's createToken() returns PersonalAccessTokenResult.
        // The plain-text JWT lives in ->accessToken (NOT ->plainTextToken,
        // which is a Sanctum API).
        $result = $user->createToken('AuthToken');
        $token = $result->accessToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    /**
     * Revoke the current bearer token.
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()?->token();

        if ($token) {
            $token->revoke();
        }

        return response()->json(['message' => 'Logged out']);
    }
}