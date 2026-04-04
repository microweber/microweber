<?php

namespace Modules\Backup\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Backup\Models\BackupSchedule;

class BackupScheduleFactory extends Factory
{
    protected $model = BackupSchedule::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(3, true) . ' Backup',
            'type' => $this->faker->randomElement(['contentBackup', 'fullBackup', 'customBackup']),
            'tables' => null,
            'include_media' => $this->faker->boolean(),
            'frequency' => $this->faker->randomElement(['hourly', 'daily', 'weekly', 'monthly']),
            'time' => $this->faker->time('H:i'),
            'day_of_week' => $this->faker->randomElement([0, 1, 2, 3, 4, 5, 6]),
            'day_of_month' => $this->faker->randomElement(range(1, 28)),
            'retention_days' => $this->faker->randomElement([7, 14, 30, 60, 90]),
            'is_active' => $this->faker->boolean(80),
            'last_run_at' => $this->faker->optional()->dateTimeBetween('-30 days', 'now'),
            'next_run_at' => $this->faker->optional()->dateTimeBetween('now', '+30 days'),
        ];
    }
}
