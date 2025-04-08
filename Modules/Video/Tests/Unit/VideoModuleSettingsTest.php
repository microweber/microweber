<?php

namespace Modules\Video\Tests\Unit;

use Tests\TestCase;
use Modules\Video\Filament\VideoModuleSettings;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class VideoModuleSettingsTest extends TestCase
{
    public function test_settings_form_structure()
    {
        $settings = new VideoModuleSettings();
        $form = $settings->form(Form::make());
        
        $this->assertTrue($form->getComponent('options.url') instanceof TextInput);
        $this->assertTrue($form->getComponent('options.autoplay') instanceof Toggle);
    }

    public function test_settings_validation()
    {
        $settings = new VideoModuleSettings();
        $form = $settings->form(Form::make());
        
        $urlField = $form->getComponent('options.url');
        $this->assertTrue($urlField->isRequired());
    }

    public function test_settings_default_values()
    {
        $settings = new VideoModuleSettings();
        $form = $settings->form(Form::make());
        
        $this->assertEquals(
            'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            $form->getComponent('options.url')->getDefaultState()
        );
        $this->assertFalse(
            $form->getComponent('options.autoplay')->getDefaultState()
        );
    }
}