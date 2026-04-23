<?php

namespace Modules\WordPressMigration\Services\Http;

/**
 * Tiny HTTP contract used by WordPressSiteProbe.
 *
 * The probe must not throw on 4xx/5xx — a 404 on /wp-json is a
 * signal (REST disabled), not an error — so this contract exposes
 * (body, http_code, headers, error) directly instead of returning
 * a body string that hides the status or the X-WP-Total count.
 *
 * The real implementation goes through HttpClientFactory::executeCurl
 * so it inherits Microweber's SSL verification + protocol
 * restrictions (see CurlHttpProbeFetcher). Tests inject a fake that
 * returns a scripted table of per-URL responses.
 *
 * @phpstan-type ProbeHttpResult array{body: string, http_code: int, headers: array<string, string>, error: string}
 */
interface HttpProbeFetcher
{
    /**
     * Perform a GET against $url with $timeout seconds of budget.
     *
     * Must not throw on non-2xx. Transport failures (DNS, connection
     * reset, SSL handshake) populate the `error` field and leave
     * `http_code` at 0, `body` empty, and `headers` empty.
     *
     * Header keys are lowercased; values are the last occurrence
     * if the server sent duplicates. Callers index with lowercase
     * names (e.g. `$resp['headers']['x-wp-total']`).
     *
     * $authorization, when non-null, is sent verbatim as the request's
     * `Authorization:` header value. The WP REST importer uses it to
     * attach a WpAppPasswordCredential's `Basic ...` line; anon callers
     * (probe, RSS, sitemap) pass null and get the existing anon
     * behavior. The third param is optional so the 90% anon path
     * stays boilerplate-free.
     *
     * @return ProbeHttpResult
     */
    public function fetch(string $url, int $timeout, ?string $authorization = null): array;
}
