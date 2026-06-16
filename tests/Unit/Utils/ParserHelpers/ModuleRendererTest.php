<?php

namespace Tests\Unit\Utils\ParserHelpers;

use MicroweberPackages\App\Utils\ParserHelpers\ModuleRenderer;
use PHPUnit\Framework\TestCase;

/**
 * Tests for ModuleRenderer.
 */
class ModuleRendererTest extends TestCase
{
    private ModuleRenderer $renderer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->renderer = new ModuleRenderer();
    }

    // ── FIXED BUG: Empty module produces empty output ──

    public function test_empty_module_produces_empty_output(): void
    {
        $result = $this->renderer->render('', '', [], '');
        $this->assertSame('', $result,
            'FIX: empty/type-less module produces empty string, not a placeholder');
    }

    // ── Basic rendering ──

    public function test_basic_module_rendering(): void
    {
        $result = $this->renderer->render(
            'btn',
            'module-btn-3',
            ['data-type' => 'btn'],
            '<button>Click me</button>'
        );

        $this->assertStringContainsString('class="module module-btn"', $result);
        $this->assertStringContainsString('id="module-btn-3"', $result);
        $this->assertStringContainsString('data-type="btn"', $result);
        $this->assertStringContainsString('<button>Click me</button>', $result);
        $this->assertStringStartsWith('<div ', $result);
        $this->assertStringEndsWith('</div>', $result);
    }

    // ── Custom HTML tag ──

    public function test_custom_html_tag(): void
    {
        $result = $this->renderer->render(
            'btn', 'module-btn-1', ['data-type' => 'btn'],
            'content', 'section'
        );

        $this->assertStringStartsWith('<section ', $result);
        $this->assertStringEndsWith('</section>', $result);
    }

    // ── No-wrap ──

    public function test_no_wrap_returns_content_only(): void
    {
        $content = '<button>Click me</button>';
        $result = $this->renderer->render(
            'btn', 'module-btn-1', ['data-type' => 'btn'],
            $content, 'div', true
        );

        $this->assertSame($content, $result);
    }

    public function test_is_no_wrap_detection(): void
    {
        $this->assertTrue($this->renderer->isNoWrap(['no_wrap' => true]));
        $this->assertTrue($this->renderer->isNoWrap(['data-no-wrap' => true]));
        $this->assertTrue($this->renderer->isNoWrap(['no-wrap' => true]));
        $this->assertFalse($this->renderer->isNoWrap(['type' => 'btn']));
    }

    // ── As element ──

    public function test_as_element_rendering(): void
    {
        $result = $this->renderer->render(
            'btn', 'module-btn-1', ['data-type' => 'btn'],
            'content', 'div', false, '', true
        );

        $this->assertStringContainsString('class="element btn"', $result);
        $this->assertStringNotContainsString('class="module', $result);
    }

    public function test_is_as_element_detection(): void
    {
        $this->assertTrue($this->renderer->isAsElement(['class' => 'module-as-element custom']));
        $this->assertFalse($this->renderer->isAsElement(['class' => 'module custom']));
        $this->assertFalse($this->renderer->isAsElement([]));
    }

    // ── User-defined class ──

    public function test_user_class_included(): void
    {
        $result = $this->renderer->render(
            'btn', 'module-btn-1', ['data-type' => 'btn'],
            'content', 'div', false, 'my-custom-class'
        );

        $this->assertStringContainsString('my-custom-class', $result);
    }

    // ── CSS class generation ──

    public function test_module_css_class(): void
    {
        $this->assertSame('module-btn', $this->renderer->moduleCssClass('btn'));
        $this->assertSame('module-layouts', $this->renderer->moduleCssClass('layouts'));
        $this->assertSame('module-text-multiple-columns', $this->renderer->moduleCssClass('text/multiple_columns'));
        $this->assertSame('module-layouts-titles-skin-1', $this->renderer->moduleCssClass('layouts/titles/skin-1'));
        $this->assertSame('module-my-module', $this->renderer->moduleCssClass('my module'));
    }

    // ── Attributes are properly escaped ──

    public function test_attributes_html_escaped(): void
    {
        $result = $this->renderer->render(
            'btn', 'module-btn-1',
            ['data-type' => 'btn', 'data-value' => 'a"b'],
            'content'
        );

        $this->assertStringContainsString('data-value="a&quot;b"', $result);
    }

    // ── ID and class are not duplicated in attributes ──

    public function test_id_and_class_not_duplicated(): void
    {
        $result = $this->renderer->render(
            'btn', 'module-btn-1',
            ['class' => 'should-not-appear-twice', 'id' => 'should-not-appear', 'data-type' => 'btn'],
            'content'
        );

        // class and id attrs should be handled by the wrapper, not duplicated
        $this->assertSame(1, substr_count($result, 'id='));
        $this->assertSame(1, substr_count($result, 'class='));
    }
}
