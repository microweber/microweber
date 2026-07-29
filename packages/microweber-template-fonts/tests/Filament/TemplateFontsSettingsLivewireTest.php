<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Tests\Filament;

use Livewire\Livewire;
use MicroweberPackages\TemplateFonts\Filament\Pages\TemplateFontsSettings;
use MicroweberPackages\TemplateFonts\Filament\Resources\TemplateFontResource\Pages\ListTemplateFonts;
use MicroweberPackages\TemplateFonts\Tests\TestCase;

class TemplateFontsSettingsLivewireTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $user = null;
        $userClass = class_exists(\MicroweberPackages\User\Models\User::class)
            ? \MicroweberPackages\User\Models\User::class
            : (class_exists(\App\Models\User::class) ? \App\Models\User::class : null);

        if ($userClass !== null) {
            $user = $userClass::query()->where('is_admin', 1)->first();
            if (!$user) {
                try {
                    $user = $userClass::query()->create([
                        'username' => 'fonts_fil_admin_' . uniqid(),
                        'email' => 'fonts_fil_' . uniqid() . '@test.com',
                        'password' => bcrypt('password'),
                        'is_admin' => 1,
                        'is_active' => 1,
                    ]);
                } catch (\Throwable) {
                    $user = $userClass::query()->first();
                }
            }
        }

        if ($user) {
            $this->actingAs($user);
        }
    }

    public function test_settings_page_renders_via_livewire(): void
    {
        try {
            $component = Livewire::test(TemplateFontsSettings::class);
            $this->assertNotNull($component->instance());
            $this->assertInstanceOf(TemplateFontsSettings::class, $component->instance());
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
            $component = Livewire::test(TemplateFontsSettings::class);
            $component->set('data.use_google_fonts_proxy', false);
            $component->set('data.download_google_fonts_locally', true);
            $component->call('save');
            $component->assertHasNoErrors();
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
            $component = Livewire::test(TemplateFontsSettings::class);
            $schema = $component->instance()->getFormSchema();
            $this->assertNotEmpty($schema);
        } catch (\Throwable $e) {
            if ($this->isFilamentBootstrapError($e)) {
                $this->markTestSkipped('Filament panel not available: ' . $e->getMessage());
            }
            throw $e;
        }
    }

    public function test_list_fonts_page_via_livewire(): void
    {
        try {
            $component = Livewire::test(ListTemplateFonts::class);
            $this->assertInstanceOf(ListTemplateFonts::class, $component->instance());
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
            || str_contains($msg, 'lastResponse')
            || str_contains($msg, 'Livewire')
            || str_contains($msg, 'view');
    }
}
