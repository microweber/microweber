<?php

declare(strict_types=1);

namespace MicroweberPackages\Update\Tests;

use Illuminate\Database\Migrations\Migration;
use PHPUnit\Framework\Attributes\Test;

class MigrationsTest extends TestCase
{
    private function migrationsPath(): string
    {
        return dirname(__DIR__) . '/src/database/migrations';
    }

    #[Test]
    public function migration_files_are_shipped(): void
    {
        $files = glob($this->migrationsPath() . '/*.php');

        $this->assertNotEmpty($files, 'The update package should ship migration files.');
    }

    #[Test]
    public function each_migration_file_returns_a_migration_instance(): void
    {
        foreach (glob($this->migrationsPath() . '/*.php') as $file) {
            $migration = require $file;

            $this->assertInstanceOf(
                Migration::class,
                $migration,
                basename($file) . ' should return an anonymous Migration instance.'
            );
        }
    }
}
