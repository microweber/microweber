<?php

declare(strict_types=1);

namespace MicroweberPackages\LaravelTemplates\Tests\Standalone;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\LaravelTemplates\Contracts\TemplatesRepositoryInterface;
use MicroweberPackages\LaravelTemplates\Models\SystemTemplates;
use PHPUnit\Framework\Attributes\Test;

/**
 * Standalone coverage for the package's dependency-free units: the Eloquent
 * model that backs the database template activator, and the repository
 * contract interface.
 */
class SystemTemplatesModelTest extends TestCase
{
    #[Test]
    public function system_templates_model_resolves_and_maps_its_table(): void
    {
        $model = new SystemTemplates();

        $this->assertInstanceOf(Model::class, $model);
        $this->assertSame('system_templates', $model->getTable());
    }

    #[Test]
    public function system_templates_model_exposes_the_expected_fillable_columns(): void
    {
        $fillable = (new SystemTemplates())->getFillable();

        foreach (['name', 'path', 'type', 'status'] as $column) {
            $this->assertContains(
                $column,
                $fillable,
                "SystemTemplates should allow mass-assignment of '{$column}'."
            );
        }
    }

    #[Test]
    public function templates_repository_contract_is_an_interface(): void
    {
        $this->assertTrue(
            interface_exists(TemplatesRepositoryInterface::class),
            'The templates repository contract should be an autoloadable interface.'
        );
    }
}
