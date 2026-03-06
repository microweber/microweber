<?php

namespace Modules\Backup\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Backup\Models\Backup;

class BackupFactory extends Factory
{
    protected $model = Backup::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'filename' => $this->faker->word() . '_' . time() . '.zip',
            'date' => now()->format('F d Y'),
            'time' => now()->format('H:i:s'),
            'size' => $this->faker->numberBetween(1000, 10000000),
        ];
    }

    /**
     * Create a backup file on disk for testing
     */
    public function createBackupFile(): static
    {
        return $this->afterCreating(function (Backup $backup) {
            $backupLocation = backup_location();
            if (!is_dir($backupLocation)) {
                mkdir($backupLocation, 0755, true);
            }
            
            $filename = $backup->filename;
            $filepath = $backupLocation . $filename;
            
            // Create a dummy backup file
            file_put_contents($filepath, 'dummy backup content');
        });
    }
}
