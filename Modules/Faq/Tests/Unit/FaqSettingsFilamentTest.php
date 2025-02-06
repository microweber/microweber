<?php

namespace Modules\Faq\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Faq\Models\Faq;
use Modules\Faq\Filament\Resources\FaqModuleResource;
use Tests\TestCase;

class FaqSettingsFilamentTest extends TestCase
{


    public function testFaqModuleResourceForm()
    {
        Faq::where('rel_type', 'some_rel_for_faq')->delete();

        // Test creating a FAQ
        $data = [
            'question' => 'Test Question',
            'answer' => 'Test Answer',
            'position' => 1,
            'is_active' => true,
            'rel_type' => 'some_rel_for_faq',
            'rel_id' => 1
        ];

        $faq = Faq::create($data);

        $this->assertDatabaseHas('faqs', $data);

        // Test updating a FAQ
        $updatedData = [
            'question' => 'Updated Question',
            'answer' => 'Updated Answer',
            'position' => 2,
            'is_active' => false,
            'rel_id' => 2
        ];

        $faq->update($updatedData);

        $this->assertDatabaseHas('faqs', $updatedData);

        // Test deleting a FAQ
        $faq->delete();

        $this->assertDatabaseMissing('faqs', $updatedData);
    }

    public function testFaqModuleResourceRelations()
    {
        //cleanup
        Faq::where('rel_type', 'some_rel_for_faq')->delete();


        // Create FAQs with different relations
        $generalFaq = Faq::create([
            'question' => 'General FAQ',
            'answer' => 'General answer',
            'position' => 0,
            'is_active' => true
        ]);

        $productFaq = Faq::create([
            'question' => 'Product FAQ',
            'answer' => 'Product answer',
            'position' => 1,
            'is_active' => true,
            'rel_type' => 'some_rel_for_faq',
            'rel_id' => 1
        ]);

        // Test querying by relation
        $this->assertEquals(1, Faq::where('rel_type', 'some_rel_for_faq')->count());
        $this->assertEquals(1, Faq::where('rel_id', 1)->count());

        // Test position ordering
        $this->assertEquals('General FAQ', Faq::orderBy('position')->first()->question);
        $this->assertEquals('Product FAQ', Faq::orderBy('position', 'desc')->first()->question);
    }
}
