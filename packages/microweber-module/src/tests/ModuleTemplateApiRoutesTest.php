<?php

declare(strict_types=1);

namespace MicroweberPackages\Module\tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ModuleTemplateApiRoutesTest extends TestCase
{
    #[Test]
    public function it_rejects_save_module_as_template_for_guests(): void
    {
        $response = $this->post(route('api.save_module_as_template'), [
            'name' => 'test-preset',
            'module' => 'logo',
        ]);

        $this->assertTrue(
            in_array($response->status(), [401, 403, 302], true),
            'Guest must not save module templates, status=' . $response->status()
        );
    }

    #[Test]
    public function it_rejects_delete_module_as_template_for_guests(): void
    {
        $response = $this->post(route('api.delete_module_as_template'), [
            'id' => 1,
        ]);

        $this->assertTrue(
            in_array($response->status(), [401, 403, 302], true),
            'Guest must not delete module templates, status=' . $response->status()
        );
    }

    #[Test]
    public function it_saves_module_as_template_as_admin(): void
    {
        $this->loginAsAdmin();

        $response = $this->post(route('api.save_module_as_template'), [
            'name' => 'api-expose-refactor-test-' . uniqid(),
            'module' => 'logo',
            'module_attrs' => json_encode(['foo' => 'bar']),
        ]);

        $response->assertSuccessful();
    }

    #[Test]
    public function it_validates_delete_module_as_template_ids(): void
    {
        $this->loginAsAdmin();

        $response = $this->post(route('api.delete_module_as_template'), [
            'ids' => ['not-an-int'],
        ]);

        $this->assertTrue(
            in_array($response->status(), [302, 422], true),
            'Invalid ids should fail validation, status=' . $response->status()
        );
    }
}
