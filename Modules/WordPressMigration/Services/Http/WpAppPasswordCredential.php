<?php

namespace Modules\WordPressMigration\Services\Http;

use InvalidArgumentException;

/**
 * WordPress Application Password credential (WP 5.6+).
 *
 * The WP admin emits application passwords as six space-separated
 * four-character groups (e.g. `abcd efgh ijkl mnop qrst uvwx`).
 * WordPress's REST auth layer accepts either the space-delimited
 * form or the packed form, so this object normalizes whitespace
 * out of the password on construction — the Authorization header
 * produced here base64-encodes `username:password` with the
 * normalized password, which is the shape WP documents.
 *
 * The class is intentionally a narrow value object: it only
 * validates shape (non-empty user + non-empty pass, ASCII-safe
 * colon separator) and emits a Basic-auth header. It does not
 * know how to fetch or persist itself — storage lives on
 * WordPressMigrationJob.encrypted_credentials and retrieval is
 * the caller's concern. Decoupling that way keeps the importer
 * free of DB access in unit tests.
 */
final class WpAppPasswordCredential
{
    private function __construct(
        private readonly string $username,
        private readonly string $password,
    ) {}

    /**
     * Parse the `user:password` form. Whitespace inside the password
     * half is stripped so the four-char groups WP displays round-trip.
     *
     * A bare `user:pass` string without any colon is invalid — callers
     * that somehow end up with just a password must supply the user
     * explicitly via the constructor.
     */
    public static function fromString(string $raw): self
    {
        $raw = trim($raw);
        if ($raw === '') {
            throw new InvalidArgumentException('Application password cannot be empty.');
        }
        $colon = strpos($raw, ':');
        if ($colon === false) {
            throw new InvalidArgumentException('Application password must be formatted as "username:password".');
        }
        $user = trim(substr($raw, 0, $colon));
        $pass = substr($raw, $colon + 1);
        return self::of($user, $pass);
    }

    /**
     * Construct from already-split user + pass. Useful when the UI
     * collects the two fields separately (most common in the Filament
     * form) so callers don't have to synthesize a colon-joined string
     * just to re-split it.
     */
    public static function of(string $username, string $password): self
    {
        $user = trim($username);
        // Strip all ASCII whitespace from the password — WP's admin
        // renders app passwords with spaces between four-char groups
        // purely for readability. The actual secret has no spaces.
        $pass = preg_replace('/\s+/', '', $password) ?? '';
        if ($user === '') {
            throw new InvalidArgumentException('Application password username cannot be empty.');
        }
        if ($pass === '') {
            throw new InvalidArgumentException('Application password secret cannot be empty.');
        }
        return new self($user, $pass);
    }

    public function username(): string
    {
        return $this->username;
    }

    /**
     * Render the HTTP Authorization header value
     * (`Basic base64(user:pass)`). Returns just the header value —
     * callers prepend it to the `Authorization:` line themselves so
     * this object stays oblivious to transport details.
     */
    public function authorizationHeader(): string
    {
        return 'Basic ' . base64_encode($this->username . ':' . $this->password);
    }
}
