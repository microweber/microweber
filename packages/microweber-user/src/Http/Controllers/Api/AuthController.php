<?php

namespace MicroweberPackages\User\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController
{
    public function login(Request $request)
    {


        $user = null;
        if (!$request->email) {
            if ($request->username) {
                $request->email = $request->username;
            } else {
                return response()->json(['message' => 'Email or username is required'], 422);
            }
        }

        if ($request->email) {
            $user = User::where('email', $request->email)->first();
        } elseif ($request->username) {
            $user = User::where('username', $request->username)->first();
            if (!$user) {
                $user = User::where('email', $request->username)->first();
            }
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
//        $existingToken = PersonalAccessToken::where('tokenable_id', $user->id)->where('tokenable_type', User::class)->first();
//
//        if($existingToken) {
//            return response()->json([
//                'token' =>  $existingToken->token,
//                'token_type' => 'Bearer',
//                'user' => $user
//            ]);
//        }


        // Passport's createToken() returns PersonalAccessTokenResult.
        // The plain-text JWT lives in ->accessToken (not ->plainTextToken
        // which is the Sanctum API).
        $token = $user->createToken('AuthToken')->accessToken;
        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Logged out']);
    }
}
