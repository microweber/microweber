<?php

declare(strict_types=1);

namespace MicroweberPackages\Passport\Tests\Feature;

use Laravel\Passport\Passport;
use PHPUnit\Framework\Attributes\Test;

final class ServiceProviderTest extends \MicroweberPackages\Passport\Tests\TestCase
{
    #[Test]
    public function passport_service_provider_is_registered(): void
    {
        $this->assertTrue(
            $this->app->providerIsLoaded(\Laravel\Passport\PassportServiceProvider::class),
            'PassportServiceProvider should be registered by MicroweberPassportServiceProvider'
        );
    }

    #[Test]
    public function rsa_keys_are_generated_on_boot(): void
    {
        $this->assertFileExists(storage_path('oauth-public.key'));
        $this->assertFileExists(storage_path('oauth-private.key'));
    }

    #[Test]
    public function passport_scopes_are_configured(): void
    {
        $scopes = Passport::scopes();

        $this->assertNotEmpty($scopes, 'Passport scopes should be configured');
        $this->assertTrue(
            $scopes->contains(fn ($s) => $s->id === 'content:read'),
            'content:read scope should exist'
        );
    }

    #[Test]
    public function config_values_are_loaded(): void
    {
        $this->assertNotNull(config('microweber-passport.tokens_expire_days'));
        $this->assertNotNull(config('microweber-passport.scopes'));
    }

    #[Test]
    public function oauth_tables_exist(): void
    {
        $this->assertTrue(\Schema::hasTable('oauth_access_tokens'));
        $this->assertTrue(\Schema::hasTable('oauth_clients'));
        $this->assertTrue(\Schema::hasTable('oauth_refresh_tokens'));
    }

    #[Test]
    public function custom_columns_exist(): void
    {
        $this->assertTrue(\Schema::hasColumn('oauth_access_tokens', 'last_used_at'));
        $this->assertTrue(\Schema::hasColumn('oauth_access_tokens', 'last_used_ip'));
    }

    #[Test]
    public function personal_access_client_is_auto_created(): void
    {
        $provider = config('auth.guards.api.provider', 'users');

        $exists = \DB::table('oauth_clients')
            ->whereJsonContains('grant_types', 'personal_access')
            ->where('provider', $provider)
            ->where('revoked', false)
            ->exists();

        $this->assertTrue($exists, 'Personal access client should be auto-created');
    }
}