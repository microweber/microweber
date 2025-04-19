<?php

namespace MicroweberPackages\User\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Laravel\Passport\PersonalAccessClient;
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


        /*php artisan passport:keys --force
php artisan passport:client --personal --name="Personal Access Client"*/


        $client = Passport::personalAccessClient();

        if (! $client->exists()) {
//            \Artisan::call('passport:client', [
//                '--personal' => true,
//                '--name' => 'Personal Access Client',
//                '--no-interaction' => true,
//            ]);
        }

        // Create personal access client if it doesn't exist
        $client = Client::where('personal_access_client', 1)->first();

        if (!$client) {
            $client = Client::create([
                'user_id' => $user->id,
                'name' => 'Personal Access Client',
                'secret' => Str::random(40),
                'provider' => null,
                'redirect' => '',
                'personal_access_client' => true,
                'password_client' => false,
                'revoked' => false,
            ]);

            PersonalAccessClient::create([
                'client_id' => $client->id,
            ]);
        }

        $token = $user->createToken('AuthToken')->accessToken;

        return response()->json([
            'access_token' => $token,
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
