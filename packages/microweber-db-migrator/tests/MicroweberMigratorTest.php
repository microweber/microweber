<?php

namespace MicroweberPackages\DbMigrator\Tests;

use Illuminate\Support\Facades\Schema;
use MicroweberPackages\DbMigrator\MicroweberMigrator;
use PHPUnit\Framework\Attributes\Test;

class MicroweberMigratorTest extends TestCase
{
    #[Test]
    public function it_is_registered_as_singleton(): void
    {
        $migrator = $this->app->make('mw_migrator');

        $this->assertInstanceOf(MicroweberMigrator::class, $migrator);
        $this->assertSame($migrator, $this->app->make('mw_migrator'));
    }

    #[Test]
    public function it_creates_migrations_table_on_run(): void
    {
        $migrator = $this->app->make('mw_migrator');
        $migrator->run([]);

        $this->assertTrue(Schema::hasTable('migrations'));
    }
}