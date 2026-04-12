<?php

namespace Database\Factories\Modules\Backup\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Backup\Models\BackupSchedule;

class BackupScheduleFactory extends Factory
{
    protected $model = BackupSchedule::class;

    public function definition(): array
    {
        return [
            'name'           => $this->faker->words(3, true) . ' backup',
            'type'           => $this->faker->randomElement(['contentBackup', 'fullBackup']),
            'include_media'  => $this->faker->boolean(),
            'frequency'      => $this->faker->randomElement(['hourly', 'daily', 'weekly', 'monthly']),
            'time'           => $this->faker->time('H:i'),
            'retention_days' => $this->faker->randomElement([7, 14, 30]),
            'is_active'      => true,
        ];
    }
}
