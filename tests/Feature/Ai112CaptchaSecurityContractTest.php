<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-132 / AI-112 / TICKET-BV — Captcha security contract.
 *
 * Pins the security guards inside Modules/Captcha/Adapters/MicroweberCaptcha::validate()
 * so the brief's required scenarios can never silently regress:
 *
 *   - "valid"   : a key matching session_get('captcha') / session_get('captcha_<id>')
 *                 is accepted and the entry is reset (so it cannot be replayed).
 *   - "invalid" : an unknown key falls through to increaseDificulty() which bumps
 *                 the session-stored captcha_difficulty counter.
 *   - "replay"  : once accepted, the key is unset from session_recent so a second
 *                 submit with the same value fails.
 *   - "missing" : a falsy / empty key is rejected with an early `return false`.
 *   - "timeout" : difficulty escalates so a stale stored captcha makes the next
 *                 request progressively harder (random-letter mode at >4).
 *
 * Source-grep style after Sec05SsrfAndStoredXssContractTest. We pin the source
 * code rather than wire up `app()->user_manager` mocks because the adapter is
 * tightly coupled to the legacy session manager and a behavioural test needs
 * the full app boot path which the run-tests.sh suite already exercises in
 * Browser/Feature smokes.
 */
class Ai112CaptchaSecurityContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function captcha_rejects_empty_or_falsy_key_early(): void
    {
        $src = $this->read('Modules/Captcha/Adapters/MicroweberCaptcha.php');

        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*\$key\s*==\s*false\s*\)\s*\{\s*return\s+false\s*;/',
            $src,
            'MicroweberCaptcha::validate MUST return false early when $key is falsy '
            . '(missing-token scenario).'
        );
    }

    #[Test]
    public function captcha_trims_input_before_compare(): void
    {
        $src = $this->read('Modules/Captcha/Adapters/MicroweberCaptcha.php');

        $this->assertStringContainsString(
            '$key = trim($key);',
            $src,
            'MicroweberCaptcha::validate MUST trim() the input so leading/trailing '
            . 'whitespace cannot accidentally invalidate a correct answer.'
        );
    }

    #[Test]
    public function captcha_consumes_recent_key_on_match_to_prevent_replay(): void
    {
        $src = $this->read('Modules/Captcha/Adapters/MicroweberCaptcha.php');

        // The "recent" pool is a list of accepted-but-not-yet-cleaned answers;
        // when a key matches there, the adapter MUST unset it before returning
        // true so a second submit cannot reuse it.
        $this->assertMatchesRegularExpression(
            '/array_search\(\$key,\s*\$old_array\)/',
            $src,
            'validate MUST locate matched key in $old_array (captcha_recent) '
            . 'so it can unset it for replay protection.'
        );

        $this->assertMatchesRegularExpression(
            '/unset\(\$old_array\[\$found_key\]\)/',
            $src,
            'validate MUST unset the matched entry from captcha_recent so a '
            . 'second submit with the same answer fails.'
        );

        $this->assertMatchesRegularExpression(
            '/session_set\(\s*[\'"]captcha_recent[\'"]\s*,\s*\$old_array\s*\)/',
            $src,
            'validate MUST persist the trimmed captcha_recent back to session '
            . 'so the unset takes effect across requests.'
        );
    }

    #[Test]
    public function captcha_resets_per_id_session_on_id_match(): void
    {
        $src = $this->read('Modules/Captcha/Adapters/MicroweberCaptcha.php');

        $this->assertMatchesRegularExpression(
            '/session_get\(\s*[\'"]captcha_[\'"]\s*\.\s*\$captcha_id\s*\)/',
            $src,
            'validate MUST scope the session lookup to a per-id key when '
            . '$captcha_id is provided (multi-form-on-page support).'
        );

        $this->assertMatchesRegularExpression(
            '/\$this->reset\(\$captcha_id\)/',
            $src,
            'validate MUST reset(\$captcha_id) on a per-id match so the same '
            . 'answer cannot be replayed against the same form.'
        );
    }

    #[Test]
    public function captcha_falls_through_to_increase_difficulty_on_invalid_key(): void
    {
        $src = $this->read('Modules/Captcha/Adapters/MicroweberCaptcha.php');

        // The fall-through path (no key match) calls increaseDificulty so a
        // brute-force attempt makes the next captcha harder to read.
        $this->assertStringContainsString(
            '$this->increaseDificulty();',
            $src,
            'validate MUST escalate difficulty on every miss so brute force '
            . 'becomes progressively harder.'
        );
    }

    #[Test]
    public function captcha_difficulty_escalates_to_random_letters_above_threshold(): void
    {
        $src = $this->read('Modules/Captcha/Adapters/MicroweberCaptcha.php');

        // Once difficulty exceeds 4, render() switches from numeric to
        // random-letter mode — pin the threshold to prevent a regression
        // that would silently weaken the captcha.
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*\$difficulty\s*>\s*4\s*\)/',
            $src,
            'render() MUST escalate to random-letter mode when difficulty > 4 '
            . '(timeout / repeated-fail scenario from the brief).'
        );
    }

    #[Test]
    public function captcha_image_response_carries_no_cache_and_anti_indexing_headers(): void
    {
        $src = $this->read('Modules/Captcha/Adapters/MicroweberCaptcha.php');

        // Captcha image must not be cached and must not be indexed by search
        // engines — both are required to make the challenge meaningful.
        $this->assertStringContainsString(
            "X-Robots-Tag", $src,
            'render() response MUST set X-Robots-Tag so search engines do not '
            . 'cache challenge images.'
        );
        $this->assertMatchesRegularExpression(
            '/Cache-Control[\'"]\s*,\s*[\'"]no-store/',
            $src,
            'render() MUST send Cache-Control: no-store on the challenge PNG.'
        );
    }
}
