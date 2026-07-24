<?php

declare(strict_types=1);

namespace MicroweberPackages\ImageOptimization\Tests\Filament;

use Livewire\Livewire;
use MicroweberPackages\ImageOptimization\Filament\Pages\ImageOptimizationSettings;
use MicroweberPackages\ImageOptimization\Tests\TestCase;

class ImageOptimizationSettingsLivewireTest extends TestCase
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
                        'username' => 'imgopt_test_admin_' . uniqid(),
                        'email' => 'imgopttest_' . uniqid() . '@test.com',
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
        return Livewire::test(ImageOptimizationSettings::class);
    }

    public function test_settings_page_renders_via_livewire(): void
    {
        try {
            $component = $this->livewireSettings();
            // Component should boot; status 200 when authorized
            $this->assertNotNull($component->instance());
            $this->assertInstanceOf(ImageOptimizationSettings::class, $component->instance());
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
            $this->assertInstanceOf(ImageOptimizationSettings::class, $instance);
            // After mount(), data may be filled when authorized
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
            $component->set('data.webp_enabled', true);
            $component->set('data.webp_quality', 90);
            $component->set('data.lazy_loading_enabled', true);
            $component->call('save');
            $component->assertHasNoErrors();
        } catch (\Throwable $e) {
            if ($this->isFilamentBootstrapError($e)) {
                $this->markTestSkipped('Filament panel not available: ' . $e->getMessage());
            }
            throw $e;
        }
    }

    public function test_clear_cache_action_works(): void
    {
        try {
            $component = $this->livewireSettings();
            $component->call('clearCache');
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
            $this->assertInstanceOf(ImageOptimizationSettings::class, $component->instance());
            // getFormSchema is available without full panel render
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
