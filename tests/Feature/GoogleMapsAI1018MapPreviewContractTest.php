<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-05-AI1018 — Map module settings must show a live, reactive map
 * preview so operators verify the geocoded location WITHOUT the slow
 * type→save→navigate→check loop.
 *
 * The address fields (data-country/city/street/zip) are ->live(), so a
 * Placeholder reading $get() re-renders on every change — a server-reactive
 * equivalent of the ticket's Alpine x-bind:src that composes cleanly with
 * Filament form state. Runtime-verified in live-edit: empty-state placeholder
 * with no address, geocoded <iframe> once an address field is filled.
 */
class GoogleMapsAI1018MapPreviewContractTest extends TestCase
{
    private string $src;

    protected function setUp(): void
    {
        parent::setUp();
        $this->src = (string) file_get_contents(base_path(
            'Modules/GoogleMaps/Filament/GoogleMapsModuleSettings.php'
        ));
    }

    #[Test]
    public function settings_render_a_reactive_map_preview_placeholder(): void
    {
        $this->assertStringContainsString(
            "Placeholder::make('map_preview')",
            $this->src,
            'The Map settings must include a map_preview Placeholder.'
        );
        // Reactive: content closure receives Get so it re-evaluates on ->live() updates.
        $this->assertMatchesRegularExpression(
            "/Placeholder::make\('map_preview'\)[\s\S]*?->content\(function \(Get \\\$get\)/",
            $this->src,
            'The preview must read form state via a Get-typed content closure (server-reactive).'
        );
    }

    #[Test]
    public function preview_builds_embed_url_from_all_address_fields(): void
    {
        foreach (['data-street', 'data-city', 'data-zip', 'data-country'] as $field) {
            $this->assertStringContainsString(
                "\$get('options.{$field}')",
                $this->src,
                "The preview must incorporate the {$field} address field."
            );
        }
        // Uses the free Google Maps embed endpoint with an encoded query.
        $this->assertStringContainsString('output=embed', $this->src, 'Must use the embeddable maps endpoint.');
        $this->assertStringContainsString('rawurlencode(', $this->src, 'The address query must be URL-encoded.');
    }

    #[Test]
    public function preview_has_empty_state_and_escapes_output(): void
    {
        // Empty-state guard when no address has been entered yet.
        $this->assertMatchesRegularExpression(
            '/if \(empty\(\$parts\)\)/',
            $this->src,
            'The preview must show an empty-state hint when no address is set.'
        );
        // Defence-in-depth: the iframe src is e()-escaped inside the attribute.
        $this->assertMatchesRegularExpression(
            "/src=\"' \. e\(\\\$src\)/",
            $this->src,
            'The iframe src must be e()-escaped when interpolated into the attribute.'
        );
    }
}
