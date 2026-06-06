<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-06-pglayoutmodal — two defects in the Live Edit "Create page"
 * compact modal, both browser-reproduced while building a site through Live Edit:
 *
 *  (1) Selecting a Layout closed the whole modal. The Template/Layout selects
 *      were ->reactive(); inside a Filament mounted-action modal the reactive
 *      commit re-renders the action-modals.0 partial, and morphing it
 *      re-initialises the modal's Alpine x-data so isOpen resets to false and
 *      the modal vanishes. A runaway preview cascade (mw.templatePreview.rend →
 *      /api/module/layout-preview loop) compounded it. Fix: the compact modal
 *      passes withPreview:false + reactive:false to MwSelectTemplateForPage, so
 *      picking a layout triggers no round-trip and the modal survives;
 *      is_shop/subtype are derived from the chosen layout at SAVE time instead.
 *
 *  (2) Creating a page with a designed layout (services/pricing/landing/about…)
 *      rendered a blank "Click here to start writing…" placeholder instead of
 *      the layout's content, because the NOVICE #11 backfill wrote the
 *      placeholder into the `content` field that the layout's editable region
 *      pulls from. Fix: skip the placeholder backfill when a non-clean
 *      layout_file is present.
 */
class LiveEditPgLayoutModalContractTest extends TestCase
{
    private string $selectSrc;
    private string $contentResourceSrc;
    private string $livePageSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->selectSrc = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Filament/Forms/Components/MwSelectTemplateForPage.php'
        ));
        $this->contentResourceSrc = (string) file_get_contents(base_path(
            'Modules/Content/Filament/Admin/ContentResource.php'
        ));
        $this->livePageSrc = (string) file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php'
        ));
    }

    #[Test]
    public function make_accepts_with_preview_and_reactive_flags(): void
    {
        $this->assertMatchesRegularExpression(
            '/bool\s+\$withPreview\s*=\s*true/',
            $this->selectSrc,
            'MwSelectTemplateForPage::make must accept a $withPreview flag (default true).'
        );
        $this->assertMatchesRegularExpression(
            '/bool\s+\$reactive\s*=\s*true/',
            $this->selectSrc,
            'MwSelectTemplateForPage::make must accept a $reactive flag (default true).'
        );
    }

    #[Test]
    public function selects_are_live_only_when_reactive_is_true(): void
    {
        // Both selects must gate liveness behind the flag, not call ->reactive()
        // unconditionally (which would re-introduce the modal-closing commit).
        $this->assertSame(
            2,
            substr_count($this->selectSrc, '->live(condition: $reactive)'),
            'Both the Template and Layout selects must use ->live(condition: $reactive).'
        );
        $stripped = preg_replace('~//[^\n]*~', '', $this->selectSrc);
        $stripped = preg_replace('~/\*[\s\S]*?\*/~', '', (string) $stripped);
        $this->assertStringNotContainsString(
            '->reactive()',
            (string) $stripped,
            'No select may call the unconditional ->reactive() any more.'
        );
    }

    #[Test]
    public function preview_block_is_conditional_on_with_preview(): void
    {
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*\$withPreview\s*\)\s*\{\s*\$schema\[\]\s*=\s*\$templatePreviewBlock/',
            $this->selectSrc,
            'The preview iframe block must only be appended when $withPreview is true.'
        );
    }

    #[Test]
    public function compact_live_edit_modal_opts_out_of_preview_and_reactivity(): void
    {
        $this->assertMatchesRegularExpression(
            '/MwSelectTemplateForPage::make\([^)]*withPreview:\s*false[^)]*reactive:\s*false[^)]*\)/s',
            $this->contentResourceSrc,
            'The compact live-edit page form must build MwSelectTemplateForPage with withPreview:false + reactive:false.'
        );
    }

    #[Test]
    public function is_shop_and_subtype_are_derived_from_layout_at_save_time(): void
    {
        // Because the compact selects are no longer reactive, the action's
        // save closure must derive is_shop/subtype from the chosen layout.
        $this->assertStringContainsString('get_layout_details', $this->livePageSrc,
            'The save closure must look up layout details to derive is_shop/subtype.');
        $this->assertMatchesRegularExpression(
            "/\\\$data\\['subtype'\\]\s*=\s*\\\$layoutDetails\\['content_type'\\]/",
            $this->livePageSrc,
            'subtype must be set from the layout content_type at save time.'
        );
        $this->assertMatchesRegularExpression(
            "/\\\$data\\['is_shop'\\]\s*=/",
            $this->livePageSrc,
            'is_shop must be set from the layout at save time.'
        );
    }

    #[Test]
    public function placeholder_backfill_is_skipped_for_designed_layouts(): void
    {
        // A page with a non-clean layout must NOT get the novice placeholder
        // written into its content field (which would hide the layout design).
        $this->assertMatchesRegularExpression(
            '/\$hasDesignedLayout\s*=\s*\$layoutFile\s*!==\s*\'\'\s*&&\s*!\s*in_array\(\$layoutFile,\s*\[\'clean\.blade\.php\',\s*\'clean\.php\'\]/',
            $this->livePageSrc,
            'The save closure must compute $hasDesignedLayout from a non-clean layout_file.'
        );
        $this->assertMatchesRegularExpression(
            '/in_array\(\$contentType,\s*\[\'post\',\s*\'page\'\],\s*true\)\s*&&\s*!\s*\$hasDesignedLayout/',
            $this->livePageSrc,
            'The placeholder backfill must be gated by ! $hasDesignedLayout.'
        );
    }
}
