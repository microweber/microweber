<?php

namespace Modules\Comments\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use MicroweberPackages\Core\tests\TestCase;
use Modules\Comments\Filament\CommentsModuleSettings;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Toggle;

class CommentsModuleSettingsTest extends TestCase
{
    #[Test]
    public function testFormSchema(): void
    {
        $settings = new CommentsModuleSettings();
        $schema = $settings->form(Schema::make());

        // Verify schema was returned and contains components
        $this->assertInstanceOf(Schema::class, $schema);
        
        $components = $schema->getComponents();
        $this->assertNotEmpty($components);
        
        // Verify the first component is a Section
        $this->assertInstanceOf(Section::class, $components[0]);
    }

    #[Test]
    public function testDefaultSettings(): void
    {
        $settings = new CommentsModuleSettings();
        $schema = $settings->form(Schema::make());

        // Verify schema was returned
        $this->assertInstanceOf(Schema::class, $schema);
        
        // Verify default values
        $this->assertTrue($settings->getSetting('require_approval'));
        $this->assertTrue($settings->getSetting('notify_authors'));
    }

    #[Test]
    public function testSettingsSaving()
    {
        $settings = new CommentsModuleSettings();
        
        // Test saving settings
        $settings->setSettings([
            'require_approval' => false,
            'notify_authors' => false
        ]);
        
        // Verify settings were persisted in database
        $this->assertEquals(
            false,
            get_option('require_approval', $settings->module),
            'require_approval setting not saved to db'
        );
        $this->assertEquals(
            false, 
            get_option('notify_authors', $settings->module),
            'notify_authors setting not saved to db'
        );
    }
}