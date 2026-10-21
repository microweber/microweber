<?php

namespace Modules\Vendors\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Vendors\Models\Vendor;

/**
 * Small coordinator for the vendor lifecycle. One place that answers
 * "is this user a vendor?" and "give me this user's vendor profile",
 * so the gate logic is not copy-pasted across the Filament pages.
 */
class VendorManager
{
    /** The Spatie role that marks a user as a vendor. */
    public const ROLE = 'vendor';

    /**
     * Ensure the `vendor` role exists so it shows up in the admin
     * Users → Roles multiselect. Idempotent + crash-safe on first boot
     * (before the roles table migration has run).
     */
    public function ensureRole(): void
    {
        try {
            \MicroweberPackages\Role\Models\Role::firstOrCreate(
                ['name' => self::ROLE],
                ['guard_name' => 'web']
            );
        } catch (\Throwable $e) {
            // roles table not migrated yet / DB unavailable during install.
        }
    }

    /**
     * Is the given (or current) user flagged as a vendor?
     *
     * Checks the roles relation by name directly rather than Spatie's
     * guard-aware hasRole(): the configured auth model (App\Models\User)
     * can differ from the concrete User class, which makes Spatie's guard
     * resolution brittle. Membership by role name is what we actually mean.
     */
    public function isVendor(?Authenticatable $user = null): bool
    {
        $user = $user ?: auth()->user();
        if (! $user) {
            return false;
        }

        try {
            if (method_exists($user, 'roles')) {
                return $user->roles()->where('name', self::ROLE)->exists();
            }
            return method_exists($user, 'hasRole') && $user->hasRole(self::ROLE);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Fetch (creating if missing) the vendor profile for a user. Called
     * lazily when a vendor first opens their profile pages so marking a
     * user as vendor in admin is all that's needed — no separate step.
     */
    public function profileFor(Authenticatable $user): Vendor
    {
        $userId = (int) $user->getAuthIdentifier();

        return Vendor::firstOrCreate(
            ['user_id' => $userId],
            [
                'shop_name' => $this->defaultShopName($user),
                'is_approved' => true,
            ]
        );
    }

    protected function defaultShopName(Authenticatable $user): string
    {
        $username = $user->username ?? null;
        $name = $user->first_name ?? null;
        return (string) ($name ?: $username ?: ('Vendor ' . $user->getAuthIdentifier()));
    }
}
