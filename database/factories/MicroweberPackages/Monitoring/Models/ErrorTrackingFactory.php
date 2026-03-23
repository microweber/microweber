<?php

namespace Database\Factories\MicroweberPackages\Monitoring\Models;

use MicroweberPackages\Monitoring\Models\ErrorTracking;
use Illuminate\Database\Eloquent\Factories\Factory;

class ErrorTrackingFactory extends Factory
{
    protected $model = ErrorTracking::class;

    public function definition(): array
    {
        return [
            'level' => $this->faker->randomElement(['critical', 'error', 'warning', 'notice', 'info']),
            'message' => $this->faker->sentence(),
            'exception_class' => $this->faker->randomElement(['Exception', 'RuntimeException', 'InvalidArgumentException', 'DatabaseException']),
            'file' => '/app/Controllers/' . $this->faker->word() . 'Controller.php',
            'line' => $this->faker->numberBetween(1, 500),
            'code' => $this->faker->randomElement(['500', '404', '403', '422']),
            'trace' => "#0 /app/Controller.php(25): test()\n#1 /app/Routes.php(10): Controller->action()",
            'url' => $this->faker->url(),
            'method' => $this->faker->randomElement(['GET', 'POST', 'PUT', 'DELETE']),
            'user_id' => null,
            'user_ip' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'server_data' => json_encode(['REQUEST_URI' => '/test', 'HTTP_HOST' => 'example.com']),
            'context' => json_encode(['custom' => 'data']),
            'is_resolved' => $this->faker->boolean(30),
            'occurrence_count' => $this->faker->numberBetween(1, 10),
            'last_occurred_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'resolved_at' => null,
            'resolved_by' => null,
            'resolution_notes' => null,
        ];
    }

    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_resolved' => true,
            'resolved_at' => now(),
        ]);
    }

    public function critical(): static
    {
        return $this->state(fn (array $attributes) => [
            'level' => 'critical',
        ]);
    }

    public function unresolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_resolved' => false,
        ]);
    }
}
