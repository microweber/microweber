<?php

namespace Modules\HighlightCode\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\HighlightCode\Filament\HighlightCodeModuleSettings;
use Tests\TestCase;

class HighlightCodeModuleSettingsFilamentTest extends TestCase
{
    public function testHighlightCodeModuleSettingsForm()
    {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'highlight_code';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'params' => [
                'id' => $moduleId,
                'type' => $moduleType
            ]
        ];

        Livewire::test(HighlightCodeModuleSettings::class)
            ->set($params)
            ->assertFormFieldExists('options.text');

        $data = [
            'options.text' => '<?php echo "Hello World"; ?>',
        ];

        Livewire::test(HighlightCodeModuleSettings::class)
            ->set($params)
            ->fillForm($data)
            ->assertFormSet([
                'options.text' => '<?php echo "Hello World"; ?>',
            ])
            ->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'text', 'option_value' => '<?php echo "Hello World"; ?>']);

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
