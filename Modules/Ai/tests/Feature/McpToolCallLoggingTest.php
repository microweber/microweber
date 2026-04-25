<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Modules\Ai\Models\McpClient;
use Modules\Ai\Services\Mcp\GeneratedMcpClientToken;
use Modules\Ai\Services\Mcp\McpClientTokenManager;
use PHPUnit\Framework\Attributes\Test;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

/**
 * Plan D.3 — pin the `mcp.tool.call` structured log line emitted
 * for every `tools/call` round-trip.
 *
 * Every tool call writes one log entry with (tool, duration_ms,
 * status, token_id, client_id) so operators get a per-tool
 * latency / success-rate signal without standing up Telescope or
 * OTel. A regression that drops the log call would silently leave
 * the server unobservable; this test fails loudly when that
 * happens.
 */
class McpToolCallLoggingTest extends TestCase
{
    private McpClient $client;
    private GeneratedMcpClientToken $token;

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

        DB::table('mcp_client_token_events')->delete();
        DB::table('mcp_client_tokens')->delete();
        DB::table('mcp_clients')->delete();

        config([
            'modules.ai.enabled' => true,
            'modules.ai.mcp.enabled' => true,
            'modules.ai.mcp.client_token_prefix' => 'mcp_',
            'modules.ai.mcp.auth.required_abilities' => ['mcp:access'],
            'modules.ai.mcp.auth.admin_scope' => 'mcp:admin',
            'modules.ai.mcp.auth.admin_only_tools' => [],
            'modules.ai.mcp.auth.admin_only_modules' => [],
            'modules.ai.mcp.log_channel' => 'stack',
        ]);

        /** @var McpClientTokenManager $manager */
        $manager = app(McpClientTokenManager::class);

        $this->client = $manager->createClient([
            'name' => 'Logging Test Client',
            'allowed_scopes' => ['mcp:access', 'mcp:admin'],
            'allowed_tools' => ['*'],
            'allowed_modules' => ['*'],
            'rate_limit_per_minute' => 600,
            'is_active' => true,
        ]);

        $this->token = $manager->issueToken(
            client: $this->client,
            name: 'Logging Test Token',
            abilities: ['mcp:access', 'mcp:admin'],
        );

        RateLimiter::clear('mcp-client-token:' . $this->token->token->id);
    }

    /**
     * Swap the configured channel's logger for a spying instance
     * so we can verify what was logged without piping through file
     * I/O.
     *
     * @return array<int, array{level: string, message: string, context: array<string, mixed>}>
     */
    private function captureLogs(callable $fn): array
    {
        $captured = [];
        $spy = new class($captured) implements LoggerInterface {
            public function __construct(private array &$captured) {}
            private function record(string $level, string $message, array $context): void
            {
                $this->captured[] = ['level' => $level, 'message' => $message, 'context' => $context];
            }
            public function emergency(string|\Stringable $message, array $context = []): void { $this->record('emergency', (string) $message, $context); }
            public function alert(string|\Stringable $message, array $context = []): void { $this->record('alert', (string) $message, $context); }
            public function critical(string|\Stringable $message, array $context = []): void { $this->record('critical', (string) $message, $context); }
            public function error(string|\Stringable $message, array $context = []): void { $this->record('error', (string) $message, $context); }
            public function warning(string|\Stringable $message, array $context = []): void { $this->record('warning', (string) $message, $context); }
            public function notice(string|\Stringable $message, array $context = []): void { $this->record('notice', (string) $message, $context); }
            public function info(string|\Stringable $message, array $context = []): void { $this->record('info', (string) $message, $context); }
            public function debug(string|\Stringable $message, array $context = []): void { $this->record('debug', (string) $message, $context); }
            public function log($level, string|\Stringable $message, array $context = []): void { $this->record((string) $level, (string) $message, $context); }
        };

        Log::shouldReceive('channel')->andReturn($spy);

        $fn();

        // Read the captured array out of the spy's by-reference
        // closure-bound property.
        return $captured;
    }

    #[Test]
    public function tool_call_emits_slow_warning_when_duration_exceeds_threshold(): void
    {
        // Threshold=1ms — every real tool call exceeds 1ms by far.
        config(['modules.ai.mcp.slow_tool_warn_ms' => 1]);

        $captured = $this->captureLogs(function () {
            $this->withHeader('Authorization', 'Bearer ' . $this->token->plainTextToken)
                ->postJson(route('api.ai.mcp'), [
                    'jsonrpc' => '2.0',
                    'id' => 'log-slow',
                    'method' => 'tools/call',
                    'params' => [
                        'name' => 'settings.read',
                        'arguments' => ['option_group' => 'website'],
                    ],
                ]);
        });

        $slowEntries = array_values(array_filter(
            $captured,
            fn (array $entry) => $entry['message'] === 'mcp.tool.slow'
        ));
        $this->assertCount(
            1,
            $slowEntries,
            'slow_tool_warn_ms=1 with a real tool dispatch must emit exactly one '
            . '`mcp.tool.slow` warning line — the operator-visible signal that a '
            . 'tool regressed past its expected latency.'
        );

        $entry = $slowEntries[0];
        $this->assertSame('warning', $entry['level']);
        $this->assertSame(1, $entry['context']['slow_threshold_ms']);
    }

    #[Test]
    public function tool_call_omits_slow_warning_when_threshold_is_zero(): void
    {
        // Threshold=0 disables the slow-warning branch entirely
        // (per the documented contract on the config key). A
        // regression that emits the warning regardless of the
        // threshold would inflate logs in environments that
        // explicitly opted out.
        config(['modules.ai.mcp.slow_tool_warn_ms' => 0]);

        $captured = $this->captureLogs(function () {
            $this->withHeader('Authorization', 'Bearer ' . $this->token->plainTextToken)
                ->postJson(route('api.ai.mcp'), [
                    'jsonrpc' => '2.0',
                    'id' => 'log-slow-disabled',
                    'method' => 'tools/call',
                    'params' => [
                        'name' => 'settings.read',
                        'arguments' => ['option_group' => 'website'],
                    ],
                ]);
        });

        $slowEntries = array_values(array_filter(
            $captured,
            fn (array $entry) => $entry['message'] === 'mcp.tool.slow'
        ));
        $this->assertSame([], $slowEntries);
    }

    #[Test]
    public function tool_call_emits_structured_log_line_with_duration_and_token_context(): void
    {
        $captured = $this->captureLogs(function () {
            $this->withHeader('Authorization', 'Bearer ' . $this->token->plainTextToken)
                ->postJson(route('api.ai.mcp'), [
                    'jsonrpc' => '2.0',
                    'id' => 'log-1',
                    'method' => 'tools/call',
                    'params' => [
                        'name' => 'settings.read',
                        'arguments' => ['option_group' => 'website'],
                    ],
                ]);
        });

        $toolCallEntries = array_values(array_filter(
            $captured,
            fn (array $entry) => $entry['message'] === 'mcp.tool.call'
        ));

        $this->assertCount(
            1,
            $toolCallEntries,
            'Plan D.3: every tools/call invocation must emit exactly one '
            . '`mcp.tool.call` log line through the configured channel. A '
            . 'regression that drops the log call would silently make the '
            . 'server unobservable; a regression that double-logs would '
            . 'inflate ingest costs.'
        );

        $entry = $toolCallEntries[0];
        $this->assertSame('info', $entry['level']);
        $context = $entry['context'];

        $this->assertSame('settings.read', $context['tool'] ?? null);
        $this->assertSame($this->token->token->id, $context['token_id'] ?? null);
        $this->assertSame($this->client->id, $context['client_id'] ?? null);
        $this->assertContains(
            $context['status'] ?? null,
            ['ok', 'error'],
            'Status must be one of {ok, error, exception}. ok/error are the '
            . 'common case; exception is reserved for the catch arm.'
        );
        $this->assertIsInt(
            $context['duration_ms'] ?? null,
            'duration_ms must be an integer milliseconds value — operators '
            . 'aggregate this as a histogram. A regression that emits a '
            . 'float or a string would break every downstream histogram.'
        );
        $this->assertGreaterThanOrEqual(
            0,
            $context['duration_ms'],
            'duration_ms cannot be negative — clock-skew protection.'
        );
    }
}
