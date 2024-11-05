<?php

namespace Modules\Faq\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Faq\Microweber\FaqModule;
use Tests\TestCase;

class FaqModuleFrontendTest extends TestCase
{

    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'test-faq-id' . uniqid(),
            'faqs' => json_encode([
                [
                    'question' => 'What is this?',
                    'answer' => 'This is a test answer.',
                ],
            ]),
        ];
        $moduleId = $params['id'];
        $moduleType = 'faq';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        // Save options to the database
        foreach ($params as $key => $value) {
            ModuleOption::create([
                'option_group' => $params['id'],
                'module' => $moduleType,
                'option_key' => $key,
                'option_value' => $value,
            ]);
        }
        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $faqModule = new FaqModule($params);
        $viewData = $faqModule->getViewData();

        $viewOutput = $faqModule->render();

        $this->assertTrue(View::exists('modules.faq::templates.default'));
        $this->assertStringContainsString('What is this?', $viewOutput);
        $this->assertStringContainsString('This is a test answer.', $viewOutput);

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
