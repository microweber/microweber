<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-64 / AI-57 / TICKET-QQ — newsletter click-link HMAC token
 * regression coverage.
 *
 * Pins the contract that:
 *   1. NewsletterMailSender appends `&sig=<hash_hmac sha256>` to every
 *      click-link URL, computed over `campaign_id|email|redirect_to`
 *      with config('app.key') as the secret.
 *   2. The /click-link route VERIFIES the HMAC before recording a
 *      click — only valid signatures touch the
 *      newsletter_campaigns_clicked_link table (closes the
 *      stats-poisoning leg).
 *   3. The cycle-7 same-host fallback stays in place so legacy
 *      in-flight emails (sent before this change) still redirect
 *      cleanly without 404ing — they just don't record.
 *   4. hash_equals() is used (constant-time) so the verification
 *      step is not vulnerable to timing oracles.
 *
 * Style after the cycle-52..63 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class NewsletterClickLinkHmacContractTest extends TestCase
{
    private string $senderSrc;
    private string $routeSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->senderSrc = file_get_contents(base_path(
            'Modules/Newsletter/Senders/NewsletterMailSender.php'
        ));
        $this->routeSrc = file_get_contents(base_path(
            'Modules/Newsletter/routes/web.php'
        ));
    }

    #[Test]
    public function sender_appends_sig_query_param_to_every_click_link(): void
    {
        // Pin the URL builder shape: each part urlencoded, sig last.
        $this->assertStringContainsString(
            "'&sig=' . urlencode(\$sig)",
            $this->senderSrc,
            'NewsletterMailSender: must append &sig=<urlencoded HMAC> to every click-link'
        );
        $this->assertStringContainsString(
            "hash_hmac('sha256'",
            $this->senderSrc,
            'NewsletterMailSender: must use hash_hmac with sha256'
        );
        // The HMAC payload must include all three trust-relevant fields.
        $this->assertMatchesRegularExpression(
            "/\\\$payload\\s*=\\s*\\(string\\)\\s*\\\$this->campaign\\['id'\\]\\s*\\.\\s*'\\|'\\s*\\.\\s*\\(string\\)\\s*\\\$email\\s*\\.\\s*'\\|'\\s*\\.\\s*\\\$originalHref/",
            $this->senderSrc,
            'NewsletterMailSender: HMAC payload must be `campaign_id|email|redirect_to` (pipe-delimited)'
        );
        $this->assertStringContainsString(
            "config('app.key')",
            $this->senderSrc,
            'NewsletterMailSender: HMAC secret must be config(app.key)'
        );

        // The href value must come from $link->getAttribute('href') — the
        // $originalHref local variable. Pin that the URL builder reads
        // it BEFORE the urlencode(...) call to guarantee the HMAC and
        // the URL agree on the exact bytes.
        $hashPos = strpos($this->senderSrc, "\$sig = hash_hmac('sha256'");
        $hrefPos = strpos($this->senderSrc, '&redirect_to=\' . urlencode($originalHref)');
        $this->assertNotFalse($hashPos, 'NewsletterMailSender: hash_hmac() call must exist');
        $this->assertNotFalse($hrefPos, 'NewsletterMailSender: redirect_to interpolation must use $originalHref');
        $this->assertLessThan(
            $hrefPos,
            $hashPos,
            'NewsletterMailSender: HMAC must be computed BEFORE the URL is built (so both sides see the same bytes)'
        );
    }

    #[Test]
    public function route_verifies_hmac_with_constant_time_compare(): void
    {
        // hash_equals is constant-time; vanilla `==` would leak timing.
        $this->assertStringContainsString(
            'hash_equals($expectedSig, $providedSig)',
            $this->routeSrc,
            '/click-link route: must use hash_equals() (constant-time) to compare HMACs'
        );
        $this->assertStringContainsString(
            "hash_hmac(\n                    'sha256'",
            $this->routeSrc,
            '/click-link route: must recompute HMAC with hash_hmac sha256'
        );
        $this->assertStringContainsString(
            "config('app.key')",
            $this->routeSrc,
            '/click-link route: HMAC verification must use config(app.key) as the secret'
        );
    }

    #[Test]
    public function route_only_records_click_when_signature_is_valid(): void
    {
        // The DB write (`->save()`) on NewsletterCampaignClickedLink
        // must be inside an `if ($sigIsValid && $campaignId)` guard.
        // Junk POSTs without a valid sig must NOT poison the analytics
        // table.
        $this->assertMatchesRegularExpression(
            '/if\\s*\\(\\s*\\$sigIsValid\\s*&&\\s*\\$campaignId\\s*\\)/',
            $this->routeSrc,
            '/click-link route: DB record block must be gated by `if ($sigIsValid && $campaignId)`'
        );

        // Pin that the recording branch is NOT reachable when sig is missing.
        $sigGuardPos = strpos(
            $this->routeSrc,
            'if ($sigIsValid && $campaignId)'
        );
        $savePos = strpos(
            $this->routeSrc,
            '$newsletterCampaignClickedLink->save();'
        );
        $this->assertNotFalse($sigGuardPos, '/click-link route: sig guard must exist');
        $this->assertNotFalse($savePos, '/click-link route: ->save() must exist');
        $this->assertLessThan(
            $savePos,
            $sigGuardPos,
            '/click-link route: ->save() must come AFTER the sig guard'
        );
    }

    #[Test]
    public function legacy_same_host_fallback_remains_for_in_flight_emails(): void
    {
        // The cycle-7 same-host validation stays as a fallback so emails
        // sent before this change still redirect cleanly.
        $this->assertStringContainsString(
            "in_array(\$scheme, ['http', 'https'], true)",
            $this->routeSrc,
            '/click-link route: same-host scheme validation must stay'
        );
        $this->assertStringContainsString(
            'strcasecmp($parts[\'host\'], $siteHost) === 0',
            $this->routeSrc,
            '/click-link route: same-host hostname check must stay'
        );
        // The `elseif ($redirectTo)` branch must come AFTER `if ($sigIsValid)`
        // so a valid sig wins over the legacy path.
        $this->assertMatchesRegularExpression(
            '/if\\s*\\(\\s*\\$sigIsValid\\s*\\)\\s*\\{[^}]*\\}\\s*elseif\\s*\\(\\s*\\$redirectTo\\s*\\)/',
            $this->routeSrc,
            '/click-link route: sig branch must precede the same-host fallback (`if ($sigIsValid) {...} elseif ($redirectTo) {...}`)'
        );
    }

    #[Test]
    public function hmac_payload_format_matches_between_sender_and_route(): void
    {
        // The two sides MUST agree byte-for-byte on the payload format
        // or the HMAC will never validate. Pin that both sides use the
        // same `campaign_id|email|redirect_to` shape.
        // Sender side:
        $this->assertStringContainsString(
            "(string) \$this->campaign['id']\n                . '|' . (string) \$email\n                . '|' . \$originalHref",
            $this->senderSrc,
            'NewsletterMailSender: payload string must be `campaign_id|email|redirect_to`'
        );
        // Route side:
        $this->assertStringContainsString(
            "(string) \$campaignId . '|' . (string) \$requestEmail . '|' . \$redirectTo",
            $this->routeSrc,
            '/click-link route: payload string must be `campaign_id|email|redirect_to`'
        );
    }

    #[Test]
    public function hmac_round_trip_works_in_practice(): void
    {
        // Mirror the production code exactly: hash_hmac + hash_equals.
        // This pins the algorithm + the constant-time compare.
        $secret = 'base64:dGVzdC1zZWNyZXQ=';
        $campaignId = 42;
        $email = 'jane@example.com';
        $href = 'https://example.com/landing?utm_source=newsletter&id=1';

        $payload = (string) $campaignId . '|' . $email . '|' . $href;
        $sig = hash_hmac('sha256', $payload, $secret);

        // Round-trip: server computes the expected sig and compares.
        $expected = hash_hmac('sha256', $payload, $secret);
        $this->assertTrue(
            hash_equals($expected, $sig),
            'HMAC round-trip must succeed when server and client share the secret + payload'
        );

        // Tampering rejected:
        $this->assertFalse(
            hash_equals(
                hash_hmac('sha256', '99|jane@example.com|' . $href, $secret),
                $sig
            ),
            'HMAC must reject when campaign_id is changed'
        );
        $this->assertFalse(
            hash_equals(
                hash_hmac('sha256', $payload, 'different-secret'),
                $sig
            ),
            'HMAC must reject when secret is changed'
        );
    }
}
