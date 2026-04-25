<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Modules\Ai\Models\McpClient;
use Modules\Ai\Models\McpClientToken;
use Modules\Ai\Services\Mcp\McpClientTokenManager;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Plan F.1 + F.2 — pin the artisan commands' golden-path behaviour.
 *
 *   - `ai:mcp:client:create` writes one mcp_clients + mcp_client_tokens
 *     row with the documented allow-list semantics, then prints the
 *     plain-text bearer token on stdout.
 *   - `ai:mcp:tools:list` enumerates the catalog (non-empty, headers
 *     correct, --module filter works).
 *
 * `ai:mcp:health` lives in a sibling smoke test that hits the live
 * dev server — it is not exercisable in PHPUnit because Http::post
 * to the local APP_URL would deadlock the test runner. The command
 * itself is wired identically to the other two so any registration
 * regression here surfaces it as well.
 */
class McpConsoleCommandsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('mcp_clients') || ! Schema::hasTable('mcp_client_tokens') || ! Schema::hasTable('mcp_client_token_events')) {
            DB::table('migrations')
                ->where('migration', '2026_04_13_184400_create_mcp_client_tables')
                ->delete();

            Artisan::call('migrate', [
                '--path' => base_path('Modules/Ai/database/migrations'),
                '--realpath' => true,
                '--force' => true,
            ]);
        }

        // Per-token rate-limit override migration — apply if the
        // tokens table exists but the column isn't there yet.
        if (Schema::hasTable('mcp_client_tokens') && ! Schema::hasColumn('mcp_client_tokens', 'rate_limit_per_minute')) {
            Artisan::call('migrate', [
                '--path' => base_path('Modules/Ai/database/migrations'),
                '--realpath' => true,
                '--force' => true,
            ]);
        }

        DB::table('mcp_client_token_events')->delete();
        DB::table('mcp_client_tokens')->delete();
        DB::table('mcp_clients')->delete();

        config([
            'modules.ai.mcp.client_token_prefix' => 'mcp_',
        ]);
    }

    #[Test]
    public function client_create_command_persists_a_client_with_a_usable_token(): void
    {
        $exitCode = Artisan::call('ai:mcp:client:create', [
            '--name' => 'CLI Test Client',
            '--scopes' => 'mcp:access,mcp:admin',
            '--tools' => '*',
            '--modules' => '*',
            '--rate-limit' => '120',
            '--token-name' => 'cli-test',
        ]);

        $this->assertSame(0, $exitCode, 'ai:mcp:client:create must exit 0 on success.');

        $output = Artisan::output();
        $this->assertStringContainsString('Created MCP client', $output);
        $this->assertStringContainsString('Token:', $output);

        // Extract the printed plain-text token and prove it
        // resolves through the manager.
        $this->assertMatchesRegularExpression(
            '/Token:\s+(mcp_\d+\|[A-Za-z0-9]+)/',
            $output,
            'Command output must contain the prefixed plain-text token in the documented format.'
        );
        preg_match('/Token:\s+(mcp_\d+\|[A-Za-z0-9]+)/', $output, $m);
        $plainTextToken = $m[1];

        /** @var McpClientTokenManager $manager */
        $manager = app(McpClientTokenManager::class);
        $resolved = $manager->findToken($plainTextToken);

        $this->assertInstanceOf(
            McpClientToken::class,
            $resolved,
            'McpClientTokenManager::findToken must resolve the freshly-printed token — '
            . 'otherwise the command UX is misleading because the operator captures '
            . 'a string that the live middleware will reject.'
        );

        $client = McpClient::where('name', 'CLI Test Client')->first();
        $this->assertNotNull($client);
        $this->assertSame(['*'], $client->allowed_tools);
        $this->assertSame(['*'], $client->allowed_modules);
        $this->assertSame(['mcp:access', 'mcp:admin'], $client->allowed_scopes);
        $this->assertSame(120, (int) $client->rate_limit_per_minute);
    }

    #[Test]
    public function client_create_command_persists_per_token_rate_limit_override(): void
    {
        $exitCode = Artisan::call('ai:mcp:client:create', [
            '--name' => 'Override Rate Client',
            '--scopes' => 'mcp:access',
            '--rate-limit' => '60',
            '--token-rate-limit' => '600',
        ]);
        $this->assertSame(0, $exitCode);

        $output = Artisan::output();
        $this->assertStringContainsString('Client rate:', $output);
        $this->assertStringContainsString('Token rate:', $output);
        $this->assertStringContainsString('600/min (token override)', $output);

        $token = \Modules\Ai\Models\McpClientToken::orderByDesc('id')->first();
        $this->assertNotNull($token);
        $this->assertSame(
            600,
            (int) $token->rate_limit_per_minute,
            'Per-token rate-limit override must persist on the tokens row — '
            . 'McpClientToken::effectiveRateLimitPerMinute() reads this column first '
            . 'and only falls back to the client-level value when the override is null. '
            . 'A regression that drops the column from the issue pipeline would silently '
            . 'collapse every issued token to the client-level limit.'
        );

        $this->assertSame(
            600,
            $token->effectiveRateLimitPerMinute(),
            'Effective rate limit must reflect the override — without this the '
            . 'AuthenticateMcpClient middleware would still apply the client-level '
            . 'limit, defeating the whole purpose of the override.'
        );
    }

    #[Test]
    public function token_default_ttl_applies_when_caller_does_not_pin_expiry(): void
    {
        config(['modules.ai.mcp.token_default_ttl_days' => 30]);

        Artisan::call('ai:mcp:client:create', [
            '--name' => 'TTL Default Client',
            '--scopes' => 'mcp:access',
        ]);

        $token = \Modules\Ai\Models\McpClientToken::orderByDesc('id')->first()->refresh();
        $this->assertNotNull(
            $token->expires_at,
            'With token_default_ttl_days=30 set, an issued token without an '
            . 'explicit --token-ttl-days flag must inherit a non-null expires_at — '
            . 'forever-tokens are now opt-in via AI_MCP_TOKEN_DEFAULT_TTL_DAYS=0.'
        );
        $this->assertEqualsWithDelta(
            now()->addDays(30)->getTimestamp(),
            $token->expires_at->getTimestamp(),
            5,
            'Default TTL must land within ~5s of now+30d (5s tolerance for the '
            . 'tiny gap between the helper now() in the production code and the '
            . 'now() here).'
        );
    }

    #[Test]
    public function token_default_ttl_zero_keeps_forever_tokens(): void
    {
        config(['modules.ai.mcp.token_default_ttl_days' => 0]);

        Artisan::call('ai:mcp:client:create', [
            '--name' => 'TTL Disabled Client',
            '--scopes' => 'mcp:access',
        ]);

        $token = \Modules\Ai\Models\McpClientToken::orderByDesc('id')->first()->refresh();
        $this->assertNull(
            $token->expires_at,
            'With token_default_ttl_days=0 set, the default-TTL branch must NOT '
            . 'apply — operators who explicitly opt out via the env var get '
            . 'forever-tokens back.'
        );
    }

    #[Test]
    public function token_without_override_inherits_client_rate_limit(): void
    {
        Artisan::call('ai:mcp:client:create', [
            '--name' => 'Inherit Rate Client',
            '--scopes' => 'mcp:access',
            '--rate-limit' => '120',
        ]);

        $token = \Modules\Ai\Models\McpClientToken::orderByDesc('id')->first()->refresh();

        $this->assertNull(
            $token->rate_limit_per_minute,
            'Without --token-rate-limit, the token row\'s override column must stay '
            . 'null so effectiveRateLimitPerMinute() falls back to the client-level '
            . 'value. A regression that copies the client-level limit into the column '
            . 'would defeat future client-level rate-limit changes (the operator would '
            . 'have to update every token row instead of just the parent client).'
        );
        $this->assertSame(
            120,
            $token->effectiveRateLimitPerMinute(),
            'Effective rate limit must fall back to the client-level value when the '
            . 'token-level override is null — the documented inheritance semantics.'
        );
    }

    #[Test]
    public function client_create_command_persists_null_allow_lists_when_options_are_omitted(): void
    {
        $exitCode = Artisan::call('ai:mcp:client:create', [
            '--name' => 'Unrestricted Client',
            '--scopes' => 'mcp:access',
        ]);

        $this->assertSame(0, $exitCode);

        $client = McpClient::where('name', 'Unrestricted Client')->first();
        $this->assertNotNull($client);
        $this->assertNull(
            $client->allowed_tools,
            'Omitting --tools must persist null (= unrestricted per the contract on '
            . 'McpClient::allowsValue) — otherwise CLI-created clients would silently '
            . 'collapse to deny-all and surprise the operator the same way the '
            . 'pre-fix Filament path did.'
        );
        $this->assertNull($client->allowed_modules);
    }

    #[Test]
    public function client_create_command_fails_without_a_name(): void
    {
        $exitCode = Artisan::call('ai:mcp:client:create');

        $this->assertSame(1, $exitCode);
        $this->assertStringContainsString('--name is required', Artisan::output());
    }

    #[Test]
    public function tools_list_command_renders_the_catalog_in_tabular_form(): void
    {
        $exitCode = Artisan::call('ai:mcp:tools:list');

        $this->assertSame(0, $exitCode);

        $output = Artisan::output();
        $this->assertStringContainsString('content.lookup', $output);
        $this->assertStringContainsString('billing.subscription_lookup', $output);
        $this->assertStringContainsString('tools registered', $output);
    }

    #[Test]
    public function tools_list_command_filters_by_module(): void
    {
        $exitCode = Artisan::call('ai:mcp:tools:list', ['--module' => 'analytics']);

        $this->assertSame(0, $exitCode);

        $output = Artisan::output();
        $this->assertStringContainsString('analytics.traffic_summary', $output);
        $this->assertStringContainsString('in module analytics', $output);

        // Negative — content tools must NOT leak into the analytics
        // filter result. A regression here would silently break the
        // operator's "give me only this module's tools" expectation.
        $this->assertStringNotContainsString('content.lookup', $output);
    }

    #[Test]
    public function token_rotate_command_revokes_old_and_issues_new_secret(): void
    {
        // Seed a client + initial token via the same code path the
        // create command uses — keeps this test honest end-to-end.
        Artisan::call('ai:mcp:client:create', [
            '--name' => 'Rotate Test Client',
            '--scopes' => 'mcp:access',
            '--tools' => '*',
            '--modules' => '*',
        ]);
        $createOutput = Artisan::output();
        preg_match('/Token id:\s+(\d+)/', $createOutput, $idMatch);
        preg_match('/Token:\s+(mcp_\d+\|[A-Za-z0-9]+)/', $createOutput, $tokenMatch);
        $tokenId = (int) ($idMatch[1] ?? 0);
        $oldPlainTextToken = $tokenMatch[1] ?? '';

        $this->assertGreaterThan(0, $tokenId, 'Rotation precondition: create must print a Token id.');
        $this->assertNotEmpty($oldPlainTextToken, 'Rotation precondition: create must print a Token.');

        // Sanity — old token resolves before rotation.
        $manager = app(McpClientTokenManager::class);
        $this->assertNotNull(
            $manager->findToken($oldPlainTextToken),
            'Pre-rotation precondition: the freshly-issued token must resolve through findToken.'
        );

        $exitCode = Artisan::call('ai:mcp:token:rotate', ['token-id' => (string) $tokenId]);
        $this->assertSame(0, $exitCode);

        $output = Artisan::output();
        $this->assertStringContainsString('Rotated token', $output);
        preg_match('/Token:\s+(mcp_\d+\|[A-Za-z0-9]+)/', $output, $newTokenMatch);
        $newPlainTextToken = $newTokenMatch[1] ?? '';

        $this->assertNotEmpty($newPlainTextToken, 'Rotate command must print the new plain-text token.');
        $this->assertNotSame(
            $oldPlainTextToken,
            $newPlainTextToken,
            'Rotation must produce a different secret — otherwise the command silently '
            . 'leaks the same token under a new id and the leak that prompted the '
            . 'rotation in the first place is not actually contained.'
        );

        // Old token row still resolves by token-id (findToken
        // doesn't filter revoked rows — that's the middleware's
        // job, see AuthenticateMcpClient::handle's isActive guard),
        // but `isRevoked()` MUST be true on the returned model so
        // the middleware rejects the leaked token.
        $oldResolved = $manager->findToken($oldPlainTextToken);
        $this->assertNotNull(
            $oldResolved,
            'Post-rotation lookup precondition: the row should still exist (rotation '
            . 'revokes; it does not delete) so the middleware can audit-log the '
            . 'denial reason.'
        );
        $this->assertTrue(
            $oldResolved->isRevoked(),
            'Post-rotation: the old token row must be marked revoked — otherwise the '
            . 'AuthenticateMcpClient middleware would still let leaked tokens through, '
            . 'defeating the entire purpose of rotating.'
        );

        // New token must resolve AND be active.
        $newResolved = $manager->findToken($newPlainTextToken);
        $this->assertNotNull(
            $newResolved,
            'Post-rotation: the new token must resolve through findToken — otherwise '
            . 'the operator just lost access to their own client.'
        );
        $this->assertFalse(
            $newResolved->isRevoked(),
            'Post-rotation: the new token must be active (not revoked).'
        );
    }

    #[Test]
    public function token_rotate_command_fails_for_unknown_token_id(): void
    {
        $exitCode = Artisan::call('ai:mcp:token:rotate', ['token-id' => '999999']);

        $this->assertSame(1, $exitCode);
        $this->assertStringContainsString('not found', Artisan::output());
    }

    #[Test]
    public function client_list_command_renders_clients_with_token_counts(): void
    {
        Artisan::call('ai:mcp:client:create', [
            '--name' => 'Listing Sample',
            '--scopes' => 'mcp:access',
        ]);

        $exitCode = Artisan::call('ai:mcp:client:list');
        $this->assertSame(0, $exitCode);

        $output = Artisan::output();
        $this->assertStringContainsString('Listing Sample', $output);
        $this->assertStringContainsString(
            '1/1',
            $output,
            'client:list must render the active/total token count column. The created '
            . 'client has exactly one token (the initial issuance), so the column '
            . 'should report 1/1. A regression in the count subqueries would surface '
            . 'as 0/0 (silently breaking the operator-side overview).'
        );
    }

    #[Test]
    public function client_list_command_warns_on_empty_inventory(): void
    {
        // No clients in setUp's truncated tables — the command must
        // emit a clear remediation hint, not a silent empty table.
        $exitCode = Artisan::call('ai:mcp:client:list');

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('No MCP clients registered', Artisan::output());
    }

    #[Test]
    public function token_revoke_command_marks_the_row_revoked(): void
    {
        Artisan::call('ai:mcp:client:create', [
            '--name' => 'Revoke Test Client',
            '--scopes' => 'mcp:access',
        ]);
        $createOutput = Artisan::output();
        preg_match('/Token id:\s+(\d+)/', $createOutput, $idMatch);
        preg_match('/Token:\s+(mcp_\d+\|[A-Za-z0-9]+)/', $createOutput, $tokenMatch);
        $tokenId = (int) ($idMatch[1] ?? 0);
        $plainTextToken = $tokenMatch[1] ?? '';

        $manager = app(McpClientTokenManager::class);
        $this->assertNotNull($manager->findToken($plainTextToken));

        $exitCode = Artisan::call('ai:mcp:token:revoke', [
            'token-id' => (string) $tokenId,
            '--reason' => 'pin test',
        ]);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Revoked token', Artisan::output());

        $resolved = $manager->findToken($plainTextToken);
        $this->assertNotNull(
            $resolved,
            'Post-revoke: the row must still resolve so the middleware can audit-log '
            . 'the denial reason on any leaked-token reuse — revocation is logical, '
            . 'not physical deletion.'
        );
        $this->assertTrue(
            $resolved->isRevoked(),
            'Post-revoke: isRevoked() must return true so AuthenticateMcpClient '
            . 'rejects further use of the leaked token.'
        );
    }

    #[Test]
    public function token_revoke_command_is_idempotent_on_already_revoked(): void
    {
        Artisan::call('ai:mcp:client:create', [
            '--name' => 'Idempotent Revoke Client',
            '--scopes' => 'mcp:access',
        ]);
        preg_match('/Token id:\s+(\d+)/', Artisan::output(), $idMatch);
        $tokenId = (int) ($idMatch[1] ?? 0);

        Artisan::call('ai:mcp:token:revoke', ['token-id' => (string) $tokenId]);

        $exitCode = Artisan::call('ai:mcp:token:revoke', ['token-id' => (string) $tokenId]);
        $this->assertSame(
            0,
            $exitCode,
            'Re-revoking an already-revoked token must succeed (the operator does '
            . 'not need to know whether the token was already revoked) and emit a '
            . 'descriptive warning, not an error. A regression that fails the second '
            . 'invocation would surface as a confusing CI failure for any rotation '
            . 'script that sweeps tokens periodically.'
        );
        $this->assertStringContainsString('already revoked', Artisan::output());
    }

    private function seedClient(string $name = 'Audit Seed Client'): \Modules\Ai\Models\McpClient
    {
        Artisan::call('ai:mcp:client:create', [
            '--name' => $name,
            '--scopes' => 'mcp:access',
        ]);

        $client = \Modules\Ai\Models\McpClient::where('name', $name)->firstOrFail();

        // Bump the create event's timestamp to "now" so the only
        // pre-existing event row doesn't trip the prune logic.
        DB::table('mcp_client_token_events')
            ->where('mcp_client_id', $client->id)
            ->update(['created_at' => now(), 'updated_at' => now()]);

        return $client;
    }

    #[Test]
    public function prune_audit_command_dry_runs_without_writing(): void
    {
        $client = $this->seedClient('Audit Dry Run Client');

        // Seed an old event row by hand so the cutoff actually
        // catches something. Created_at is a datetime — set it 100
        // days in the past so a default --older-than=90 catches it.
        DB::table('mcp_client_token_events')->insert([
            'mcp_client_id' => $client->id,
            'mcp_client_token_id' => null,
            'action' => 'token.used',
            'metadata' => json_encode(['source' => 'pin']),
            'created_at' => now()->subDays(100),
            'updated_at' => now()->subDays(100),
        ]);

        $beforeCount = (int) DB::table('mcp_client_token_events')->count();
        $exitCode = Artisan::call('ai:mcp:prune-audit', ['--dry-run' => true]);
        $this->assertSame(0, $exitCode);

        $output = Artisan::output();
        $this->assertStringContainsString('Dry run', $output);
        $this->assertStringContainsString('1 mcp_client_token_events row', $output);

        // Row must still exist (dry-run guarantees zero deletes).
        $this->assertSame(
            $beforeCount,
            (int) DB::table('mcp_client_token_events')->count(),
            'Dry-run must not delete anything — the audit-log table count must be '
            . 'unchanged after the command exits. A regression that secretly deletes '
            . 'on dry-run would silently break the audit trail.'
        );
    }

    #[Test]
    public function prune_audit_command_deletes_rows_older_than_horizon(): void
    {
        $client = $this->seedClient('Audit Real Run Client');

        // One old, one fresh — only the old one should be deleted.
        DB::table('mcp_client_token_events')->insert([
            [
                'mcp_client_id' => $client->id,
                'mcp_client_token_id' => null,
                'action' => 'token.used',
                'metadata' => json_encode(['age' => 'old']),
                'created_at' => now()->subDays(100),
                'updated_at' => now()->subDays(100),
            ],
            [
                'mcp_client_id' => $client->id,
                'mcp_client_token_id' => null,
                'action' => 'token.used',
                'metadata' => json_encode(['age' => 'fresh']),
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
        ]);

        $beforeCount = (int) DB::table('mcp_client_token_events')->count();
        $exitCode = Artisan::call('ai:mcp:prune-audit', ['--older-than' => '90']);
        $this->assertSame(0, $exitCode);

        $output = Artisan::output();
        $this->assertStringContainsString('Pruned 1', $output);
        $this->assertSame(
            $beforeCount - 1,
            (int) DB::table('mcp_client_token_events')->count(),
            'Prune must keep rows newer than the cutoff — exactly 1 old row should '
            . 'be deleted, the rest (the fresh one + the create-client event from '
            . 'seedClient()) must survive. A regression that deletes everything '
            . 'would erase recent operator-visible audit context.'
        );
    }
}
