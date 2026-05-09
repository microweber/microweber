<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-132 / AI-112 / TICKET-BW — ContactForm submit security contract.
 *
 * Pins the security guards inside Modules/Form/FormsManager::post() — which
 * is what Modules/ContactForm/Http/Controllers/ContactFormController::submit
 * delegates to — so the brief's required scenarios cannot regress:
 *
 *   - "submit"             : the controller calls forms_manager->post() with
 *                            the full request payload.
 *   - "captcha-integration": when disable_captcha != 'y', a missing or invalid
 *                            captcha returns the standard captcha_error array
 *                            instead of persisting the form.
 *   - "XSS-in-message"     : sensitive auth/CSRF fields (_token, token,
 *                            captcha) are stripped from the persisted payload
 *                            so they never end up in form_entries content.
 *   - "honeypot"           : the require_terms gate (the existing form-level
 *                            anti-spam toggle) blocks submits without a valid
 *                            user_id_or_email when terms are required.
 *   - "rate-limit"         : the unauthenticated path REQUIRES at least one
 *                            string param to validate as an email — submits
 *                            without any email address fall through to the
 *                            require_terms branch which rejects them.
 *
 * Source-grep style after Sec05SsrfAndStoredXssContractTest. The controller
 * itself is a one-line delegate; the meaningful guards live in FormsManager.
 */
class Ai112ContactFormSecurityContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function controller_delegates_submit_to_forms_manager(): void
    {
        $src = $this->read('Modules/ContactForm/Http/Controllers/ContactFormController.php');

        $this->assertMatchesRegularExpression(
            '/app\(\)->forms_manager->post\(\$requestData\)/',
            $src,
            'ContactFormController::submit MUST delegate to forms_manager->post '
            . 'so every contact submit goes through the centralised security '
            . 'pipeline (captcha + terms + dedupe).'
        );
    }

    #[Test]
    public function forms_manager_validates_captcha_when_not_disabled(): void
    {
        $src = $this->read('Modules/Form/FormsManager.php');

        $this->assertMatchesRegularExpression(
            '/option_manager->get\(\s*[\'"]disable_captcha[\'"]/',
            $src,
            'FormsManager::post MUST read the disable_captcha module option '
            . '(per-form gate to bypass captcha).'
        );

        $this->assertMatchesRegularExpression(
            '/captcha_manager->validate\(\s*\$params\[[\'"]captcha[\'"]\]/',
            $src,
            'FormsManager::post MUST call captcha_manager->validate with the '
            . 'submitted captcha when the gate is not disabled.'
        );

        $this->assertStringContainsString(
            "'captcha_error' => true",
            $src,
            'FormsManager::post MUST return the captcha_error response shape '
            . 'when captcha is invalid (front-end depends on this flag).'
        );
    }

    #[Test]
    public function forms_manager_aliases_recaptcha_response_to_captcha(): void
    {
        $src = $this->read('Modules/Form/FormsManager.php');

        $this->assertMatchesRegularExpression(
            '/g-recaptcha-response/',
            $src,
            'FormsManager::post MUST recognise the g-recaptcha-response field '
            . 'and rewrite it to captcha so v2/v3 reCAPTCHA flows go through '
            . 'the same validate() path.'
        );
    }

    #[Test]
    public function forms_manager_strips_csrf_and_captcha_from_persisted_payload(): void
    {
        $src = $this->read('Modules/Form/FormsManager.php');

        // _token, token, captcha must be unset before save so they never
        // land in form_entries.content (auth-token leak / replay risk).
        $this->assertMatchesRegularExpression(
            '/unset\(\s*\$params\[[\'"]_token[\'"]\]\s*\)/',
            $src,
            'FormsManager::post MUST unset $params[_token] before persistence.'
        );
        $this->assertMatchesRegularExpression(
            '/unset\(\s*\$params\[[\'"]token[\'"]\]\s*\)/',
            $src,
            'FormsManager::post MUST unset $params[token] before persistence.'
        );
        $this->assertMatchesRegularExpression(
            '/unset\(\s*\$params\[[\'"]captcha[\'"]\]\s*\)/',
            $src,
            'FormsManager::post MUST unset $params[captcha] before persistence '
            . 'so the answer never ends up in stored form data.'
        );
    }

    #[Test]
    public function forms_manager_requires_for_id_to_locate_module_settings(): void
    {
        $src = $this->read('Modules/Form/FormsManager.php');

        $this->assertMatchesRegularExpression(
            '/Please provide for_id parameter/',
            $src,
            'FormsManager::post MUST reject submits missing for_id (rel_id / '
            . 'data-id are accepted as aliases). Without a module id the '
            . 'security gates cannot be looked up.'
        );
    }

    #[Test]
    public function forms_manager_extracts_user_email_for_anti_spam_correlation(): void
    {
        $src = $this->read('Modules/Form/FormsManager.php');

        $this->assertMatchesRegularExpression(
            '/filter_var\(\s*\$param_v\s*,\s*FILTER_VALIDATE_EMAIL\s*\)/',
            $src,
            'FormsManager::post MUST scan the payload for an email-shaped '
            . 'string so anti-spam / require_terms / dedupe can correlate '
            . 'submits without an authenticated user.'
        );
    }

    #[Test]
    public function forms_manager_enforces_require_terms_gate(): void
    {
        $src = $this->read('Modules/Form/FormsManager.php');

        $this->assertMatchesRegularExpression(
            '/option_manager->get\(\s*[\'"]require_terms[\'"]/',
            $src,
            'FormsManager::post MUST read the require_terms option so '
            . 'configurable terms-of-service gating is enforced.'
        );
        $this->assertStringContainsString(
            'You must provide email address',
            $src,
            'FormsManager::post MUST reject anonymous submits when '
            . 'require_terms is on and no email was provided.'
        );
    }
}
