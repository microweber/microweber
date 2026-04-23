<?php

namespace Modules\WordPressMigration\Services\Media;

/**
 * Rehost an asset URL from an origin WordPress site into
 * Microweber's media storage.
 *
 * "Rehost" means: download the bytes, store them under
 * `userfiles/media/`, create a `media` row, and return the public
 * URL the imported HTML should point at. A null return means "the
 * URL should be left alone in the HTML" — use this for off-site
 * links, unsupported schemes, or (in tests) URLs the fake decides
 * aren't assets.
 *
 * The interface is a deliberately thin seam between the rewriter
 * and the download+record path so unit tests can script rehost
 * outcomes without invoking Microweber's HTTP client or writing
 * files.
 */
interface MediaRehoster
{
    /**
     * Rehost `$url` and return its new local public URL, or null
     * if the URL must be preserved unchanged.
     *
     * `$context` carries optional per-call hints — currently the
     * shape is `{rel_type?: string, rel_id?: int|string, host?: string}`.
     * Implementations may ignore any key they don't use; callers
     * MUST treat `$context` as advisory.
     *
     * Implementations MUST NOT throw for ordinary failures (404,
     * DNS, timeout, unrecognised scheme) — return null instead.
     * Exceptions are reserved for programmer errors (e.g. bad
     * argument types).
     *
     * @param array<string, mixed> $context
     */
    public function rehost(string $url, array $context = []): ?string;
}
