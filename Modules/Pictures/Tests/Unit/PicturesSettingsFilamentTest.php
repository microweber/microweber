<?php

namespace Modules\Pictures\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Pictures\Filament\PicturesModuleSettings;
use Tests\TestCase;

class PicturesSettingsFilamentTest extends TestCase
{

    public function testPicturesModuleSettingsForm()
    {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'pictures';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'module' => $moduleType,
            'params' => [
                'id' => $moduleId,
            ]
        ];

        Livewire::test(PicturesModuleSettings::class,$params)
            ->set($params)
            ->assertFormFieldExists('options.data-use-from-post');

        $data = [
            'options.data-use-from-post' => 'y',

        ];

        Livewire::test(PicturesModuleSettings::class,$params)
            ->set($params)
            ->fillForm($data)
            ->assertFormSet([
                'options.data-use-from-post' => 'y',
            ])
            ->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'data-use-from-post', 'option_value' => 'y']);


        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
