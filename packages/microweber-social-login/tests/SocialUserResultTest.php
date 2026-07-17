<?php

declare(strict_types=1);

namespace MicroweberPackages\SocialLogin\Tests;

use Laravel\Socialite\Two\User as SocialiteUser;
use MicroweberPackages\SocialLogin\Contracts\SocialUserResult;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase as BaseTestCase;

class SocialUserResultTest extends BaseTestCase
{
    #[Test]
    public function it_creates_from_socialite_user(): void
    {
        $socialiteUser = new SocialiteUser();
        $socialiteUser->id = '12345';
        $socialiteUser->email = 'test@example.com';
        $socialiteUser->name = 'John Doe';
        $socialiteUser->nickname = 'johndoe';
        $socialiteUser->avatar = 'https://example.com/avatar.jpg';

        $result = SocialUserResult::fromSocialiteUser('google', $socialiteUser);

        $this->assertEquals('google', $result->provider);
        $this->assertEquals('12345', $result->id);
        $this->assertEquals('test@example.com', $result->email);
        $this->assertEquals('John Doe', $result->name);
        $this->assertEquals('johndoe', $result->nickname);
        $this->assertEquals('https://example.com/avatar.jpg', $result->avatar);
        $this->assertEquals('John', $result->firstName);
        $this->assertEquals('Doe', $result->lastName);
    }

    #[Test]
    public function it_handles_single_name(): void
    {
        $socialiteUser = new SocialiteUser();
        $socialiteUser->id = '99';
        $socialiteUser->email = 'single@example.com';
        $socialiteUser->name = 'Madonna';
        $socialiteUser->nickname = null;
        $socialiteUser->avatar = null;

        $result = SocialUserResult::fromSocialiteUser('github', $socialiteUser);

        $this->assertEquals('Madonna', $result->firstName);
        $this->assertNull($result->lastName);
    }

    #[Test]
    public function it_handles_multi_part_last_name(): void
    {
        $socialiteUser = new SocialiteUser();
        $socialiteUser->id = '42';
        $socialiteUser->email = 'multi@example.com';
        $socialiteUser->name = 'John van der Berg';
        $socialiteUser->nickname = null;
        $socialiteUser->avatar = null;

        $result = SocialUserResult::fromSocialiteUser('facebook', $socialiteUser);

        $this->assertEquals('John', $result->firstName);
        $this->assertEquals('van der Berg', $result->lastName);
    }

    #[Test]
    public function it_handles_null_name(): void
    {
        $socialiteUser = new SocialiteUser();
        $socialiteUser->id = '1';
        $socialiteUser->email = 'noname@example.com';
        $socialiteUser->name = null;
        $socialiteUser->nickname = 'anon';
        $socialiteUser->avatar = null;

        $result = SocialUserResult::fromSocialiteUser('twitter', $socialiteUser);

        $this->assertNull($result->name);
        $this->assertNull($result->firstName);
        $this->assertNull($result->lastName);
        $this->assertEquals('anon', $result->nickname);
    }

    #[Test]
    public function it_stores_provider_correctly(): void
    {
        $socialiteUser = new SocialiteUser();
        $socialiteUser->id = '1';
        $socialiteUser->email = 'user@example.com';
        $socialiteUser->name = 'Test';
        $socialiteUser->nickname = null;
        $socialiteUser->avatar = null;

        $providers = ['facebook', 'google', 'github', 'twitter', 'linkedin', 'microweber'];

        foreach ($providers as $provider) {
            $result = SocialUserResult::fromSocialiteUser($provider, $socialiteUser);
            $this->assertEquals($provider, $result->provider);
        }
    }
}