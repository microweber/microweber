<?php

namespace Modules\Pdf\Tests\Unit;

use Livewire\Livewire;
use MicroweberPackages\Option\Models\Option;
use Modules\Pdf\Filament\PdfModuleSettings;
use Tests\TestCase;

class PdfModuleSettingsTest extends TestCase
{
    // use RefreshDatabase;

    public function testPdfModuleSettingsForm()
    {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'pdf';

        Option::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'params' => [
                'id' => $moduleId,
                'type' => $moduleType
            ]
        ];


        // Test when options.data-pdf-source is set to 'file'
        $data = [
            'options.data-pdf-source' => 'file',
            'options.data-pdf-upload' => 'path/to/uploaded/file.pdf',
        ];

        $component = Livewire::test(PdfModuleSettings::class, $params)
            ->set($params)
            ->fillForm($data)
            ->assertSet('options.data-pdf-source', 'file')
            ->assertSet('options.data-pdf-upload', 'path/to/uploaded/file.pdf');


        $component->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'data-pdf-source', 'option_value' => 'file']);
        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'data-pdf-upload', 'option_value' => 'path/to/uploaded/file.pdf']);

        // Test when options.data-pdf-source is set to 'url'
        $data = [
            'options.data-pdf-url' => 'https://www.example.com/document.pdf',
        ];

        $component->fillForm($data)
            ->assertSet('options.data-pdf-url', 'https://www.example.com/document.pdf');
        $component->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'data-pdf-url', 'option_value' => 'https://www.example.com/document.pdf']);

        // Clean up
        Option::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
