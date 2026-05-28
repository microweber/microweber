<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-28-92a3f1 / AI-1061 — Login & Register settings
 * identical placeholder copy differentiated.
 * Jira: https://microweber.atlassian.net/browse/AI-1061
 *
 * Pre-fix: both "Register options" and "Login options" sections in
 * AdminLoginRegisterPage shared the exact same description string
 * "Set your settings for proper login and register functionality."
 * This is generic boilerplate that gives no signal about what each
 * section actually controls.
 *
 * Fix: differentiate the two descriptions so each describes its
 * section's actual purpose:
 *   Register options → 'Configure who can sign up for a new account
 *                       and how new registrations are handled.'
 *   Login options    → 'Configure how existing users log in and
 *                       manage access to their accounts.'
 *
 * Source-of-truth: Modules/Settings/Filament/Pages/AdminLoginRegisterPage.php
 *
 * Selector-self-match guard: setUp() pre-strips PHP block comments
 * before any absence assertion (uniformity-rule from task-7aa48a).
 */
class Admin92a3f1AI1061LoginRegisterDescriptionContractTest extends TestCase
{
    private const SOURCE = 'Modules/Settings/Filament/Pages/AdminLoginRegisterPage.php';

    private string $source;

    private string $sourceStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $this->source = (string) file_get_contents(base_path(self::SOURCE));
        // Strip block comments before absence assertions (task-7aa48a uniformity rule)
        $this->sourceStripped = (string) preg_replace('~/\*[\s\S]*?\*/~', '', $this->source);
        // Strip single-line comments
        $this->sourceStripped = (string) preg_replace('~//[^\n]*~', '', $this->sourceStripped);
    }

    // -----------------------------------------------------------------------
    // Group A — Register options section has differentiated description
    // -----------------------------------------------------------------------

    #[Test]
    public function register_section_description_mentions_sign_up(): void
    {
        $this->assertStringContainsString(
            'sign up',
            $this->source,
            'AI-1061: Register options section description MUST mention sign up to differentiate it from Login options.'
        );
    }

    #[Test]
    public function register_section_has_register_specific_description(): void
    {
        $this->assertMatchesRegularExpression(
            '/Section::make\(\'Register options\'\)[\s\S]{0,300}?->description\(\'Configure who can sign up/',
            $this->source,
            'AI-1061: Register options MUST carry its own register-specific description string.'
        );
    }

    // -----------------------------------------------------------------------
    // Group B — Login options section has differentiated description
    // -----------------------------------------------------------------------

    #[Test]
    public function login_section_description_mentions_log_in(): void
    {
        $this->assertStringContainsString(
            'log in',
            $this->source,
            'AI-1061: Login options section description MUST mention "log in" to differentiate it from Register options.'
        );
    }

    #[Test]
    public function login_section_has_login_specific_description(): void
    {
        $this->assertMatchesRegularExpression(
            '/Section::make\(\'Login options\'\)[\s\S]{0,300}?->description\(\'Configure how existing users log in/',
            $this->source,
            'AI-1061: Login options MUST carry its own login-specific description string.'
        );
    }

    // -----------------------------------------------------------------------
    // Group C — Legacy identical copy absent (comment-stripped negative)
    // -----------------------------------------------------------------------

    #[Test]
    public function identical_boilerplate_description_is_absent(): void
    {
        // Count occurrences of the old identical boilerplate description.
        // Pre-fix: 2 occurrences (both sections shared the same string).
        // Post-fix: 0 occurrences.
        $count = substr_count(
            $this->sourceStripped,
            "Set your settings for proper login and register functionality."
        );

        $this->assertSame(
            0,
            $count,
            "AI-1061: The old identical boilerplate description string MUST be absent — both sections now carry distinct, purpose-specific descriptions."
        );
    }

    // -----------------------------------------------------------------------
    // Group D — Descriptions are distinct (not identical to each other)
    // -----------------------------------------------------------------------

    #[Test]
    public function register_and_login_descriptions_are_distinct(): void
    {
        preg_match_all(
            '/Section::make\(\'(?:Register|Login) options\'\)[\s\S]{0,300}?->description\(\'([^\']+)\'\)/',
            $this->source,
            $matches
        );

        $this->assertCount(
            2,
            $matches[1],
            'AI-1061: Both Register options and Login options sections MUST carry a ->description() call.'
        );

        $this->assertNotSame(
            $matches[1][0],
            $matches[1][1],
            'AI-1061: Register options and Login options MUST have DISTINCT description strings — not the same boilerplate.'
        );
    }

    // -----------------------------------------------------------------------
    // Group E — Task-id marker present
    // -----------------------------------------------------------------------

    #[Test]
    public function task_id_marker_present_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-92a3f1',
            $this->source,
            'AI-1061: source MUST carry the task-id marker for cross-surface audit grep.'
        );
        $this->assertStringContainsString(
            'AI-1061',
            $this->source,
            'AI-1061: source MUST cite the AI-1061 ticket ID.'
        );
    }
}
