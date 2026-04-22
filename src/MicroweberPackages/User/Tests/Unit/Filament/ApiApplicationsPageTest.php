<?php

declare(strict_types=1);

namespace MicroweberPackages\User\Tests\Unit\Filament;

use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use MicroweberPackages\User\Filament\Pages\ApiApplicationsPage;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

/**
 * Exercises the API Applications Filament page, focused on the
 * "Revoke all tokens" bulk action that the page exposes at the top of
 * the personal-access-tokens table.
 *
 * The revokeAllPersonalTokens() Livewire action must:
 *   - flip every non-revoked oauth_access_token owned by the caller
 *   - flip the matching oauth_refresh_tokens to revoked
 *   - leave tokens owned by other users untouched
 *   - leave already-revoked tokens alone and not re-touch them
 *   - no-op cleanly (no SQL error, no notification spam) when there
 *     are zero active tokens
 */
final class ApiApplicationsPageTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function revoke_all_tokens_revokes_every_token_owned_by_caller(): void
    {
        $user = auth()->user();

        $token1 = $user->createToken('Token A')->token;
        $token2 = $user->createToken('Token B', ['content:read'])->token;
        $token3 = $user->createToken('Token C', ['products:write'])->token;

        DB::table('oauth_refresh_tokens')->insert([
            'id' => 'refresh-' . $token1->id,
            'access_token_id' => $token1->id,
            'revoked' => false,
            'expires_at' => now()->addDays(30),
        ]);

        Livewire::test(ApiApplicationsPage::class)
            ->call('revokeAllPersonalTokens')
            ->assertSuccessful();

        $this->assertTrue($token1->fresh()->revoked, 'Token A should be revoked');
        $this->assertTrue($token2->fresh()->revoked, 'Token B should be revoked');
        $this->assertTrue($token3->fresh()->revoked, 'Token C should be revoked');

        $this->assertTrue(
            (bool) DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $token1->id)
                ->value('revoked'),
            'Refresh token attached to Token A should also be revoked'
        );
    }

    #[Test]
    public function revoke_all_tokens_does_not_touch_tokens_owned_by_other_users(): void
    {
        $me = auth()->user();
        $someoneElse = User::factory()->create([
            'email' => 'other-' . uniqid() . '@example.com',
        ]);

        $myToken = $me->createToken('Mine')->token;
        $theirToken = $someoneElse->createToken('Theirs')->token;

        Livewire::test(ApiApplicationsPage::class)
            ->call('revokeAllPersonalTokens')
            ->assertSuccessful();

        $this->assertTrue($myToken->fresh()->revoked);
        $this->assertFalse(
            $theirToken->fresh()->revoked,
            'Revoking all of my tokens must not affect another user\'s tokens'
        );
    }

    #[Test]
    public function revoke_all_tokens_is_a_noop_when_there_are_zero_active_tokens(): void
    {
        // No tokens created for the current user.
        $this->assertSame(0, auth()->user()->tokens()->where('revoked', false)->count());

        Livewire::test(ApiApplicationsPage::class)
            ->call('revokeAllPersonalTokens')
            ->assertSuccessful();
    }

    #[Test]
    public function revoke_all_tokens_ignores_already_revoked_tokens(): void
    {
        $user = auth()->user();

        $active = $user->createToken('Still active')->token;
        $alreadyRevoked = $user->createToken('Already revoked')->token;
        $alreadyRevoked->revoke();

        $revokedAtBefore = DB::table('oauth_access_tokens')
            ->where('id', $alreadyRevoked->id)
            ->value('updated_at');

        Livewire::test(ApiApplicationsPage::class)
            ->call('revokeAllPersonalTokens')
            ->assertSuccessful();

        $this->assertTrue($active->fresh()->revoked);

        $revokedAtAfter = DB::table('oauth_access_tokens')
            ->where('id', $alreadyRevoked->id)
            ->value('updated_at');

        $this->assertSame(
            $revokedAtBefore,
            $revokedAtAfter,
            'Already-revoked tokens must not be re-touched (updated_at unchanged)'
        );
    }

    #[Test]
    public function revoke_all_tokens_renders_button_in_the_page(): void
    {
        $user = auth()->user();
        $user->createToken('Visible Token');

        Livewire::test(ApiApplicationsPage::class)
            ->assertSuccessful()
            ->assertSee('Revoke all tokens')
            ->assertSee('1 active token');
    }
}
