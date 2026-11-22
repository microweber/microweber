<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit\Concerns;

/**
 * Server-side URL-scheme guard — the PHP sibling of mw-ai.js isUnsafeUrl.
 *
 * Tools that persist an author-controlled URL as a link href (menu items,
 * written straight to the DB and rendered as <a href="{url}"> on the published
 * page) must run it through this guard. The agent can be steered by page text it
 * reads, so a javascript:/vbscript:/data: URL is a stored-XSS vector that fires
 * when a visitor clicks the published link.
 */
trait GuardsUrlScheme
{
    /**
     * Returns the offending scheme name (e.g. "javascript") when the URL would
     * execute as a script or carry markup on the published page, else ''.
     *
     * Normalises the way a browser does before dispatching a scheme — strips all
     * leading/embedded control + whitespace chars (so java&#9;script: can't slip
     * past a leading-only test) and lowercases — then blocks javascript:,
     * vbscript: and data:. Menu hrefs never legitimately use those schemes, so
     * there is no data:image exemption here (unlike an <img src> on the canvas).
     */
    protected function unsafeUrlScheme(string $url): string
    {
        $s = strtolower((string) preg_replace('/[\x00-\x20]+/', '', $url));
        if (preg_match('/^(javascript|vbscript):/', $s, $m) === 1) {
            return $m[1];
        }
        if (str_starts_with($s, 'data:')) {
            return 'data';
        }

        return '';
    }
}
