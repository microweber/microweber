<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Modules\Content\Models\Content;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-08-cfslug — shop "Filter by attributes" never matched any
 * multi-word custom field.
 *
 * `CustomFieldsTrait::scopeWhereCustomField()` looked the field up with
 * `where('name_key', Str::slug($fieldName, '-'))` — a HYPHEN separator. But
 * custom-field name_keys are stored with UNDERSCORES (e.g. "Session Focus"
 * → "session_focus"). So `Str::slug('session_focus', '-')` produced
 * "session-focus", which matched no row, and the shop attribute filter
 * returned "No products found" for every multi-word attribute (single-word
 * ones like "color" happened to work because slug left them unchanged).
 *
 * Fix: slug the filter field with '_' so it aligns with the stored name_key.
 *
 * Verified live: /shop?customFields[session_focus][0]=Frontend returned the
 * "Pair Programming Hour" product (was "No products found").
 */
class CustomFieldsCfslugShopAttributeFilterContractTest extends TestCase
{
    private const TRAIT = 'Modules/CustomFields/Traits/CustomFieldsTrait.php';

    #[Test]
    public function where_custom_field_scope_slugs_name_key_with_underscore(): void
    {
        $src = (string) file_get_contents(base_path(self::TRAIT));

        $this->assertMatchesRegularExpression(
            "/->where\(\s*'name_key'\s*,\s*Str::slug\(\\\$fieldName\s*,\s*'_'\s*\)\s*\)/",
            $src,
            "scopeWhereCustomField must slug the field name with '_' to match the stored name_key separator."
        );
        $this->assertDoesNotMatchRegularExpression(
            "/->where\(\s*'name_key'\s*,\s*Str::slug\(\\\$fieldName\s*,\s*'-'\s*\)\s*\)/",
            $src,
            "scopeWhereCustomField must NOT slug with '-' — that produced 'session-focus' and matched nothing."
        );
    }

    #[Test]
    public function multi_word_attribute_filter_matches_a_product_that_offers_the_value(): void
    {
        $token = 'cfslug' . substr(md5((string) microtime(true)), 0, 8);
        $nameKey = $token . '_focus'; // multi-word: underscore in the name_key
        $morph = (new Content())->getMorphClass();

        $product = Product::create([
            'title' => $token . ' Filter Product',
            'content_type' => 'product',
            'subtype' => 'product',
            'url' => $token . '-filter-product',
            'is_active' => 1,
            'parent' => 0,
        ]);

        $fieldId = DB::table('custom_fields')->insertGetId([
            'rel_type' => $morph,
            'rel_id' => $product->id,
            'type' => 'dropdown',
            'name' => ucfirst($token) . ' Focus',
            'name_key' => $nameKey,
            'position' => 1,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('custom_fields_values')->insert([
            'custom_field_id' => $fieldId,
            'value' => 'Frontend',
            'position' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        try {
            // The fixed scope (underscore slug) must MATCH the product.
            $match = Product::where('is_active', 1)
                ->whereCustomField([$nameKey => ['Frontend']])
                ->pluck('id')
                ->all();
            $this->assertContains(
                $product->id,
                $match,
                'Filtering by a multi-word attribute value must return the product that offers it.'
            );

            // A value the product does NOT offer must not match it.
            $miss = Product::where('is_active', 1)
                ->whereCustomField([$nameKey => ['Backend']])
                ->pluck('id')
                ->all();
            $this->assertNotContains(
                $product->id,
                $miss,
                'Filtering by a value the product does not offer must exclude it (selectivity).'
            );
        } finally {
            DB::table('custom_fields_values')->where('custom_field_id', $fieldId)->delete();
            DB::table('custom_fields')->where('id', $fieldId)->delete();
            Content::whereIn('id', [$product->id])->forceDelete();
        }
    }
}
