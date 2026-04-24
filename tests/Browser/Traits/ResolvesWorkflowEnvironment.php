<?php

declare(strict_types=1);

namespace Tests\Browser\Traits;

/**
 * Central resolver for the workflow test's environment knobs —
 * admin creds, app URL, headless-mode marker. Sources values from
 * `.env.dusk` (loaded by `Tests\DuskTestCase`) so the test file
 * itself carries no hard-coded hosts, ports, emails, or passwords.
 *
 * Satisfies the last Plan A.1 acceptance bullet: the workflow
 * fixture values come from env, not PHP constants, so a contributor
 * can run the suite against a non-default dev install by editing
 * `.env.dusk` rather than touching the test file.
 *
 * Defaults (matching the canonical dev install) keep the trait
 * robust even when `.env.dusk` is missing the optional keys — the
 * test still runs, the defaults just match what the bulk of the
 * Dusk suite has always used.
 */
trait ResolvesWorkflowEnvironment
{
    protected function workflowAdminEmail(): string
    {
        return (string) env('DUSK_ADMIN_EMAIL', 'admin@admin.com');
    }

    protected function workflowAdminPassword(): string
    {
        return (string) env('DUSK_ADMIN_PASSWORD', 'admin');
    }

    /**
     * Base URL the browser should visit. Sourced from `APP_URL`
     * (canonical Laravel convention) and trimmed of trailing slash
     * so relative paths can be concatenated without doubling.
     */
    protected function workflowAppUrl(): string
    {
        $url = (string) env('APP_URL', 'http://127.0.0.1:8000/');
        return rtrim($url, '/');
    }

    /**
     * True when the test is running in a headless environment
     * (CI, `composer test:browser`). False for
     * `composer test:browser:headed`, so a contributor can
     * opt-in to a visible browser for debugging.
     *
     * The marker comes from `DUSK_HEADLESS_DISABLED=1` which the
     * Dusk `test:browser:headed` script sets; default is
     * headless (true).
     */
    protected function workflowIsHeadless(): bool
    {
        return (string) env('DUSK_HEADLESS_DISABLED', '') !== '1';
    }
}
