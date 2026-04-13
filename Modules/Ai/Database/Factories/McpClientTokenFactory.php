<?php

declare(strict_types=1);

namespace Modules\Ai\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Ai\Models\McpClient;
use Modules\Ai\Models\McpClientToken;

class McpClientTokenFactory extends Factory
{
    protected $model = McpClientToken::class;

    public function definition(): array
    {
        $secret = Str::random(64);

        return [
            'mcp_client_id' => McpClient::factory(),
            'rotated_from_token_id' => null,
            'name' => $this->faker->words(2, true),
            'token_hash' => Hash::make($secret),
            'token_last_eight' => substr($secret, -8),
            'abilities' => ['mcp:access'],
            'last_used_at' => null,
            'last_used_ip' => null,
            'last_used_user_agent' => null,
            'expires_at' => null,
            'revoked_at' => null,
            'revocation_reason' => null,
            'created_by_user_id' => null,
            'revoked_by_user_id' => null,
        ];
    }
}
