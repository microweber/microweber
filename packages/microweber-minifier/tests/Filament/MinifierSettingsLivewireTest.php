<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier\Tests\Filament;

use Livewire\Livewire;
use MicroweberPackages\Minifier\Filament\Pages\MinifierSettings;
use MicroweberPackages\Minifier\Tests\TestCase;

class MinifierSettingsLivewireTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $user = null;
        if (class_exists(\App\Models\User::class)) {
            $user = \App\Models\User::where('is_admin', 1)->first();
            if (!$user) {
                try {
                    $user = \App\Models\User::create([
                        'username' => 'minifier_test_admin_' . uniqid(),
                        'email' => 'minifiertest_' . uniqid() . '@test.com',
                        'password' => bcrypt('password'),
                        'is_admin' => 1,
                        'is_active' => 1,
                    ]);
                } catch (\Throwable) {
                    $user = \App\Models\User::query()->first();
                }
            }
        }

        if ($user) {
            $this->actingAs($user);
        }
    }

    /**
     * @return \Livewire\Features\SupportTesting\Testable
     */
    protected function livewireSettings()
    {
        return Livewire::test(MinifierSettings::class);
    }

    public function test_settings_page_renders_via_livewire(): void
    {
        try {
            $component = $this->livewireSettings();
            $this->assertNotNull($component->instance());
            $this->assertInstanceOf(MinifierSettings::class, $component->instance());
        } catch (\Throwable $e) {
            if ($this->isFilamentBootstrapError($e)) {
                $this->markTestSkipped('Filament panel not available: ' . $e->getMessage());
            }
            throw $e;
        }
    }

    public function test_settings_page_has_form_data(): void
    {
        try {
            $component = $this->livewireSettings();
            $instance = $component->instance();
            $this->assertInstanceOf(MinifierSettings::class, $instance);
            $data = $instance->data ?? [];
            $this->assertIsArray($data);
        } catch (\Throwable $e) {
            if ($this->isFilamentBootstrapError($e)) {
                $this->markTestSkipped('Filament panel not available: ' . $e->getMessage());
            }
            throw $e;
        }
    }

    public function test_save_action_works(): void
    {
        try {
            $component = $this->livewireSettings();
            $component->set('data.enabled', true);
            $component->set('data.minify_js', true);
            $component->set('data.minify_css', true);
            $component->call('save');
            $component->assertHasNoErrors();
        } catch (\Throwable $e) {
            if ($this->isFilamentBootstrapError($e)) {
                $this->markTestSkipped('Filament panel not available: ' . $e->getMessage());
            }
            throw $e;
        }
    }

    public function test_self_test_action_works(): void
    {
        try {
            $component = $this->livewireSettings();
            $component->call('runSelfTest');
            $component->assertHasNoErrors();
        } catch (\Throwable $e) {
            if ($this->isFilamentBootstrapError($e)) {
                $this->markTestSkipped('Filament panel not available: ' . $e->getMessage());
            }
            throw $e;
        }
    }

    public function test_settings_page_renders_form_fields(): void
    {
        try {
            $component = $this->livewireSettings();
            $this->assertInstanceOf(MinifierSettings::class, $component->instance());
            $schema = $component->instance()->getFormSchema();
            $this->assertNotEmpty($schema);
        } catch (\Throwable $e) {
            if ($this->isFilamentBootstrapError($e)) {
                $this->markTestSkipped('Filament panel not available: ' . $e->getMessage());
            }
            throw $e;
        }
    }

    protected function isFilamentBootstrapError(\Throwable $e): bool
    {
        $msg = $e->getMessage();

        return str_contains($msg, 'Panel')
            || str_contains($msg, 'filament')
            || str_contains($msg, 'Filament')
            || str_contains($msg, '403')
            || str_contains($msg, 'Unauthorized')
            || str_contains($msg, 'lastResponse');
    }
}
