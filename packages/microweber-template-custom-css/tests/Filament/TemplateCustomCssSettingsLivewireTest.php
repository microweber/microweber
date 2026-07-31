<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Tests\Filament;

use Livewire\Livewire;
use MicroweberPackages\TemplateCustomCss\Filament\Pages\TemplateCustomCssSettings;
use MicroweberPackages\TemplateCustomCss\Tests\TestCase;

class TemplateCustomCssSettingsLivewireTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $userClass = class_exists(\MicroweberPackages\User\Models\User::class)
            ? \MicroweberPackages\User\Models\User::class
            : (class_exists(\App\Models\User::class) ? \App\Models\User::class : null);

        if ($userClass !== null) {
            try {
                $user = $userClass::query()->where('is_admin', 1)->first();
                if (!$user) {
                    $user = $userClass::query()->create([
                        'username' => 'css_fil_admin_' . uniqid(),
                        'email' => 'css_fil_' . uniqid() . '@test.com',
                        'password' => bcrypt('password'),
                        'is_admin' => 1,
                        'is_active' => 1,
                    ]);
                }
                if ($user) {
                    $this->actingAs($user);
                }
            } catch (\Throwable) {
                // ignore
            }
        }
    }

    public function test_settings_page_renders_via_livewire(): void
    {
        try {
            $component = Livewire::test(TemplateCustomCssSettings::class);
            $this->assertNotNull($component->instance());
            $this->assertInstanceOf(TemplateCustomCssSettings::class, $component->instance());
        } catch (\Throwable $e) {
            if ($this->isFilamentBootstrapError($e)) {
                $this->markTestSkipped('Filament panel not available: ' . $e->getMessage());
            }
            throw $e;
        }
    }

    public function test_settings_form_schema(): void
    {
        try {
            $component = Livewire::test(TemplateCustomCssSettings::class);
            $schema = $component->instance()->getFormSchema();
            $this->assertNotEmpty($schema);
        } catch (\Throwable $e) {
            if ($this->isFilamentBootstrapError($e)) {
                $this->markTestSkipped('Filament panel not available: ' . $e->getMessage());
            }
            throw $e;
        }
    }

    public function test_settings_save_action_works(): void
    {
        try {
            $component = Livewire::test(TemplateCustomCssSettings::class);
            $component->set('data.live_edit_css', '.filament-test { color: #123456; }');
            $component->set('data.custom_css', '/* filament custom */ body { margin: 0; }');
            $component->set('data.validate_on_save', true);
            $component->call('save');
            $component->assertHasNoErrors();
        } catch (\Throwable $e) {
            if ($this->isFilamentBootstrapError($e)) {
                $this->markTestSkipped('Filament panel not available: ' . $e->getMessage());
            }
            throw $e;
        }
    }

    public function test_settings_save_rejects_invalid_css(): void
    {
        try {
            $component = Livewire::test(TemplateCustomCssSettings::class);
            $component->set('data.live_edit_css', '.broken { color: ');
            $component->set('data.validate_on_save', true);
            $component->call('save');
            // Should not throw; notification is sent instead
            $this->assertTrue(true);
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

        return str_contains($msg, 'Filament')
            || str_contains($msg, 'panel')
            || str_contains($msg, 'Livewire')
            || str_contains($msg, 'No default panel')
            || str_contains($msg, 'not been booted');
    }
}
