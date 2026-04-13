<?php

declare(strict_types=1);

namespace Modules\Ai\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Ai\Models\McpClient;

class McpClientFactory extends Factory
{
    protected $model = McpClient::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->company();

        return [
            'name' => $name,
            'slug' => $this->faker->unique()->slug(),
            'description' => $this->faker->optional()->sentence(),
            'allowed_scopes' => ['mcp:access'],
            'allowed_tools' => ['tools/list'],
            'allowed_modules' => ['ai'],
            'rate_limit_per_minute' => 60,
            'is_active' => true,
            'last_used_at' => null,
            'revoked_at' => null,
            'created_by_user_id' => null,
            'updated_by_user_id' => null,
        ];
    }
}
