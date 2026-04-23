<?php

namespace Modules\WordPressMigration\Tests\Unit;

use InvalidArgumentException;
use Modules\WordPressMigration\Services\Http\WpAppPasswordCredential;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Unit coverage for the WP 5.6+ application-password value object.
 *
 * The credential sits on the hot path of every authenticated REST
 * request so these tests pin three contracts:
 *
 *   - Whitespace normalization: the WP admin renders app passwords
 *     as four-char groups separated by spaces; both forms must
 *     produce the same Authorization header.
 *   - Basic-auth encoding: `Authorization: Basic base64(user:pass)`
 *     using the normalized password.
 *   - Strict validation: empty user or empty secret must throw so
 *     the caller sees the malformed-input bug at construction time,
 *     not as a silent 401 during a long REST walk.
 */
class WpAppPasswordCredentialTest extends TestCase
{
    #[Test]
    public function from_string_parses_user_colon_password(): void
    {
        $credential = WpAppPasswordCredential::fromString('admin:abcd efgh ijkl mnop qrst uvwx');

        $this->assertSame('admin', $credential->username());
        $this->assertSame(
            'Basic ' . base64_encode('admin:abcdefghijklmnopqrstuvwx'),
            $credential->authorizationHeader()
        );
    }

    #[Test]
    public function of_accepts_already_split_user_and_password(): void
    {
        $credential = WpAppPasswordCredential::of('editor', 'ZZZZ YYYY XXXX WWWW VVVV UUUU');

        $this->assertSame('editor', $credential->username());
        $this->assertSame(
            'Basic ' . base64_encode('editor:ZZZZYYYYXXXXWWWWVVVVUUUU'),
            $credential->authorizationHeader()
        );
    }

    #[Test]
    public function space_separated_and_packed_passwords_produce_identical_headers(): void
    {
        // Operators sometimes paste the spaces, sometimes not. Both
        // are valid inputs per WP's admin and must roundtrip to the
        // same Authorization header so imports don't 401 based on
        // how the user copied the password.
        $spaced = WpAppPasswordCredential::of('root', 'abcd efgh ijkl mnop qrst uvwx');
        $packed = WpAppPasswordCredential::of('root', 'abcdefghijklmnopqrstuvwx');

        $this->assertSame(
            $packed->authorizationHeader(),
            $spaced->authorizationHeader()
        );
    }

    #[Test]
    public function missing_colon_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WpAppPasswordCredential::fromString('no-colon-here');
    }

    #[Test]
    public function empty_raw_string_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WpAppPasswordCredential::fromString('');
    }

    #[Test]
    public function empty_username_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WpAppPasswordCredential::of('', 'abcd efgh ijkl mnop qrst uvwx');
    }

    #[Test]
    public function empty_secret_after_normalization_is_rejected(): void
    {
        // A password that is only whitespace collapses to empty after
        // normalization, so it must be rejected — otherwise callers
        // would silently send `Basic base64("user:")` which WP will
        // always 401 on.
        $this->expectException(InvalidArgumentException::class);
        WpAppPasswordCredential::of('admin', "   \t  \n ");
    }

    #[Test]
    public function colon_inside_password_half_is_preserved(): void
    {
        // Only the FIRST colon splits user from pass. Some WP-adjacent
        // plugins let operators mint passwords containing a colon;
        // the parser must not greedy-match on later colons.
        $credential = WpAppPasswordCredential::fromString('admin:secret:with:colons');

        $this->assertSame('admin', $credential->username());
        $this->assertSame(
            'Basic ' . base64_encode('admin:secret:with:colons'),
            $credential->authorizationHeader()
        );
    }
}
