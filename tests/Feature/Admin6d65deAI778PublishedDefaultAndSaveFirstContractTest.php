<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-6d65de / AI-778 — Posts admin Published-default flip +
 * Live-Edit-button save-first guard pin.
 * Jira: https://microweber.atlassian.net/browse/AI-778
 *
 * Designer's Round-10 audit raised two concerns:
 *   1. Published toggle defaulted to ON on Create — type title +
 *      SAVE → live immediately. Footgun for first-draft scenarios.
 *   2. Live Edit button next to SAVE has no save-first guard —
 *      data-loss risk.
 *
 * Recon:
 *   (1) Confirmed in source — was `default(fn ($get) => $get('id') ? 0 : 1)`
 *       at ContentResource::publishedSection(). On Create, $get('id')
 *       is null/falsy → returns 1 (publish). Flipped to `default(false)`
 *       so new content starts UNPUBLISHED — operator must explicitly
 *       opt in to publish before SAVE. Edit form unaffected because
 *       Filament loads is_active from the record via Eloquent; the
 *       default only applies when the record is null.
 *
 *   (2) The Live Edit button already DOES save first — the action key
 *       is `saveContentAndGoLiveEdit` (CreateContent.php:128 +
 *       EditContent.php:74) which routes to
 *       Modules/Content/Concerns/HasEditContentForms.php:10-22, where
 *       saveContentAndGoLiveEdit() → saveContent() → goLiveEdit().
 *       saveContent() calls parent::create() which throws on
 *       validation failure, halting before the redirect. Designer's
 *       "no save-first guard" appears to have missed the indirect
 *       call chain. This test pins the save-first chain so future
 *       refactors cannot accidentally drop the saveContent() call
 *       from the action handler.
 */
class Admin6d65deAI778PublishedDefaultAndSaveFirstContractTest extends TestCase
{
    private string $contentResource;
    private string $createContent;
    private string $editContent;
    private string $hasEditContentForms;

    protected function setUp(): void
    {
        parent::setUp();
        $this->contentResource = (string) file_get_contents(base_path(
            'Modules/Content/Filament/Admin/ContentResource.php'
        ));
        $this->createContent = (string) file_get_contents(base_path(
            'Modules/Content/Filament/Admin/ContentResource/Pages/CreateContent.php'
        ));
        $this->editContent = (string) file_get_contents(base_path(
            'Modules/Content/Filament/Admin/ContentResource/Pages/EditContent.php'
        ));
        $this->hasEditContentForms = (string) file_get_contents(base_path(
            'Modules/Content/Concerns/HasEditContentForms.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Published toggle default flipped to false on Create
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function published_toggle_default_is_false(): void
    {
        // Slice the publishedSection block via strpos to avoid the
        // selector-self-match guard hitting on docblock prose
        // mentioning the previous `default(fn ($get) => ...)` shape.
        $start = strpos($this->contentResource, 'protected static function publishedSection()');
        $this->assertNotFalse($start);
        $end = strpos($this->contentResource, '            ]);', $start);
        $this->assertNotFalse($end);
        $slice = substr($this->contentResource, $start, $end - $start);

        // The published control may be a Toggle or Radio. The key
        // contract is that is_active defaults to false/0 on create.
        $this->assertMatchesRegularExpression(
            "/(?:Toggle|Radio)::make\('is_active'\)/s",
            $slice,
            "publishedSection() must declare a Toggle or Radio for 'is_active'."
        );
        $this->assertMatchesRegularExpression(
            "/->default\((?:false|0)\)/s",
            $slice,
            "publishedSection() must default is_active to false/0 so new content starts unpublished."
        );
    }

    #[Test]
    public function legacy_get_id_ternary_default_is_gone(): void
    {
        // Negative regression-guard: the previous shape used a
        // closure with `$get('id') ? 0 : 1`. After the AI-778 fix
        // that pattern must NOT appear inside publishedSection.
        $start = strpos($this->contentResource, 'protected static function publishedSection()');
        $this->assertNotFalse($start);
        $end = strpos($this->contentResource, '            ]);', $start);
        $slice = substr($this->contentResource, $start, $end - $start);
        $this->assertDoesNotMatchRegularExpression(
            "/->default\(function[^)]*\)\s*\{[^}]*\\\$get\('id'\)\s*\?\s*0\s*:\s*1/s",
            $slice,
            "Legacy `default(function() { return \$get('id') ? 0 : 1; })` shape must be gone from publishedSection."
        );
    }

    #[Test]
    public function posted_at_auto_set_logic_preserved(): void
    {
        // The afterStateUpdated callback that auto-sets posted_at
        // when is_active flips on is preserved (operator-friendly
        // behaviour — toggling ON still timestamps the publish
        // datetime even though it's no longer the default).
        $start = strpos($this->contentResource, 'protected static function publishedSection()');
        $end = strpos($this->contentResource, '            ]);', $start);
        $slice = substr($this->contentResource, $start, $end - $start);
        $this->assertStringContainsString("if (\$get('is_active') && !\$get('posted_at'))", $slice);
        $this->assertStringContainsString("\$set('posted_at', now()->format('Y-m-d H:i:s'))", $slice);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Live Edit button SAVE-first chain (pin against refactor)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function create_page_live_edit_button_routes_through_save(): void
    {
        // CreateContent's Live Edit button must use an action key
        // whose handler save()s first — `saveContentAndGoLiveEdit`
        // or `saveContentAndGoLiveEditIframe`. Bare `goLiveEdit`
        // (which would skip the save step) MUST NOT appear as the
        // action target.
        $this->assertMatchesRegularExpression(
            "/Actions\\\\Action::make\('liveEdit'\)->action\('saveContentAndGoLiveEdit'\)/",
            $this->createContent,
            'CreateContent liveEdit action must route to saveContentAndGoLiveEdit (save-first).'
        );
        $this->assertMatchesRegularExpression(
            "/Actions\\\\Action::make\('liveEditIframe'\)->action\('saveContentAndGoLiveEditIframe'\)/",
            $this->createContent,
            'CreateContent liveEditIframe action must route to saveContentAndGoLiveEditIframe (save-first).'
        );
        // Negative regression-guard: bare `->action('goLiveEdit')`
        // (skipping the save call) must NOT appear.
        $this->assertDoesNotMatchRegularExpression(
            "/->action\('goLiveEdit'\)/",
            $this->createContent,
            "CreateContent must NOT use bare `->action('goLiveEdit')` as a header-action target — would skip the save-first guard."
        );
    }

    #[Test]
    public function edit_page_live_edit_button_routes_through_save(): void
    {
        // Same guard for EditContent.
        $this->assertMatchesRegularExpression(
            "/->action\('saveContentAndGoLiveEdit(Iframe)?'\)/",
            $this->editContent,
            'EditContent EditAction must route to saveContentAndGoLiveEdit (save-first).'
        );
        $this->assertDoesNotMatchRegularExpression(
            "/->action\('goLiveEdit'\)/",
            $this->editContent,
            "EditContent must NOT use bare `->action('goLiveEdit')` as a header-action target."
        );
    }

    #[Test]
    public function save_first_chain_in_trait_is_preserved(): void
    {
        // The trait method must call saveContent() BEFORE goLiveEdit().
        // Pin both presence and ordering so a refactor cannot reorder
        // or drop either call.
        $start = strpos($this->hasEditContentForms, 'public function saveContentAndGoLiveEdit()');
        $this->assertNotFalse($start);
        // Slice from method opener to the closing brace.
        $end = strpos($this->hasEditContentForms, '    }', $start);
        $this->assertNotFalse($end);
        $slice = substr($this->hasEditContentForms, $start, $end - $start);
        $savePos = strpos($slice, '$this->saveContent();');
        $goPos = strpos($slice, '$this->goLiveEdit(');
        $this->assertNotFalse($savePos, 'saveContentAndGoLiveEdit() must call $this->saveContent().');
        $this->assertNotFalse($goPos, 'saveContentAndGoLiveEdit() must call $this->goLiveEdit().');
        $this->assertLessThan(
            $goPos,
            $savePos,
            'saveContent() MUST be called before goLiveEdit() — save-first guard. Refactors that reorder these calls reintroduce the AI-778 data-loss risk.'
        );

        // Same guard for the iframe variant.
        $startIframe = strpos($this->hasEditContentForms, 'public function saveContentAndGoLiveEditIframe()');
        $this->assertNotFalse($startIframe);
        $endIframe = strpos($this->hasEditContentForms, '    }', $startIframe);
        $sliceIframe = substr($this->hasEditContentForms, $startIframe, $endIframe - $startIframe);
        $savePosI = strpos($sliceIframe, '$this->saveContent();');
        $goPosI = strpos($sliceIframe, '$this->goLiveEdit(');
        $this->assertNotFalse($savePosI);
        $this->assertNotFalse($goPosI);
        $this->assertLessThan($goPosI, $savePosI);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai778_markers_present(): void
    {
        $this->assertStringContainsString('task-2026-05-17-6d65de', $this->contentResource);
        $this->assertStringContainsString('AI-778', $this->contentResource);
    }
}
