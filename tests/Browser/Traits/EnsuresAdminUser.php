<?php

namespace Tests\Browser\Traits;

use Illuminate\Support\Facades\Hash;
use MicroweberPackages\User\Models\User;

/**
 * Ensures the canonical Dusk admin account exists before a test logs in, so the
 * browser suite is self-provisioning on a fresh database.
 *
 * Credentials default to admin@admin.com / admin and are overridable via the
 * DUSK_ADMIN_EMAIL / DUSK_ADMIN_PASSWORD env knobs (the same values
 * AdminLoginTrait logs in with). This replaces the per-test private
 * ensureAdminUser() copies that used to be duplicated across the suite.
 */
trait EnsuresAdminUser
{
    protected function ensureAdminUser(): void
    {
        $email = (string) env('DUSK_ADMIN_EMAIL', 'admin@admin.com');
        $password = (string) env('DUSK_ADMIN_PASSWORD', 'admin');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = new User();
            $user->email = $email;
            $user->username = 'admin';
            $user->password = Hash::make($password);
            $user->is_admin = 1;
            $user->is_active = 1;
            $user->is_verified = 1;
            $user->first_name = 'Admin';
            $user->last_name = 'User';
            $user->save();

            return;
        }

        $dirty = false;
        if ((int) $user->is_admin !== 1) { $user->is_admin = 1; $dirty = true; }
        if ((int) $user->is_active !== 1) { $user->is_active = 1; $dirty = true; }
        if ($dirty) { $user->save(); }
    }
}
