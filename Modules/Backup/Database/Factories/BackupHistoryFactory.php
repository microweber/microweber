<?php

namespace Modules\Backup\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Backup\Models\BackupHistory;
use Modules\Backup\Models\BackupSchedule;

class BackupHistoryFactory extends Factory
{
    protected $model = BackupHistory::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['manual', 'scheduled']);
        $status = $this->faker->randomElement(['pending', 'running', 'completed', 'failed']);
        $backupType = $this->faker->randomElement(['contentBackup', 'fullBackup', 'customBackup']);
        
        return [
            'backup_schedule_id' => $type === 'scheduled' ? BackupSchedule::factory()->create()->id : null,
            'type' => $type,
            'backup_type' => $backupType,
            'filename' => $this->faker->optional()->word() . '_' . time() . '.zip',
            'filepath' => null,
            'size' => $status === 'completed' ? $this->faker->numberBetween(1000, 10000000) : null,
            'status' => $status,
            'tables' => null,
            'include_media' => $this->faker->boolean(),
            'error_message' => $status === 'failed' ? $this->faker->sentence() : null,
            'started_at' => now(),
            'completed_at' => in_array($status, ['completed', 'failed']) ? now()->addMinutes($this->faker->numberBetween(1, 30)) : null,
        ];
    }

    /**
     * State for a completed backup
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'size' => $this->faker->numberBetween(1000, 10000000),
            'completed_at' => now()->addMinutes($this->faker->numberBetween(1, 30)),
        ]);
    }

    /**
     * State for a failed backup
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'error_message' => $this->faker->sentence(),
            'completed_at' => now()->addMinutes($this->faker->numberBetween(1, 5)),
        ]);
    }
}
