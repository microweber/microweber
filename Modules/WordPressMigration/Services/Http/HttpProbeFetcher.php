<?php

namespace Modules\WordPressMigration\Services\Http;

/**
 * Tiny HTTP contract used by WordPressSiteProbe.
 *
 * The probe must not throw on 4xx/5xx — a 404 on /wp-json is a
 * signal (REST disabled), not an error — so this contract exposes
 * (body, http_code, error) directly instead of returning a body
 * string that hides the status.
 *
 * The real implementation goes through HttpClientFactory::executeCurl
 * so it inherits Microweber's SSL verification + protocol
 * restrictions (see CurlHttpProbeFetcher). Tests inject a fake that
 * returns a scripted table of per-URL responses.
 *
 * @phpstan-type ProbeHttpResult array{body: string, http_code: int, error: string}
 */
interface HttpProbeFetcher
{
    /**
     * Perform a GET against $url with $timeout seconds of budget.
     *
     * Must not throw on non-2xx. Transport failures (DNS, connection
     * reset, SSL handshake) populate the `error` field and leave
     * `http_code` at 0 and `body` empty.
     *
     * @return ProbeHttpResult
     */
    public function fetch(string $url, int $timeout): array;
}
