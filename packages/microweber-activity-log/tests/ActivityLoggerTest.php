<?php

declare(strict_types=1);

namespace MicroweberPackages\ActivityLog\Tests;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\ActivityLog\Services\ActivityLogger;
use PHPUnit\Framework\Attributes\Test;

class ActivityLoggerTest extends TestCase
{
    #[Test]
    public function record_login_writes_an_audit_row_for_the_user(): void
    {
        $user = (object) ['id' => 42, 'email' => 'owner@example.com'];

        ActivityLogger::recordLogin($user, ['via' => 'password']);

        $row = DB::table('activity_log')->where('action', 'auth.login')->first();

        $this->assertNotNull($row, 'recordLogin() should insert a row.');
        $this->assertSame(42, (int) $row->user_id);
        $this->assertSame('owner@example.com', $row->actor_email);
        $this->assertStringContainsString('"via":"password"', $row->metadata);
    }

    #[Test]
    public function record_failed_login_stores_attempted_email_without_user(): void
    {
        ActivityLogger::recordFailedLogin('attacker@example.com');

        $row = DB::table('activity_log')->where('action', 'auth.login_failed')->first();

        $this->assertNotNull($row, 'recordFailedLogin() should insert a row.');
        $this->assertNull($row->user_id, 'A failed login has no authenticated user.');
        $this->assertSame('attacker@example.com', $row->actor_email);
    }

    #[Test]
    public function record_resolves_entity_key_via_get_key_method(): void
    {
        $user = new class {
            public function getKey(): int
            {
                return 7;
            }

            public string $email = 'keyed@example.com';
        };

        ActivityLogger::record('content.updated', $user);

        $row = DB::table('activity_log')->where('action', 'content.updated')->first();

        $this->assertNotNull($row);
        $this->assertSame(7, (int) $row->user_id, 'getKey() should resolve the user id.');
        $this->assertSame('keyed@example.com', $row->actor_email);
    }
}
