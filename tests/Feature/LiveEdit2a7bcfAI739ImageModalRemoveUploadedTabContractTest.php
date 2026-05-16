<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-2a7bcf / AI-739 — image upload modal IA cleanup.
 * Jira: https://microweber.atlassian.net/browse/AI-739
 *
 * Designer dispatch 2026-05-16T15:54:23 (High — first image-add
 * experience for every user).
 *
 * Two IA issues:
 *   1. Default Media library tab renders BLANK on open.
 *   2. "Uploaded" tab is redundant with "Media library" — both
 *      surface uploaded files; the former is legacy FileManager
 *      UI, the latter is the canonical iframe.
 *
 * This slice ships #2 only:
 *   - Remove the `server` (Uploaded) entry from the filepicker
 *     `components` array → 5 tabs become 4.
 *
 * Issue #1 (skeleton loader + "No files yet" empty state with
 * `[Upload from device]` CTA that switches to My computer) is
 * tracked as AI-739a — requires Vue component work on the
 * library tab's iframe-load-state surface that exceeds the
 * scope of a 1-line tab removal.
 */
class LiveEdit2a7bcfAI739ImageModalRemoveUploadedTabContractTest extends TestCase
{
    private string $filepicker;
    private string $bundle;
    private array $components = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->filepicker = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/components/filepicker.js'
        ));
        // Slice the components array literal to scan inside.
        // Bounded from `components: [` to matching `],`.
        $start = strpos($this->filepicker, 'components: [');
        if ($start !== false) {
            $end = strpos($this->filepicker, '        ],', $start);
            if ($end !== false) {
                $this->components = [substr($this->filepicker, $start, $end - $start)];
            }
        }
        $bundlePath = base_path(
            'packages/frontend-assets/resources/dist/build/admin.js'
        );
        $this->bundle = file_exists($bundlePath)
            ? (string) file_get_contents($bundlePath)
            : '';
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Uploaded tab removed
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function components_array_no_longer_carries_server_tab(): void
    {
        $this->assertNotEmpty(
            $this->components,
            'components: [ … ] array slice must be locatable in filepicker.js.'
        );
        $slice = $this->components[0];
        $this->assertDoesNotMatchRegularExpression(
            "/\\{\\s*type:\\s*['\"]server['\"]/",
            $slice,
            'components: [ … ] must NOT contain a { type: "server" } entry — the Uploaded tab is removed per AI-739.'
        );
    }

    #[Test]
    public function uploaded_label_string_gone_from_source(): void
    {
        // Strip block + line comments before regression scan so
        // the migration-rationale block (which legitimately quotes
        // "Uploaded") doesn't false-match. LESSONS selector-self-
        // match family — hit 6+ times this session.
        $rules = preg_replace('/\/\*.*?\*\//s', '', $this->filepicker);
        $rules = preg_replace('/\/\/.*$/m', '', $rules);
        $this->assertDoesNotMatchRegularExpression(
            "/label:\\s*mw\\.lang\\(['\"]Uploaded['\"]\\)/",
            $rules,
            'Legacy `label: mw.lang("Uploaded")` entry must no longer appear in the rendered components array.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — surviving 4 tabs preserved
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function four_remaining_tabs_present(): void
    {
        $slice = $this->components[0];
        foreach (['library', 'desktop', 'ai', 'url'] as $type) {
            $this->assertMatchesRegularExpression(
                "/\\{\\s*type:\\s*['\"]{$type}['\"]/",
                $slice,
                "Surviving tab type \"{$type}\" must remain in the components array."
            );
        }
    }

    #[Test]
    public function media_library_remains_first_per_baseline(): void
    {
        $slice = $this->components[0];
        // The audit-test 2026-05-07 baseline placed Media library
        // first as the most-used path. Removing the Uploaded tab
        // must NOT disturb that order.
        $this->assertMatchesRegularExpression(
            "/components:\\s*\\[\\s*\\{\\s*type:\\s*['\"]library['\"]/",
            $slice,
            'Media library must remain the first tab (audit-test 2026-05-07 baseline preserved).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — bundle runtime probe (SOUL #108)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function bundle_does_not_carry_uploaded_label_in_components_position(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Vite admin.js bundle not present — run `cd packages/frontend-assets && npm run build` to enable runtime probe.');
        }
        // Probe for the source-side pattern `type:"server"` next
        // to `label:"Uploaded"` (Vite minified output keeps the
        // structural shape). After the AI-739 tab removal, no
        // `type:"server"` should appear in the bundle.
        $this->assertDoesNotMatchRegularExpression(
            "/type:\\s*['\"]server['\"]\\s*,\\s*label:/",
            $this->bundle,
            'Bundle must not carry `type:"server"` paired with `label:` — the Uploaded tab tuple is gone.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — markers + deferred-followup hint
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai739_markers_pinned(): void
    {
        $this->assertStringContainsString('task-2026-05-17-2a7bcf', $this->filepicker);
        $this->assertStringContainsString('AI-739', $this->filepicker);
        $this->assertStringContainsString(
            'AI-739a',
            $this->filepicker,
            'Source comment must hint at AI-739a — skeleton loader + "No files yet" empty state follow-up.'
        );
    }
}
