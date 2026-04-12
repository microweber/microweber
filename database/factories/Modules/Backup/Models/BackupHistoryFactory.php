<?php

namespace Database\Factories\Modules\Backup\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Backup\Models\BackupHistory;

class BackupHistoryFactory extends Factory
{
    protected $model = BackupHistory::class;

    public function definition(): array
    {
        $filename = 'backup_' . $this->faker->dateTimeThisMonth()->format('Y-m-d-His') . '.zip';
        return [
            'type'          => $this->faker->randomElement(['manual', 'scheduled']),
            'backup_type'   => $this->faker->randomElement(['contentBackup', 'fullBackup', 'customBackup']),
            'filename'      => $filename,
            'filepath'      => storage_path('backups/' . $filename),
            'size'          => $this->faker->numberBetween(1024 * 100, 1024 * 1024 * 50),
            'status'        => 'completed',
            'include_media' => $this->faker->boolean(),
            'started_at'    => $this->faker->dateTimeThisMonth(),
            'completed_at'  => $this->faker->dateTimeThisMonth(),
        ];
    }
}
