<?php

namespace Tests\Feature\Filament;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

/**
 * Base test case for Filament resource tests.
 *
 * Provides common assertions and helper methods for testing Filament resources
 * including authentication, form interactions, and table operations.
 *
 * The InteractsWithFilamentPanel trait ensures the Filament admin panel is
 * properly configured with all module routes registered during tests.
 */
abstract class FilamentResourceTestCase extends TestCase
{
    use RefreshDatabase;
    use InteractsWithFilamentPanel;

    /**
     * The resource class being tested.
     * Must be implemented by subclasses.
     *
     * @return string
     */
    abstract protected function getResourceClass(): string;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    /**
     * Assert that text is visible in the Livewire component.
     *
     * @param \Livewire\Features\SupportTesting\Testable $component
     * @param string $text
     * @return \Livewire\Features\SupportTesting\Testable
     */
    protected function assertComponentSee(\Livewire\Features\SupportTesting\Testable $component, string $text): \Livewire\Features\SupportTesting\Testable
    {
        return $component->assertSee($text);
    }

    /**
     * Assert that text is not visible in the Livewire component.
     *
     * @param \Livewire\Features\SupportTesting\Testable $component
     * @param string $text
     * @return \Livewire\Features\SupportTesting\Testable
     */
    protected function assertComponentDontSee(\Livewire\Features\SupportTesting\Testable $component, string $text): \Livewire\Features\SupportTesting\Testable
    {
        return $component->assertDontSee($text);
    }

    /**
     * Assert that a database record exists with the given attributes.
     *
     * @param string $table
     * @param array $data
     * @return void
     */
    protected function assertRecordExists(string $table, array $data): void
    {
        $this->assertDatabaseHas($table, $data);
    }

    /**
     * Assert that a database record does not exist with the given attributes.
     *
     * @param string $table
     * @param array $data
     * @return void
     */
    protected function assertRecordMissing(string $table, array $data): void
    {
        $this->assertDatabaseMissing($table, $data);
    }

    /**
     * Get the resource's model class.
     *
     * @return string|null
     */
    protected function getModelClass(): ?string
    {
        $resourceClass = $this->getResourceClass();

        if (!class_exists($resourceClass)) {
            return null;
        }

        return $resourceClass::getModel();
    }

    /**
     * Create a model instance for testing.
     *
     * @param array $attributes
     * @return mixed
     */
    protected function createModel(array $attributes = [])
    {
        $modelClass = $this->getModelClass();

        if (!$modelClass) {
            throw new \RuntimeException('Model class not found for resource: ' . $this->getResourceClass());
        }

        return $modelClass::factory()->create($attributes);
    }

    /**
     * Assert that the user can access the resource index page.
     *
     * @param string|null $pageClass
     * @return mixed
     */
    protected function assertCanAccessIndex(?string $pageClass = null)
    {
        $this->actingAsAdmin();

        $listPage = $pageClass ?? $this->getResourceClass()::getPages()['index'] ?? null;

        if (!$listPage) {
            throw new \RuntimeException('List page not found for resource');
        }

        return \Livewire\Livewire::test($listPage)
            ->assertSuccessful();
    }

    /**
     * Assert that a guest cannot access the resource.
     *
     * @param string $route
     * @return $this
     */
    protected function assertGuestCannotAccess(string $route): self
    {
        $response = $this->get($route);
        $response->assertRedirect('/admin/login');

        return $this;
    }

    /**
     * Get the route name for the resource.
     *
     * @return string
     */
    protected function getResourceRoute(): string
    {
        $resourceClass = $this->getResourceClass();
        $panelId = 'admin';

        // Extract resource name from class
        $className = class_basename($resourceClass);
        $resourceName = str_replace('Resource', '', $className);
        $resourceName = strtolower($resourceName);

        return "filament.{$panelId}.resources.{$resourceName}";
    }
}
