<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Enforce email uniqueness at the DATABASE level.
 *
 * Until now uniqueness was only enforced in application code (UserManager::save
 * + User::validateAndFill), which a race condition or a save path that bypasses
 * validation (force_save, OAuth, raw inserts) could defeat. A legacy migration
 * declared `unique('email', 'users_email_index')` but inside a swallowing
 * try/catch, so it was never guaranteed — and on current databases no such index
 * exists.
 *
 * NULL emails are intentionally left alone: both MySQL and SQLite treat NULLs as
 * distinct under a UNIQUE index, so the username-only / OAuth accounts that have
 * no email are unaffected. Empty-string emails are normalised to NULL first so
 * they are exempt too (an empty string is NOT distinct under UNIQUE).
 *
 * Any pre-existing duplicate non-NULL emails (there should be none — app code has
 * enforced this for years) are de-duplicated before the index is added, by
 * plus-addressing all but the oldest row (foo@bar.com -> foo+dup<id>@bar.com) and
 * logging each change, so the migration never hard-fails and the original address
 * stays recoverable.
 */
return new class extends Migration
{
    private const INDEX_NAME = 'users_email_unique';

    private const LEGACY_INDEX_NAME = 'users_email_index';

    public function up(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasColumn('users', 'email')) {
            return;
        }

        // Already uniquely indexed (our index or the legacy one) — nothing to do.
        if (Schema::hasIndex('users', self::INDEX_NAME) || Schema::hasIndex('users', self::LEGACY_INDEX_NAME)) {
            return;
        }

        // Empty strings are not exempt from UNIQUE the way NULL is — normalise them.
        DB::table('users')->where('email', '')->update(['email' => null]);

        $this->resolveDuplicateEmails();

        Schema::table('users', function (Blueprint $table) {
            $table->unique('email', self::INDEX_NAME);
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        if (Schema::hasIndex('users', self::INDEX_NAME)) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique(self::INDEX_NAME);
            });
        }
    }

    /**
     * Make every non-NULL email unique so the index can be created. Keeps the
     * oldest row (lowest id) for each duplicated address untouched; rewrites the
     * rest to a unique plus-addressed variant and logs the change.
     */
    private function resolveDuplicateEmails(): void
    {
        $duplicateEmails = DB::table('users')
            ->select('email')
            ->whereNotNull('email')
            ->groupBy('email')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('email');

        foreach ($duplicateEmails as $email) {
            $ids = DB::table('users')
                ->where('email', $email)
                ->orderBy('id')
                ->pluck('id');

            // Keep the first (oldest) occurrence as-is.
            $ids->shift();

            foreach ($ids as $id) {
                $newEmail = $this->plusAddress($email, (int) $id);

                DB::table('users')->where('id', $id)->update(['email' => $newEmail]);

                Log::warning('[users_email_unique] De-duplicated conflicting email before adding the unique index.', [
                    'user_id' => $id,
                    'old_email' => $email,
                    'new_email' => $newEmail,
                ]);
            }
        }
    }

    /**
     * foo@bar.com -> foo+dup<id>@bar.com (recoverable; original is the part
     * before "+dup"). Falls back to a suffix when there is no "@".
     */
    private function plusAddress(string $email, int $id): string
    {
        $parts = explode('@', $email, 2);

        if (count($parts) === 2) {
            return $parts[0] . '+dup' . $id . '@' . $parts[1];
        }

        return $email . '+dup' . $id;
    }
};
