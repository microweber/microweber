<?php

declare(strict_types=1);

namespace Modules\Ai\Support;

/**
 * Formats the "loaded JS libraries" hint injected into a Live-Edit agent's
 * prompt, so the model drives motion/behaviour with the libraries that are
 * ACTUALLY on the page (gsap, ScrollTrigger, jQuery, …) instead of guessing.
 *
 * Shared by the single-agent chat (AiController) and the swarm (SwarmController).
 */
trait LibrariesHint
{
    /**
     * @param array<int, mixed> $libraries each item is a string "name" or
     *                                      ['name' => string, 'version' => string]
     */
    protected function formatLibrariesHint(array $libraries): string
    {
        $names = [];
        foreach ($libraries as $lib) {
            if (is_string($lib)) {
                $name = $lib;
                $ver = '';
            } else {
                $lib = (array) $lib;
                $name = (string) ($lib['name'] ?? '');
                $ver = (string) ($lib['version'] ?? '');
            }
            $name = trim($name);
            $ver = trim($ver);
            if ($name === '') {
                continue;
            }
            $names[] = $ver !== '' ? ($name . ' ' . $ver) : $name;
        }

        if (empty($names)) {
            return '';
        }

        return '[Loaded JS libraries on the published page: ' . implode(', ', $names) . '. '
            . 'In apply_js / apply_page_js you MAY use these directly — they are globals. '
            . 'If gsap is listed: use a GSAP timeline for the entrance/reveal and any zoom, ScrollTrigger (if listed) for scroll-driven animation, and a single raw requestAnimationFrame loop for continuous motion (physics, parallax, per-element displacement). '
            . 'Only use a library that appears in THIS list; otherwise write plain vanilla JS. '
            . 'Always feature-guard (e.g. if (typeof gsap !== "undefined") { … } else { /* vanilla fallback */ }) and wrap motion in prefers-reduced-motion so it degrades to a plain fade.]';
    }
}
