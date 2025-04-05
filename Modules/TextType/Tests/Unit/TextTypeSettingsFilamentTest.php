<?php

namespace Modules\TextType\Tests\Unit;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Modules\TextType\Filament\TextTypeModuleSettings;
use Tests\TestCase;

class TextTypeSettingsFilamentTest extends TestCase
{
    public function testFormSchema()
    {
        $settings = new TextTypeModuleSettings();
dd($settings->get());
        $form = $settings->form(new Form($settings));

        $this->assertCount(3, $form->getSchema());

        $this->assertInstanceOf(TextInput::class, $form->getSchema()[0]);
        $this->assertEquals('options.text', $form->getSchema()[0]->getName());
        $this->assertEquals('Text', $form->getSchema()[0]->getLabel());

        $this->assertInstanceOf(TextInput::class, $form->getSchema()[1]);
        $this->assertEquals('options.fontSize', $form->getSchema()[1]->getName());
        $this->assertEquals('Font Size', $form->getSchema()[1]->getLabel());

        $this->assertInstanceOf(TextInput::class, $form->getSchema()[2]);
        $this->assertEquals('options.animationSpeed', $form->getSchema()[2]->getName());
        $this->assertEquals('Animation Speed', $form->getSchema()[2]->getLabel());
    }
}
