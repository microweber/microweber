<?php

namespace MicroweberPackages\User\tests;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Pins the DB-level `users_email_unique` index added by
 * 2026_10_22_000001_add_unique_index_to_users_email: duplicate non-NULL emails
 * must be rejected by the database (not just application code), while NULL
 * emails (OAuth / username-only accounts) stay exempt.
 */
class UserEmailUniqueIndexTest extends TestCase
{
    #[Test]
    public function duplicate_non_null_emails_are_rejected_by_the_database(): void
    {
        $email = 'dup_index_' . uniqid() . '@example.test';

        DB::table('users')->insert([
            'email' => $email,
            'username' => 'u1_' . uniqid(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->expectException(QueryException::class);

        // A second row with the same email must violate the unique index.
        DB::table('users')->insert([
            'email' => $email,
            'username' => 'u2_' . uniqid(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    #[Test]
    public function multiple_null_emails_are_allowed(): void
    {
        $before = DB::table('users')->whereNull('email')->count();

        foreach (range(1, 3) as $i) {
            DB::table('users')->insert([
                'email' => null,
                'username' => 'null_email_' . $i . '_' . uniqid(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->assertSame(
            $before + 3,
            DB::table('users')->whereNull('email')->count(),
            'NULL emails must be exempt from the unique index.'
        );
    }
}
