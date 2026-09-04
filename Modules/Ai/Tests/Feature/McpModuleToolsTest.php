<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Attributes\Tools\AttributesListTool;
use Modules\Category\Tools\CategoryListTool;
use Modules\Comments\Tools\CommentsListTool;
use Modules\Company\Tools\CompanyListTool;
use Modules\ContentData\Tools\ContentDataTool;
use Modules\Country\Tools\CountryListTool;
use Modules\CustomFields\Tools\CustomFieldsListTool;
use Modules\CustomFields\Tools\CustomFieldValuesTool;
use Modules\Coupons\Tools\CouponsListTool;
use Modules\Currency\Tools\CurrencyRatesTool;
use Modules\Faq\Tools\FaqListTool;
use Modules\MailTemplate\Tools\MailTemplateListTool;
use Modules\Menu\Tools\MenuListTool;
use Modules\Offer\Tools\OfferListTool;
use Modules\Rating\Tools\RatingListTool;
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
    public function mailtemplate_and_company_return_valid_json(): void
    {
        $m = (new MailTemplateListTool())->__invoke(limit: 5);
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $m);
        $this->assertArrayHasKey('templates', json_decode($m, true));

        $c = (new CompanyListTool())->__invoke(limit: 5);
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $c);
        $this->assertArrayHasKey('companies', json_decode($c, true));
    }

    #[Test]
    public function rating_and_attributes_return_valid_json(): void
    {
        $r = (new RatingListTool())->__invoke(limit: 5);
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $r);
        $this->assertArrayHasKey('ratings', json_decode($r, true));

        $a = (new AttributesListTool())->__invoke(limit: 5);
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $a);
        $this->assertArrayHasKey('attributes', json_decode($a, true));
    }

    #[Test]
    public function custom_fields_list_returns_valid_json(): void
    {
        $out = (new CustomFieldsListTool())->__invoke(limit: 5);
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $out);
        $this->assertArrayHasKey('fields', json_decode($out, true));
    }

    #[Test]
    public function content_data_and_custom_field_values_connect_and_validate(): void
    {
        // content_data: needs a content id; returns the metadata store shape.
        $cd = (new ContentDataTool())->__invoke(rel_id: 1);
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $cd);
        $this->assertArrayHasKey('data', json_decode($cd, true));
        // missing rel_id is a clean error.
        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, (new ContentDataTool())->__invoke(rel_id: 0));

        // custom field values: connects a definition to its options.
        $cv = (new CustomFieldValuesTool())->__invoke(custom_field_id: 1);
        $this->assertStringNotContainsString(BaseTool::ERROR_OUTPUT_MARKER, $cv);
        $this->assertArrayHasKey('values', json_decode($cv, true));
        $this->assertStringContainsString(BaseTool::ERROR_OUTPUT_MARKER, (new CustomFieldValuesTool())->__invoke(custom_field_id: 0));
    }

    #[Test]
    public function module_tools_expose_expected_names(): void
    {
        $this->assertSame('custom_fields_list', (new CustomFieldsListTool())->getName());
        $this->assertSame('custom_field_values', (new CustomFieldValuesTool())->getName());
        $this->assertSame('content_data_get', (new ContentDataTool())->getName());
        $this->assertSame('rating_list', (new RatingListTool())->getName());
        $this->assertSame('attributes_list', (new AttributesListTool())->getName());
        $this->assertSame('mail_template_list', (new MailTemplateListTool())->getName());
        $this->assertSame('company_list', (new CompanyListTool())->getName());
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
