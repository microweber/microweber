<?php

namespace Tests\Feature\Filament;

use PHPUnit\Framework\Attributes\Test;

/**
 * Example test demonstrating the FilamentResourceTestCase base class.
 *
 * This test verifies the base test case can be extended and used
 * for testing Filament resources.
 */
class FilamentResourceTestCaseExampleTest extends FilamentResourceTestCase
{
    /**
     * A simple resource class for demonstration.
     * In real usage, this would be an actual Filament Resource class.
     *
     * @return string
     */
    protected function getResourceClass(): string
    {
        return \stdClass::class;
    }

    #[Test]
    public function it_base_test_case_can_be_extended(): void {
        $this->assertTrue(true);
    }

    #[Test]
    public function it_acting_as_admin_creates_admin_user(): void {
        $user = $this->actingAsAdmin();

        $this->assertEquals(1, $user->is_admin);
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function it_acting_as_user_creates_non_admin_user(): void {
        $user = $this->actingAsUser();

        $this->assertEquals(0, $user->is_admin);
        $this->assertAuthenticatedAs($user);
    }
}
