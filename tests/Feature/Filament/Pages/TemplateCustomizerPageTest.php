<?php

namespace Tests\Feature\Filament\Pages;

use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Modules\Settings\Filament\Pages\AdminTemplateCustomizerPage;
use Modules\Settings\Filament\Pages\AdminTemplatePage;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

class TemplateCustomizerPageTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_can_render_template_customizer_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AdminTemplateCustomizerPage::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_has_template_selection_form(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AdminTemplateCustomizerPage::class)
            ->assertSuccessful()
            ->assertSee('Template');
    }

    #[Test]
    public function it_has_customization_sections(): void
    {
        $this->actingAsAdmin();

        // Sections are only visible when a template is selected
        Livewire::test(AdminTemplateCustomizerPage::class)
            ->set('selectedTemplate', 'Bootstrap')
            ->assertSuccessful()
            ->assertSee('Appearance Settings')
            ->assertSee('Typography');
    }

    #[Test]
    public function it_has_live_preview_section(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AdminTemplateCustomizerPage::class)
            ->assertSuccessful()
            ->assertSee('Live Preview');
    }

    #[Test]
    public function it_can_update_template_selection(): void
    {
        $this->actingAsAdmin();

        $response = Livewire::test(AdminTemplateCustomizerPage::class)
            ->set('selectedTemplate', 'Bootstrap');

        $response->assertSuccessful();
        $response->assertSet('selectedTemplate', 'Bootstrap');
    }

    #[Test]
    public function it_generates_preview_url(): void
    {
        $this->actingAsAdmin();

        $response = Livewire::test(AdminTemplateCustomizerPage::class)
            ->set('selectedTemplate', 'Bootstrap');

        $response->assertSuccessful();

        $previewUrl = $response->get('previewUrl');
        $this->assertNotEmpty($previewUrl);
        $this->assertStringContainsString('preview_template', $previewUrl);
    }

    #[Test]
    public function it_can_save_color_customization(): void
    {
        $this->actingAsAdmin();

        $templateName = 'Bootstrap';

        $response = Livewire::test(AdminTemplateCustomizerPage::class)
            ->set('selectedTemplate', $templateName)
            ->call('saveCustomization', 'primary_color', '#ff0000');

        $response->assertSuccessful();

        // Verify the option was saved
        $optionGroup = 'mw-template-' . $templateName;
        $option = get_option('primary_color', $optionGroup);
        $this->assertEquals('#ff0000', $option);
    }

    #[Test]
    public function it_can_save_font_customization(): void
    {
        $this->actingAsAdmin();

        $templateName = 'Bootstrap';

        $response = Livewire::test(AdminTemplateCustomizerPage::class)
            ->set('selectedTemplate', $templateName)
            ->call('saveCustomization', 'heading_font', 'Roboto');

        $response->assertSuccessful();

        // Verify the option was saved
        $optionGroup = 'mw-template-' . $templateName;
        $option = get_option('heading_font', $optionGroup);
        $this->assertEquals('Roboto', $option);
    }

    #[Test]
    public function it_clears_cache_on_save(): void
    {
        $this->actingAsAdmin();

        $templateName = 'Bootstrap';
        Cache::put('template_settings_' . $templateName, ['test' => 'data'], 3600);

        Livewire::test(AdminTemplateCustomizerPage::class)
            ->set('selectedTemplate', $templateName)
            ->call('saveCustomization', 'primary_color', '#00ff00');

        // Verify cache was cleared
        $this->assertFalse(Cache::has('template_settings_' . $templateName));
    }

    #[Test]
    public function it_can_reset_template_settings(): void
    {
        $this->actingAsAdmin();

        $templateName = 'Bootstrap';
        $optionGroup = 'mw-template-' . $templateName;

        // Save a customization first
        save_option([
            'option_key' => 'test_setting',
            'option_value' => 'test_value',
            'option_group' => $optionGroup,
        ]);

        // Reset settings
        $response = Livewire::test(AdminTemplateCustomizerPage::class)
            ->set('selectedTemplate', $templateName)
            ->call('resetTemplateSettings');

        // After reset, the template options should be deleted from the DB
        $option = get_option('test_setting', $optionGroup);
        $this->assertNull($option);
    }

    #[Test]
    public function it_dispatches_refresh_preview_event(): void
    {
        $this->actingAsAdmin();

        $response = Livewire::test(AdminTemplateCustomizerPage::class)
            ->set('selectedTemplate', 'Bootstrap')
            ->call('saveCustomization', 'primary_color', '#0000ff');

        $response->assertSuccessful();
        $response->assertDispatched('refreshPreview');
    }

    #[Test]
    public function it_has_quick_actions(): void
    {
        $this->actingAsAdmin();

        // Quick Actions section is only visible when a template is selected
        Livewire::test(AdminTemplateCustomizerPage::class)
            ->set('selectedTemplate', 'Bootstrap')
            ->assertSuccessful()
            ->assertSee('Quick Actions');
    }

    #[Test]
    public function it_shows_template_info_card(): void
    {
        $this->actingAsAdmin();

        // Template Information is only visible when a template is selected
        Livewire::test(AdminTemplateCustomizerPage::class)
            ->set('selectedTemplate', 'Bootstrap')
            ->assertSuccessful()
            ->assertSee('Template Information');
    }

    #[Test]
    public function it_has_export_and_import_actions(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AdminTemplateCustomizerPage::class)
            ->assertSuccessful()
            ->assertSee('Export Settings')
            ->assertSee('Import Settings');
    }

    #[Test]
    public function it_requires_authentication(): void
    {
        \Illuminate\Support\Facades\Auth::logout();
        $this->get(route('filament.admin.pages.template-customization'))
            ->assertRedirect();
    }

    #[Test]
    public function it_requires_admin_role(): void
    {
        $user = \App\Models\User::factory()->create([
            'is_admin' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('filament.admin.pages.template-customization'))
            ->assertForbidden();
    }
}
