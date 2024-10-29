<?php

namespace Modules\HighlightCode\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\HighlightCode\Microweber\HighlightCodeModule;
use Tests\TestCase;

class HighlightCodeModuleFrontendTest extends TestCase
{
    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'test-highlight-code-id' . uniqid(),
            'text' => '<?php echo "Hello World"; ?>',
        ];
        $moduleId = $params['id'];
        $moduleType = 'highlight_code';

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

        $highlightCodeModule = new HighlightCodeModule($params);
        $viewData = $highlightCodeModule->getViewData();

        $viewOutput = $highlightCodeModule->render();

        $this->assertTrue(View::exists('modules.highlight_code::templates.default'));
        $this->assertStringContainsString('Hello World', $viewOutput);
        $this->assertStringContainsString('highlight_code_module', $viewOutput);

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
