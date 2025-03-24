<?php

namespace Modules\Faq\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use Modules\Faq\Models\Faq;
use Modules\Faq\Microweber\FaqModule;
use Tests\TestCase;

class FaqModuleFrontendTest extends TestCase
{
    use RefreshDatabase;

    public function testDefaultViewRendering()
    {
        // Create a test FAQ in the database
     $id = 'test-faq-id-' . uniqid();

        $params = [
            'id' =>$id ,
        ];
        $faq = Faq::create([
            'question' => 'What is this?',
            'answer' => 'This is a test answer.',
            'position' => 0,
            'rel_type' => 'module',
            'rel_id' => $id,
            'is_active' => true
        ]);
        $faqModule = new FaqModule($params);
        $viewOutput = $faqModule->render();

        $this->assertTrue(View::exists('modules.faq::templates.default'));
        $this->assertStringContainsString('What is this?', $viewOutput);
        $this->assertStringContainsString('This is a test answer.', $viewOutput);
    }

    public function testRelationBasedViewRendering()
    {
        $id = 'test-faq-id-' . uniqid();
        // Create FAQs with different relations
        $generalFaq = Faq::create([
            'question' => 'General FAQ',
            'answer' => 'General answer',
            'position' => 0,
            'rel_id' => 'testRelationBasedViewRendering',
            'rel_type' => 'module',
            'is_active' => true
        ]);

        $productFaq = Faq::create([
            'question' => 'Product FAQ',
            'answer' => 'Product answer',
            'position' => 0,
            'is_active' => true,
            'rel_type' => 'module',
            'rel_id' => $id
        ]);

        // Test general FAQs
        $params = ['id' => 'testRelationBasedViewRendering'];
        $faqModule = new FaqModule($params);
        $viewOutput = $faqModule->render();

        $this->assertStringContainsString('General FAQ', $viewOutput);
        $this->assertStringNotContainsString('Product FAQ', $viewOutput);

        // Test product-specific FAQs
        $params = [
            'id' => 'test-faq-id-' . uniqid(),
            'rel_type' => 'module',
            'rel_id' => $id
        ];
        $faqModule = new FaqModule($params);
        $viewOutput = $faqModule->render();

        $this->assertStringContainsString('Product FAQ', $viewOutput);
        $this->assertStringNotContainsString('General FAQ', $viewOutput);
    }
}
