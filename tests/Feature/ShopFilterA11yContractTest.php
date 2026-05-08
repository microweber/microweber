<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-74 / AI-67 / TICKET-ZZ — Shop filter a11y regression coverage.
 *
 * Pins:
 *   - Categories filter buttons are real <button type="button"> with
 *     wire:click + aria-current on the active row (was <span wire:click>
 *     which keyboard users couldn't activate).
 *   - Tag-button is a btn-group of two real <button>s, NOT a <button>
 *     wrapping <span wire:click> (the prior shape captured keyboard
 *     activation on the parent button — no-op — instead of the inner
 *     span). The remove-tag button carries an aria-label.
 *   - Custom-fields filter wraps each grouped checkbox set in
 *     <fieldset><legend> so AT users hear the field name when
 *     navigating the checkboxes.
 *   - Every filter section has a real <h3> heading (was bare <div>
 *     so screen-reader heading-nav couldn't reach it).
 *   - Offers <select> is programmatically labelled (was an empty
 *     <label> wrapping only the text).
 *
 * Style after the cycle-52..73 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class ShopFilterA11yContractTest extends TestCase
{
    private string $catIndex;
    private string $catChild;
    private string $tagIndex;
    private string $tagButton;
    private string $customFields;
    private string $priceRange;
    private string $offers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->catIndex = file_get_contents(base_path(
            'Modules/Shop/resources/views/livewire/shop/filters/categories/index.blade.php'
        ));
        $this->catChild = file_get_contents(base_path(
            'Modules/Shop/resources/views/livewire/shop/filters/categories/category-child.blade.php'
        ));
        $this->tagIndex = file_get_contents(base_path(
            'Modules/Shop/resources/views/livewire/shop/filters/tags/index.blade.php'
        ));
        $this->tagButton = file_get_contents(base_path(
            'Modules/Shop/resources/views/livewire/shop/filters/tags/tag-button.blade.php'
        ));
        $this->customFields = file_get_contents(base_path(
            'Modules/Shop/resources/views/livewire/shop/filters/custom_fields/index.blade.php'
        ));
        $this->priceRange = file_get_contents(base_path(
            'Modules/Shop/resources/views/livewire/shop/filters/price_range/index.blade.php'
        ));
        $this->offers = file_get_contents(base_path(
            'Modules/Shop/resources/views/livewire/shop/filters/offers/index.blade.php'
        ));
    }

    #[Test]
    public function categories_filter_uses_real_buttons_not_clickable_spans(): void
    {
        // Negative: the original `<span ... wire:click>` shape is gone.
        $this->assertDoesNotMatchRegularExpression(
            '/<span\\s+[^>]*wire:click="filter(Clear)?Category/',
            $this->catIndex,
            'categories index: must not use <span wire:click> for the category buttons (keyboard-inaccessible)'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/<span\\s+[^>]*wire:click="filterCategory/',
            $this->catChild,
            'categories child: must not use <span wire:click>'
        );

        // Positive: real <button type="button"> with wire:click.
        // Multi-line declarations break `[^>]*` regexes — use plain
        // substring checks for the wire:click and verify the file
        // contains a `<button` for each occurrence.
        $this->assertStringContainsString(
            'wire:click="filterClearCategory()"',
            $this->catIndex,
            'categories index: All Categories must carry wire:click="filterClearCategory()"'
        );
        $this->assertStringContainsString(
            '<button',
            $this->catIndex,
            'categories index: must contain a <button> element'
        );
        $this->assertStringContainsString(
            'wire:click="filterCategory(',
            $this->catChild,
            'categories child: each category must carry wire:click="filterCategory(...)"'
        );
        $this->assertStringContainsString(
            '<button',
            $this->catChild,
            'categories child: must contain a <button> element'
        );

        // aria-current mirrors the visual `.active` class so AT
        // users hear the current category.
        $this->assertStringContainsString(
            'aria-current="true"',
            $this->catIndex,
            'categories index: All Categories must carry aria-current="true" when no category is filtered'
        );
        $this->assertStringContainsString(
            'aria-current="true"',
            $this->catChild,
            'categories child: active category must carry aria-current="true"'
        );
    }

    #[Test]
    public function tag_button_is_two_real_buttons_not_button_wrapping_span(): void
    {
        // The prior shape was `<button>...<span wire:click>...</span></button>`
        // — keyboard activation fired the parent button (no handler,
        // no-op) instead of the inner span's click. Pin the new shape
        // is two REAL <button>s in a btn-group.
        // The prior shape was `<button ...><span wire:click="filterTag(`
        // — pin via plain string negation since the multi-line markup
        // makes regex matching brittle.
        $this->assertStringNotContainsString(
            '<span wire:click="filterTag(',
            $this->tagButton,
            'tag-button: must not use <span wire:click="filterTag(...)"> (keyboard-inaccessible)'
        );

        // Two real <button>s. Multi-line button declarations make
        // `[^>]*` regexes brittle; use plain substring checks instead.
        $this->assertStringContainsString(
            'wire:click="filterTag(\'{{ $tagSlug }}\')"',
            $this->tagButton,
            'tag-button: tag-toggle must carry wire:click="filterTag(...)"'
        );
        $this->assertStringContainsString(
            'wire:click="filterRemoveTag(\'{{ $tagSlug }}\')"',
            $this->tagButton,
            'tag-button: remove-tag must carry wire:click="filterRemoveTag(...)"'
        );
        // Both must be real <button type="button"> elements. Strip
        // the Blade `{{-- ... --}}` doc-comment first so any
        // `<button>` references inside it don't inflate the count.
        $strippedTagButton = preg_replace('/\{\{--.*?--\}\}/s', '', $this->tagButton);
        $this->assertSame(
            2,
            substr_count($strippedTagButton, '<button type="button"'),
            'tag-button: must contain exactly 2 <button type="button"> elements (toggle + remove)'
        );

        // Remove-tag carries an aria-label so screen readers announce
        // the affordance ("Remove tag Sale") instead of the bare X.
        $this->assertStringContainsString(
            "aria-label=\"{{ __('Remove tag :name'",
            $this->tagButton,
            'tag-button: remove button must carry aria-label translated via :name placeholder'
        );

        // The visible `×` glyph is aria-hidden so the SR doesn't
        // announce "times" alongside the aria-label.
        $this->assertStringContainsString(
            'aria-hidden="true"',
            $this->tagButton,
            'tag-button: × glyph must carry aria-hidden="true"'
        );
    }

    #[Test]
    public function custom_fields_filter_uses_fieldset_and_legend(): void
    {
        // Each custom-field group MUST be wrapped in a <fieldset> with
        // a <legend> for screen-reader group announcement.
        $this->assertStringContainsString(
            '<fieldset',
            $this->customFields,
            'custom_fields index: each grouped checkbox set must be wrapped in <fieldset>'
        );
        $this->assertStringContainsString(
            '<legend',
            $this->customFields,
            'custom_fields index: each fieldset must carry a <legend>'
        );
        // Pin the legend renders the field name so AT hears "Color,
        // group: Red / Blue" (not just "group: Red / Blue").
        $this->assertStringContainsString(
            '{{ $customField->name }}',
            $this->customFields,
            'custom_fields index: legend must render $customField->name'
        );
    }

    #[Test]
    public function every_filter_section_has_h3_heading_for_screen_reader_nav(): void
    {
        $sections = [
            'categories' => $this->catIndex,
            'tags' => $this->tagIndex,
            'custom_fields' => $this->customFields,
            'price_range' => $this->priceRange,
            'offers' => $this->offers,
        ];
        foreach ($sections as $name => $src) {
            $this->assertMatchesRegularExpression(
                '/<h3[^>]*class="mw-shop-filter-heading/',
                $src,
                "Shop {$name} filter: must have an <h3 class=\"mw-shop-filter-heading\"> for AT heading-nav"
            );
        }
    }

    #[Test]
    public function offers_select_is_programmatically_labelled(): void
    {
        // The original empty <label>Discount</label> wrapped only
        // text, not the <select> — AT announced "combobox" with no
        // label. New shape uses a stable id + visually-hidden <label
        // for=...> so the visual h3 stays AND the select gets a
        // programmatic label.
        $this->assertStringContainsString(
            '<label for="{{ $offersSelectId }}"',
            $this->offers,
            'offers index: <label for="..."> must reference the stable select id'
        );
        $this->assertStringContainsString(
            'class="visually-hidden"',
            $this->offers,
            'offers index: the for= label is visually-hidden so the visual heading stays as h3'
        );
        $this->assertStringContainsString(
            'id="{{ $offersSelectId }}"',
            $this->offers,
            'offers index: the <select> must carry the id the label references'
        );
    }
}
