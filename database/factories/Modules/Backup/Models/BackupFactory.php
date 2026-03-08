<?php

namespace Database\Factories\Modules\Backup\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Backup\Models\Backup;

class BackupFactory extends Factory
{
    protected $model = Backup::class;

    public function definition(): array
    {
        $filename = 'backup_' . $this->faker->dateTimeThisMonth()->format('Y-m-d-His') . '.zip';
        return [
            'filename' => $filename,
            'date'     => $this->faker->date('Y-m-d'),
            'time'     => $this->faker->time('H:i:s'),
            'size'     => (string) $this->faker->numberBetween(1024 * 100, 1024 * 1024 * 50),
        ];
    }
}
