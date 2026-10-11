<?php

namespace MicroweberPackages\Filament\Tests;

use Livewire\Livewire;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Tests to verify the Filament v5.6.7 class migration.
 *
 * Confirms that:
 *  - All old aliased classes (Filament\Tables\Actions\*) are no longer needed
 *    because the codebase now uses the real Filament\Actions\* classes directly.
 *  - The new canonical Filament v5 classes exist and are loadable.
 *  - Livewire table components that use the new action classes render correctly.
 */
class FilamentV5MigrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function actingAsAdmin(): User
    {
        $user = User::factory()->create([
            'is_admin' => 1,
        ]);

        $this->actingAs($user);

        return $user;
    }

    // ─── Class existence checks ─────────────────────────────────────────

    #[Test]
    public function it_filament_actions_action_class_exists(): void
    {
        $this->assertTrue(
            class_exists(\Filament\Actions\Action::class),
            'Filament\Actions\Action must exist in v5.6.7'
        );
    }

    #[Test]
    public function it_filament_actions_create_action_class_exists(): void
    {
        $this->assertTrue(
            class_exists(\Filament\Actions\CreateAction::class),
            'Filament\Actions\CreateAction must exist in v5.6.7'
        );
    }

    #[Test]
    public function it_filament_actions_edit_action_class_exists(): void
    {
        $this->assertTrue(
            class_exists(\Filament\Actions\EditAction::class),
            'Filament\Actions\EditAction must exist in v5.6.7'
        );
    }

    #[Test]
    public function it_filament_actions_delete_action_class_exists(): void
    {
        $this->assertTrue(
            class_exists(\Filament\Actions\DeleteAction::class),
            'Filament\Actions\DeleteAction must exist in v5.6.7'
        );
    }

    #[Test]
    public function it_filament_actions_view_action_class_exists(): void
    {
        $this->assertTrue(
            class_exists(\Filament\Actions\ViewAction::class),
            'Filament\Actions\ViewAction must exist in v5.6.7'
        );
    }

    #[Test]
    public function it_filament_actions_bulk_action_class_exists(): void
    {
        $this->assertTrue(
            class_exists(\Filament\Actions\BulkAction::class),
            'Filament\Actions\BulkAction must exist in v5.6.7'
        );
    }

    #[Test]
    public function it_filament_actions_bulk_action_group_class_exists(): void
    {
        $this->assertTrue(
            class_exists(\Filament\Actions\BulkActionGroup::class),
            'Filament\Actions\BulkActionGroup must exist in v5.6.7'
        );
    }

    #[Test]
    public function it_filament_actions_delete_bulk_action_class_exists(): void
    {
        $this->assertTrue(
            class_exists(\Filament\Actions\DeleteBulkAction::class),
            'Filament\Actions\DeleteBulkAction must exist in v5.6.7'
        );
    }

    #[Test]
    public function it_filament_actions_action_group_class_exists(): void
    {
        $this->assertTrue(
            class_exists(\Filament\Actions\ActionGroup::class),
            'Filament\Actions\ActionGroup must exist in v5.6.7'
        );
    }

    #[Test]
    public function it_filament_actions_import_action_class_exists(): void
    {
        $this->assertTrue(
            class_exists(\Filament\Actions\ImportAction::class),
            'Filament\Actions\ImportAction must exist in v5.6.7'
        );
    }

    #[Test]
    public function it_filament_actions_export_action_class_exists(): void
    {
        $this->assertTrue(
            class_exists(\Filament\Actions\ExportAction::class),
            'Filament\Actions\ExportAction must exist in v5.6.7'
        );
    }

    #[Test]
    public function it_filament_actions_export_bulk_action_class_exists(): void
    {
        $this->assertTrue(
            class_exists(\Filament\Actions\ExportBulkAction::class),
            'Filament\Actions\ExportBulkAction must exist in v5.6.7'
        );
    }

    #[Test]
    public function it_filament_schemas_get_class_exists(): void
    {
        $this->assertTrue(
            class_exists(\Filament\Schemas\Components\Utilities\Get::class),
            'Filament\Schemas\Components\Utilities\Get must exist in v5.6.7'
        );
    }

    #[Test]
    public function it_filament_schemas_set_class_exists(): void
    {
        $this->assertTrue(
            class_exists(\Filament\Schemas\Components\Utilities\Set::class),
            'Filament\Schemas\Components\Utilities\Set must exist in v5.6.7'
        );
    }

    #[Test]
    public function it_filament_schemas_section_class_exists(): void
    {
        $this->assertTrue(
            class_exists(\Filament\Schemas\Components\Section::class),
            'Filament\Schemas\Components\Section must exist in v5.6.7'
        );
    }

    // ─── Verify aliases are no longer registered ────────────────────────

    #[Test]
    public function it_service_provider_does_not_register_aliases(): void
    {
        // Read the service provider file and confirm no class_alias calls
        $providerFile = file_get_contents(
            base_path('src/MicroweberPackages/Filament/Providers/MicroweberFilamentServiceProvider.php')
        );

        $this->assertStringNotContainsString(
            'class_alias',
            $providerFile,
            'MicroweberFilamentServiceProvider should not contain class_alias calls after migration'
        );
    }

    // ─── Livewire component rendering tests ─────────────────────────────

    #[Test]
    public function it_accordion_table_list_renders_with_new_actions(): void
    {
        $this->actingAsAdmin();

        $moduleId = 'accordion-migration-test-' . uniqid();
        $moduleType = 'accordion';

        $record = new \Modules\Accordion\Models\Accordion();
        $record->rel_id = $moduleId;
        $record->rel_type = $moduleType;
        $record->title = 'Migration Test Accordion';
        $record->content = 'Test content for migration verification.';
        $record->save();

        Livewire::test(\Modules\Accordion\Filament\AccordionTableList::class, [
            'rel_id' => $moduleId,
            'rel_type' => $moduleType,
        ])
            ->assertSee('Migration Test Accordion')
            ->assertSuccessful();

        $record->delete();
    }

    #[Test]
    public function it_tabs_table_list_renders_with_new_actions(): void
    {
        $this->actingAsAdmin();

        $moduleId = 'tabs-migration-test-' . uniqid();
        $moduleType = 'tabs';

        $record = new \Modules\Tabs\Models\Tab();
        $record->rel_id = $moduleId;
        $record->rel_type = $moduleType;
        $record->title = 'Migration Test Tab';
        $record->content = 'Test content for migration verification.';
        $record->save();

        Livewire::test(\Modules\Tabs\Filament\TabsTableList::class, [
            'rel_id' => $moduleId,
            'rel_type' => $moduleType,
        ])
            ->assertSee('Migration Test Tab')
            ->assertSuccessful();

        $record->delete();
    }

    #[Test]
    public function it_testimonials_table_list_renders_with_new_actions(): void
    {
        $this->actingAsAdmin();

        $moduleId = 'testimonials-migration-test-' . uniqid();
        $moduleType = 'testimonials';

        $record = new \Modules\Testimonials\Models\Testimonial();
        $record->rel_id = $moduleId;
        $record->rel_type = $moduleType;
        $record->name = 'Migration Test Testimonial';
        $record->content = 'Test content for migration verification.';
        $record->save();

        Livewire::test(\Modules\Testimonials\Filament\TestimonialsTableList::class, [
            'rel_id' => $moduleId,
            'rel_type' => $moduleType,
        ])
            ->assertSee('Migration Test Testimonial')
            ->assertSuccessful();

        $record->delete();
    }

    #[Test]
    public function it_faq_table_list_renders_with_new_actions(): void
    {
        $this->actingAsAdmin();

        $moduleId = 'faq-migration-test-' . uniqid();
        $moduleType = 'faq';

        if (!class_exists(\Modules\Faq\Models\FaqItem::class)) {
            $this->markTestSkipped('Faq module not installed');
        }

        $record = new \Modules\Faq\Models\FaqItem();
        $record->rel_id = $moduleId;
        $record->rel_type = $moduleType;
        $record->question = 'Migration Test FAQ?';
        $record->answer = 'Test answer for migration verification.';
        $record->save();

        Livewire::test(\Modules\Faq\Filament\FaqTableList::class, [
            'rel_id' => $moduleId,
            'rel_type' => $moduleType,
        ])
            ->assertSuccessful();

        $record->delete();
    }

    #[Test]
    public function it_slider_table_list_renders_with_new_actions(): void
    {
        $this->actingAsAdmin();

        $moduleId = 'slider-migration-test-' . uniqid();
        $moduleType = 'slider';

        if (!class_exists(\Modules\Slider\Models\Slide::class)) {
            $this->markTestSkipped('Slider module not installed');
        }

        $record = new \Modules\Slider\Models\Slide();
        $record->rel_id = $moduleId;
        $record->rel_type = $moduleType;
        $record->title = 'Migration Test Slide';
        $record->save();

        Livewire::test(\Modules\Slider\Filament\SliderTableList::class, [
            'rel_id' => $moduleId,
            'rel_type' => $moduleType,
        ])
            ->assertSuccessful();

        $record->delete();
    }

    #[Test]
    public function it_pictures_table_list_renders_with_new_actions(): void
    {
        $this->actingAsAdmin();

        $moduleId = 'pictures-migration-test-' . uniqid();
        $moduleType = 'pictures';

        if (!class_exists(\Modules\Pictures\Models\Picture::class)) {
            $this->markTestSkipped('Pictures module not installed');
        }

        $record = new \Modules\Pictures\Models\Picture();
        $record->rel_id = $moduleId;
        $record->rel_type = $moduleType;
        $record->title = 'Migration Test Picture';
        $record->save();

        Livewire::test(\Modules\Pictures\Filament\PicturesTableList::class, [
            'rel_id' => $moduleId,
            'rel_type' => $moduleType,
        ])
            ->assertSuccessful();

        $record->delete();
    }

    // ─── Table action tests (edit action with new classes) ──────────────

    #[Test]
    public function it_accordion_edit_action_works_with_new_classes(): void
    {
        $this->actingAsAdmin();

        $moduleId = 'accordion-edit-test-' . uniqid();
        $moduleType = 'accordion';

        $record = new \Modules\Accordion\Models\Accordion();
        $record->rel_id = $moduleId;
        $record->rel_type = $moduleType;
        $record->title = 'Edit Action Test';
        $record->content = 'Test content.';
        $record->save();

        Livewire::test(\Modules\Accordion\Filament\AccordionTableList::class, [
            'rel_id' => $moduleId,
            'rel_type' => $moduleType,
        ])
            ->assertSee('Edit Action Test')
            ->callTableAction('edit', $record)
            ->assertSee('Edit Action Test')
            ->callMountedTableAction()
            ->assertHasNoTableActionErrors();

        $record->delete();
    }

    #[Test]
    public function it_tabs_edit_action_works_with_new_classes(): void
    {
        $this->actingAsAdmin();

        $moduleId = 'tabs-edit-test-' . uniqid();
        $moduleType = 'tabs';

        $record = new \Modules\Tabs\Models\Tab();
        $record->rel_id = $moduleId;
        $record->rel_type = $moduleType;
        $record->title = 'Edit Action Test Tab';
        $record->content = 'Test content.';
        $record->save();

        Livewire::test(\Modules\Tabs\Filament\TabsTableList::class, [
            'rel_id' => $moduleId,
            'rel_type' => $moduleType,
        ])
            ->assertSee('Edit Action Test Tab')
            ->callTableAction('edit', $record)
            ->assertSee('Edit Action Test Tab')
            ->callMountedTableAction()
            ->assertHasNoTableActionErrors();

        $record->delete();
    }

    // ─── Custom ImportAction classes ────────────────────────────────────

    #[Test]
    public function it_mw_tables_import_action_extends_filament_actions_import_action(): void
    {
        $reflection = new \ReflectionClass(\MicroweberPackages\Filament\Tables\Actions\ImportAction::class);
        $parent = $reflection->getParentClass();

        $this->assertNotFalse($parent);
        $this->assertEquals(
            'Filament\Actions\ImportAction',
            $parent->getName(),
            'MicroweberPackages\Filament\Tables\Actions\ImportAction must extend Filament\Actions\ImportAction'
        );
    }

    #[Test]
    public function it_mw_actions_import_action_extends_filament_actions_import_action(): void
    {
        $reflection = new \ReflectionClass(\MicroweberPackages\Filament\Actions\ImportAction::class);
        $parent = $reflection->getParentClass();

        $this->assertNotFalse($parent);
        $this->assertEquals(
            'Filament\Actions\ImportAction',
            $parent->getName(),
            'MicroweberPackages\Filament\Actions\ImportAction must extend Filament\Actions\ImportAction'
        );
    }
}
