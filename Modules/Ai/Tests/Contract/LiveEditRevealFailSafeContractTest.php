<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Contract;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Guard for the Live-Edit agent's scroll-reveal guidance.
 *
 * A real site broke because the agent hand-rolled a reveal by putting the hidden
 * start state (opacity:0 !important) in CSS on a bare `section` selector; when
 * the reveal JS did not add the reveal class, every section stayed invisible —
 * a blank page. The agent prompt must steer away from that fail-deadly pattern:
 * prefer the data-mw-reveal motion kit, and if hand-rolling, gate the hidden
 * state so content is visible without JS. This pins that guidance in source.
 */
final class LiveEditRevealFailSafeContractTest extends TestCase
{
    private string $src = '';

    protected function setUp(): void
    {
        $root = dirname(__DIR__, 4);
        $this->src = (string) file_get_contents($root . '/Modules/Ai/Agents/LiveEditAgent.php');
    }

    #[Test]
    public function reveal_guidance_prefers_the_motion_kit(): void
    {
        $this->assertStringContainsString('data-mw-reveal', $this->src,
            'The agent must know about the fail-safe motion kit (data-mw-reveal).');
    }

    #[Test]
    public function reveal_guidance_forbids_unconditional_css_hiding(): void
    {
        // The prompt must state the fail-safe principle: content visible without JS.
        $this->assertMatchesRegularExpression(
            '~visible\s+when\s+JavaScript\s+does\s+not\s+run|FAILS?\s+SAFE|fail\s+safe~i',
            $this->src,
            'The agent prompt must state that content must be visible without JS (reveals fail safe).'
        );
        // And explicitly warn against the bare-selector unconditional hide.
        $this->assertMatchesRegularExpression(
            '~hidden start state.*opacity:0|opacity:0.*CSS.*plain selector|bare\s+`?section~is',
            $this->src,
            'The agent prompt must warn against putting the hidden state (opacity:0) in plain CSS on a bare selector.'
        );
    }

    #[Test]
    public function reveal_guidance_describes_a_js_gate(): void
    {
        // A JS-added gate class on <html> (or hide-in-JS) is the fail-safe mechanism.
        $this->assertMatchesRegularExpression(
            '~gate|js-reveal|documentElement|add(s|ed)?\s+(a\s+)?class\s+(the\s+JS\s+adds\s+)?to\s+<html>~i',
            $this->src,
            'The agent prompt must describe the JS-added gate (or hide-in-JS) fail-safe mechanism.'
        );
        // Reveal already-in-view elements immediately (so above-the-fold is not stuck).
        $this->assertMatchesRegularExpression(
            '~already[- ]in[- ]view|already in view~i',
            $this->src,
            'The agent prompt must say to reveal already-in-view elements immediately.'
        );
    }
}
