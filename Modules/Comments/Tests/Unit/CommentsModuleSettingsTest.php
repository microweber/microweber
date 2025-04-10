<?php

namespace Modules\Comments\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use MicroweberPackages\Core\tests\TestCase;
use Modules\Comments\Filament\CommentsModuleSettings;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;

class CommentsModuleSettingsTest extends TestCase
{
    #[Test]
    public function testFormSchema()
    {
        $settings = new CommentsModuleSettings();
        $livewire = new class extends \Livewire\Component implements \Filament\Forms\Contracts\HasForms {
            use \Filament\Forms\Concerns\InteractsWithForms;
        };
        $form = $settings->form(Form::make($livewire));
        
        // Verify form contains required fields
        // Verify all required fields exist
        $this->assertNotNull(
            $form->getComponent('options.require_approval'),
            'Missing require_approval field'
        );
        $this->assertNotNull(
            $form->getComponent('options.notify_authors'), 
            'Missing notify_authors field'
        );
        $this->assertNotNull(
            $form->getComponent('options.show_user_avatar'),
            'Missing show_user_avatar field'
        );
        $this->assertNotNull(
            $form->getComponent('options.comments_per_page'),
            'Missing comments_per_page field'
        );
    }

    #[Test]
    public function testDefaultSettings()
    {
        $settings = new CommentsModuleSettings();
        $livewire = new class extends \Livewire\Component implements \Filament\Forms\Contracts\HasForms {
            use \Filament\Forms\Concerns\InteractsWithForms;
        };
        $form = $settings->form(Form::make($livewire));
        
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