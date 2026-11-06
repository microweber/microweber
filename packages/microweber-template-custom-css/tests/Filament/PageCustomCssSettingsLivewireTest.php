<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Tests\Filament;

use Livewire\Livewire;
use MicroweberPackages\TemplateCustomCss\Filament\Pages\PageCustomCssSettings;
use MicroweberPackages\TemplateCustomCss\Services\TemplateCustomCssManager;
use MicroweberPackages\TemplateCustomCss\Tests\TestCase;

/**
 * Reachability + behaviour of the manual per-page custom CSS/JS editor page.
 * Mirrors {@see TemplateCustomCssSettingsLivewireTest} (the global sibling).
 */
class PageCustomCssSettingsLivewireTest extends TestCase
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
                        'username' => 'page_css_admin_' . uniqid(),
                        'email' => 'page_css_' . uniqid() . '@test.com',
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

    public function test_page_renders_via_livewire(): void
    {
        try {
            $component = Livewire::test(PageCustomCssSettings::class);
            $this->assertNotNull($component->instance());
            $this->assertInstanceOf(PageCustomCssSettings::class, $component->instance());
        } catch (\Throwable $e) {
            if ($this->isFilamentBootstrapError($e)) {
                $this->markTestSkipped('Filament panel not available: ' . $e->getMessage());
            }
            throw $e;
        }
    }

    public function test_save_writes_per_page_css_and_js(): void
    {
        $contentId = $this->seedPage();
        if ($contentId === null) {
            $this->markTestSkipped('No content table available in this harness.');
        }

        try {
            $component = Livewire::test(PageCustomCssSettings::class);
            $component->set('data.content_id', $contentId);
            $component->set('data.page_css', '.page-only { color: #abcdef; }');
            $component->set('data.page_js', 'console.log("page only");');
            $component->call('save');
            $component->assertHasNoErrors();
        } catch (\Throwable $e) {
            if ($this->isFilamentBootstrapError($e)) {
                $this->markTestSkipped('Filament panel not available: ' . $e->getMessage());
            }
            throw $e;
        }

        $le = app(TemplateCustomCssManager::class)->liveEdit();
        $this->assertStringContainsString('#abcdef', $le->getPageCssContent($contentId));
        $this->assertStringContainsString('page only', $le->getPageJsContent($contentId));

        // cleanup so the test leaves no per-page files behind
        $le->savePageCssContent($contentId, '');
        $le->savePageJsContent($contentId, '');

        $this->cleanupPage($contentId);
    }

    /**
     * Create a throwaway page row the picker will accept; null when the content
     * table is absent (standalone package harness).
     */
    protected function seedPage(): ?int
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('content')) {
                return null;
            }

            return (int) \Illuminate\Support\Facades\DB::table('content')->insertGetId([
                'title' => 'Per-page CSS test ' . uniqid(),
                'url' => 'per-page-css-test-' . uniqid(),
                'content_type' => 'page',
                'is_deleted' => 0,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable) {
            return null;
        }
    }

    protected function cleanupPage(int $contentId): void
    {
        try {
            \Illuminate\Support\Facades\DB::table('content')->where('id', $contentId)->delete();
        } catch (\Throwable) {
            // ignore
        }
    }

    public function test_save_without_a_page_is_a_no_op(): void
    {
        try {
            $component = Livewire::test(PageCustomCssSettings::class);
            $component->set('data.content_id', null);
            $component->set('data.page_css', '.x { color: red; }');
            $component->call('save');
            $component->assertHasNoErrors();
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
