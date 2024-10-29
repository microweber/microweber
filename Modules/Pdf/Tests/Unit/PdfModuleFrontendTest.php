<?php

namespace Modules\Pdf\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Pdf\Microweber\PdfModule;
use Tests\TestCase;

class PdfModuleFrontendTest extends TestCase
{
    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'test-pdf-id' . uniqid(),
            'data-pdf-source' => 'url',
            'data-pdf-url' => 'https://www.example.com/document.pdf',
        ];
        $moduleId = $params['id'];
        $moduleType = 'pdf';

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

        $pdfModule = new PdfModule($params);
        $viewData = $pdfModule->getViewData();

        $viewOutput = $pdfModule->render();

        $this->assertTrue(View::exists('modules.pdf::templates.default'));
        $this->assertStringContainsString('document.pdf', $viewOutput);
        $this->assertStringContainsString('pdf', $viewOutput);

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
