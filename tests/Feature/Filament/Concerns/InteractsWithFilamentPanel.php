<?php

namespace Tests\Feature\Filament\Concerns;

use Filament\Facades\Filament;
use MicroweberPackages\User\Models\User;

/**
 * Trait for Filament resource tests that ensures the admin panel
 * is properly configured with all module routes registered.
 *
 * Usage:
 *   use InteractsWithFilamentPanel;
 *
 *   protected function setUp(): void
 *   {
 *       parent::setUp();
 *       $this->setUpFilamentPanel();
 *   }
 */
trait InteractsWithFilamentPanel
{
    /**
     * The panel ID used for the current test session.
     */
    protected string $filamentPanelId = 'admin';

    /**
     * Set up the Filament admin panel for testing.
     *
     * This ensures:
     * - An admin user is authenticated
     * - The Filament admin panel is set as the current panel
     * - All module-registered Filament resource routes are available
     */
    protected function setUpFilamentPanel(?string $panelId = 'admin'): void
    {
        $this->filamentPanelId = $panelId ?? 'admin';

        $this->filamentActingAsAdmin();

        $panel = Filament::getPanel($this->filamentPanelId);
        Filament::setCurrentPanel($panel);
    }

    /**
     * Authenticate as an admin user for Filament tests.
     *
     * Also available as actingAsAdmin() for backward compatibility.
     */
    protected function filamentActingAsAdmin(array $attributes = []): User
    {
        $user = User::factory()->create(array_merge([
            'is_admin' => 1,
        ], $attributes));

        $this->actingAs($user);

        return $user;
    }

    /**
     * Authenticate as an admin user.
     *
     * Convenience alias for filamentActingAsAdmin() that also ensures the
     * current panel is set. This method is provided for backward compatibility
     * with tests that were written before the trait was introduced.
     */
    protected function actingAsAdmin(array $attributes = []): User
    {
        $user = $this->filamentActingAsAdmin($attributes);
        Filament::setCurrentPanel(Filament::getPanel($this->filamentPanelId));

        return $user;
    }

    /**
     * Authenticate as a non-admin user for Filament tests.
     *
     * Also available as actingAsUser() for backward compatibility.
     */
    protected function filamentActingAsUser(array $attributes = []): User
    {
        $user = User::factory()->create(array_merge([
            'is_admin' => 0,
        ], $attributes));

        $this->actingAs($user);

        return $user;
    }

    /**
     * Authenticate as a non-admin user.
     *
     * Convenience alias for filamentActingAsUser() that also ensures the
     * current panel is set.
     */
    protected function actingAsUser(array $attributes = []): User
    {
        $user = $this->filamentActingAsUser($attributes);
        Filament::setCurrentPanel(Filament::getPanel($this->filamentPanelId));

        return $user;
    }

    /**
     * Get the URL for a Filament resource action.
     *
     * @param string $resourceClass The fully-qualified resource class name
     * @param string $action The action name (index, create, edit, view)
     * @param array $parameters Route parameters (e.g., ['record' => $model])
     */
    protected function getFilamentResourceUrl(string $resourceClass, string $action = 'index', array $parameters = []): string
    {
        return $resourceClass::getUrl($action, $parameters);
    }
}
