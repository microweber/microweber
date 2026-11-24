<?php

declare(strict_types=1);

namespace Modules\Ai\Support;

/**
 * Formats the "theme design tokens" hint injected into a Live-Edit agent's
 * prompt, so the model sees the ACTUAL CSS custom properties (and their computed
 * values) that the active template defines on this page — brand colour, text and
 * heading colours, button/form/section tokens, fonts, radii, spacing.
 *
 * WHY: the model builds sections that inherit these tokens. Without the palette
 * it (a) can't reuse the theme's own tokens so designs drift off-brand, and (b)
 * can't pick a legible text colour for a custom background it adds — it leaves
 * headings to inherit the template's base colour (often dark) which then vanishes
 * on a dark/gradient band the model created. The get_css_vars tool already reads
 * these on demand; this hint puts the whole set in front of the model up-front so
 * it doesn't have to ask, mirroring the LibrariesHint pattern.
 *
 * Shared by the single-agent chat (AiController) and the swarm (SwarmController).
 */
trait CssVarsHint
{
    /**
     * @param array<string,mixed> $cssVars name => computed value (as collected
     *                                      by the frontend and bound to the
     *                                      'mw.ai.liveedit.context').
     */
    protected function formatCssVarsHint(array $cssVars): string
    {
        $pairs = [];
        foreach ($cssVars as $name => $value) {
            $name = trim((string) $name);
            if ($name === '' || strncmp($name, '--', 2) !== 0) {
                continue;
            }
            $value = is_scalar($value) ? trim((string) $value) : '';
            // Keep each value short so the list stays readable.
            if (strlen($value) > 60) {
                $value = substr($value, 0, 57) . '…';
            }
            $pairs[] = $value === '' ? $name : ($name . ': ' . $value);

            // Defensive cap so a pathological page can't bloat the prompt.
            if (count($pairs) >= 160) {
                break;
            }
        }

        if (empty($pairs)) {
            return '';
        }

        return '[Theme design tokens (CSS variables) on this page — REUSE these in your CSS as '
            . 'var(--name) so your work matches the theme, and READ a token\'s value here to pick '
            . 'text colours that CONTRAST with any background you add (never leave a heading to '
            . 'inherit the template default — it is often dark text that disappears on a dark or '
            . 'gradient band you create). Key colour tokens: --mw-primary-color, --mw-body-color '
            . '(body text), --mw-heading-color (headings), --mw-link-color, --mw-background-color, '
            . '--mw-section-background-color, --mw-text-on-dark-background-color (use for text that '
            . 'sits on a dark/coloured background), --mw-btn-background-color/--mw-btn-text-color, '
            . 'fonts --mw-heading-font-family/--mw-body-font-family, --mw-border-radius. Change any '
            . 'token site-wide with set_css_var. Current values: ' . implode(' · ', $pairs) . ']';
    }
}
