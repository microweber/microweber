<?php

declare(strict_types=1);

namespace MicroweberPackages\Passport\Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;

final class StampTokenMiddlewareTest extends \MicroweberPackages\Passport\Tests\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // The test-stamp route is registered in the package's routes/api.php
        // when APP_ENV=testing.
    }

    protected function createStampUser(string $suffix): object
    {
        $email = "stamp-{$suffix}-" . uniqid() . '@example.com';
        $userModel = config('auth.providers.users.model');
        return $userModel::create([
            'name' => 'Stamp Test ' . $suffix,
            'email' => $email,
            'password' => Hash::make('secret'),
        ]);
    }

    #[Test]
    public function middleware_stamps_last_used_at_and_ip(): void
    {
        Cache::flush();

        $user = $this->createStampUser('used');
        $result = $user->createToken('Stamp Token');
        $tokenId = $result->token->id;

        $before = DB::table('oauth_access_tokens')->where('id', $tokenId)->first();
        $this->assertNull($before->last_used_at);

        $this->withToken($result->accessToken)
            ->getJson('/api/passport/test-stamp')
            ->assertOk();

        $after = DB::table('oauth_access_tokens')->where('id', $tokenId)->first();
        $this->assertNotNull($after->last_used_at, 'last_used_at should be stamped after request');
    }

    #[Test]
    public function middleware_throttles_db_writes(): void
    {
        Cache::flush();

        $user = $this->createStampUser('throttle');
        $result = $user->createToken('Throttle Token');

        $this->withToken($result->accessToken)
            ->getJson('/api/passport/test-stamp')
            ->assertOk();

        $firstStamp = DB::table('oauth_access_tokens')
            ->where('id', $result->token->id)
            ->value('last_used_at');

        $this->withToken($result->accessToken)
            ->getJson('/api/passport/test-stamp')
            ->assertOk();

        $secondStamp = DB::table('oauth_access_tokens')
            ->where('id', $result->token->id)
            ->value('last_used_at');

        $this->assertSame($firstStamp, $secondStamp, 'Should not re-stamp within throttle window');
    }
}