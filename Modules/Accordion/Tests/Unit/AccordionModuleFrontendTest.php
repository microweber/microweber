<?php

namespace Modules\Accordion\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Modules\Accordion\Microweber\AccordionModule;
use Modules\Accordion\Models\Accordion;
use Tests\TestCase;

class AccordionModuleFrontendTest extends TestCase
{
    #[Test]
    public function it_default_view_rendering(): void
    {
        $params = [
            'id' => 'test-rel-id',
            'module' => 'accordion',
        ];

        $accordionItem = Accordion::create([
            'title' => 'Test Accordion Item',
            'content' => 'This is a test content for the accordion.',
            'rel_id' => 'test-rel-id',
            'rel_type' => 'module',
        ]);

        $module = new AccordionModule($params);
        $viewData = $module->getViewData();

        $viewOutput = $module->render();

        $this->assertStringContainsString($accordionItem->title, $viewOutput);

        // cleanup
        $accordionItem->delete();
    }

    #[Test]
    public function it_prefixes_header_and_collapse_ids_with_the_module_instance_for_every_collapse_skin(): void
    {
        $templates = ['default', 'skin-1', 'skin-3', 'skin-4'];

        foreach ($templates as $template) {
            $moduleId = 'accordion-module-' . $template;
            $slideId = 5;

            $accordionItem = Accordion::create([
                'title' => 'Accordion Item ' . $template,
                'content' => 'Body ' . $template,
                'rel_id' => $moduleId,
                'rel_type' => 'module',
                'position' => 1,
            ]);

            $accordionItem->id = $slideId;
            $accordionItem->save();

            $module = new AccordionModule([
                'id' => $moduleId,
                'rel_id' => $moduleId,
                'rel_type' => 'module',
                'module' => 'accordion',
                'template' => $template,
            ]);

            $viewOutput = (string) $module->render();

            $expectedHeaderId = 'header-item-' . $moduleId . '-' . $slideId;
            $expectedCollapseId = 'collapse-accordion-item-' . $moduleId . '-' . $slideId . '-0';

            $this->assertStringContainsString($expectedHeaderId, $viewOutput, 'Missing prefixed header id for template ' . $template);
            $this->assertStringContainsString($expectedCollapseId, $viewOutput, 'Missing prefixed collapse id for template ' . $template);
            $this->assertStringNotContainsString(
                'id="header-item-' . $slideId . '"',
                $viewOutput,
                'Found unprefixed header id for template ' . $template
            );
            $this->assertStringNotContainsString(
                'id="collapse-accordion-item-' . $slideId . '-0"',
                $viewOutput,
                'Found unprefixed collapse id for template ' . $template
            );
            $this->assertStringContainsString(
                'aria-labelledby="' . $expectedHeaderId . '"',
                $viewOutput,
                'Missing prefixed aria-labelledby for template ' . $template
            );
            $this->assertStringContainsString(
                'aria-controls="' . $expectedCollapseId . '"',
                $viewOutput,
                'Missing prefixed aria-controls for template ' . $template
            );

            $accordionItem->delete();
        }
    }

}
