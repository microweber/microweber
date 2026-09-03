<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Category\Tools\CategoryListTool;
use Modules\Comments\Tools\CommentsListTool;
use Modules\Country\Tools\CountryListTool;
use Modules\Coupons\Tools\CouponsListTool;
use Modules\Currency\Tools\CurrencyRatesTool;
use Modules\Faq\Tools\FaqListTool;
use Modules\Menu\Tools\MenuListTool;
use Modules\Offer\Tools\OfferListTool;
use Modules\Slider\Tools\SliderListTool;
use Modules\Tag\Tools\TagListTool;
use Modules\Testimonials\Tools\TestimonialsListTool;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Functional smoke tests for module tools newly exposed over MCP. Each tool must
 * run without error and return well-formed JSON (not the error marker). Extend
 * this as more modules are wired via the MCP catalog.
 */
class McpModuleToolsTest extends TestCase
{
    #[Test]
    public function menu_list_returns_valid_json(): void
    {
        $out = (new MenuListTool())->__invoke();
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $out);
        $decoded = json_decode($out, true);
        $this->assertIsArray($decoded);
        $this->assertArrayHasKey('menus', $decoded);
    }

    #[Test]
    public function menu_list_can_filter_by_group(): void
    {
        $out = (new MenuListTool())->__invoke(menu: 'header_menu');
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $out);
        $this->assertIsArray(json_decode($out, true));
    }

    #[Test]
    public function category_list_returns_valid_json(): void
    {
        $out = (new CategoryListTool())->__invoke(limit: 5);
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $out);
        $decoded = json_decode($out, true);
        $this->assertIsArray($decoded);
        $this->assertArrayHasKey('categories', $decoded);
    }

    #[Test]
    public function comments_list_returns_valid_json(): void
    {
        $out = (new CommentsListTool())->__invoke(status: 'all', limit: 5);
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $out);
        $decoded = json_decode($out, true);
        $this->assertIsArray($decoded);
        $this->assertArrayHasKey('comments', $decoded);
    }

    #[Test]
    public function testimonials_list_returns_valid_json(): void
    {
        $out = (new TestimonialsListTool())->__invoke(limit: 5);
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $out);
        $decoded = json_decode($out, true);
        $this->assertIsArray($decoded);
        $this->assertArrayHasKey('testimonials', $decoded);
    }

    #[Test]
    public function faq_list_returns_valid_json(): void
    {
        $out = (new FaqListTool())->__invoke(limit: 5);
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $out);
        $decoded = json_decode($out, true);
        $this->assertIsArray($decoded);
        $this->assertArrayHasKey('faqs', $decoded);
    }

    #[Test]
    public function slider_list_returns_valid_json(): void
    {
        $out = (new SliderListTool())->__invoke(limit: 5);
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $out);
        $decoded = json_decode($out, true);
        $this->assertIsArray($decoded);
        $this->assertArrayHasKey('slides', $decoded);
    }

    #[Test]
    public function country_and_currency_return_valid_json(): void
    {
        $c = (new CountryListTool())->__invoke(limit: 5);
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $c);
        $this->assertArrayHasKey('countries', json_decode($c, true));

        $r = (new CurrencyRatesTool())->__invoke(limit: 5);
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $r);
        $this->assertArrayHasKey('rates', json_decode($r, true));
    }

    #[Test]
    public function coupons_list_returns_valid_json(): void
    {
        $out = (new CouponsListTool())->__invoke(limit: 5);
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $out);
        $this->assertArrayHasKey('coupons', json_decode($out, true));
    }

    #[Test]
    public function offer_and_tag_return_valid_json(): void
    {
        $o = (new OfferListTool())->__invoke(limit: 5);
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $o);
        $this->assertArrayHasKey('offers', json_decode($o, true));

        $t = (new TagListTool())->__invoke(limit: 5);
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $t);
        $this->assertArrayHasKey('tags', json_decode($t, true));
    }

    #[Test]
    public function module_tools_expose_expected_names(): void
    {
        $this->assertSame('menu_list', (new MenuListTool())->getName());
        $this->assertSame('category_list', (new CategoryListTool())->getName());
        $this->assertSame('comments_list', (new CommentsListTool())->getName());
        $this->assertSame('testimonials_list', (new TestimonialsListTool())->getName());
        $this->assertSame('faq_list', (new FaqListTool())->getName());
        $this->assertSame('slider_list', (new SliderListTool())->getName());
        $this->assertSame('country_list', (new CountryListTool())->getName());
        $this->assertSame('currency_rates', (new CurrencyRatesTool())->getName());
        $this->assertSame('coupons_list', (new CouponsListTool())->getName());
        $this->assertSame('offer_list', (new OfferListTool())->getName());
        $this->assertSame('tag_list', (new TagListTool())->getName());
    }
}
