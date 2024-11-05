<?php

namespace Modules\Faq\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Faq\Filament\FaqModuleSettings;
use Tests\TestCase;

class FaqSettingsFilamentTest extends TestCase
{

    public function testFaqModuleSettingsForm()
    {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'faq';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'params' => [
                'id' => $moduleId,
                'type' => $moduleType
            ]
        ];

        Livewire::test(FaqModuleSettings::class)
            ->set($params)
            ->assertFormFieldExists('options.title');

        $data = [
            'options.title' => 'Custom FAQ Title',
            'faqs' => [
                [
                    'question' => 'What is this?',
                    'answer' => 'This is a test answer.',
                ],
            ],
        ];

        Livewire::test(FaqModuleSettings::class)
            ->set($params)
            ->fillForm($data)
            ->assertFormSet($data)
            ->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('options', [
            'option_group' => $moduleId,
            'module' => $moduleType,
            'option_key' => 'title',
            'option_value' => 'Custom FAQ Title'
        ]);
        $this->assertDatabaseHas('options', [
            'option_group' => $moduleId,
            'module' => $moduleType,
            'option_key' => 'faqs',
            'option_value' => json_encode($data['faqs'])
        ]);

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
