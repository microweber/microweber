<?php

declare(strict_types=1);

namespace MicroweberPackages\SocialLogin\Tests;

use Illuminate\Http\Request;
use Illuminate\Session\ArraySessionHandler;
use Illuminate\Session\Store;
use MicroweberPackages\SocialLogin\Providers\MicroweberSocialiteProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase as BaseTestCase;

class MicroweberSocialiteProviderTest extends BaseTestCase
{
    private function makeProvider(string $serverUrl = 'https://mwlogin.com'): MicroweberSocialiteProvider
    {
        $request = Request::create('/');
        $request->setLaravelSession(new Store('test', new ArraySessionHandler(60)));

        $provider = new MicroweberSocialiteProvider(
            $request,
            'test-client-id',
            'test-client-secret',
            'https://example.com/callback'
        );

        $provider->setServerUrl($serverUrl);
        $provider->stateless();

        return $provider;
    }

    #[Test]
    public function redirect_url_points_to_server(): void
    {
        $provider = $this->makeProvider('https://custom-mw.com');

        $redirectResponse = $provider->redirect();
        $url = $redirectResponse->getTargetUrl();

        $this->assertStringContainsString('https://custom-mw.com/oauth/authorize', $url);
        $this->assertStringContainsString('client_id=test-client-id', $url);
        $this->assertStringContainsString('redirect_uri=', $url);
    }

    #[Test]
    public function set_server_url_strips_trailing_slash(): void
    {
        $provider = $this->makeProvider('https://example.com/');

        $redirectResponse = $provider->redirect();
        $url = $redirectResponse->getTargetUrl();

        // Should not have double slashes
        $this->assertStringContainsString('https://example.com/oauth/authorize', $url);
        $this->assertStringNotContainsString('https://example.com//oauth', $url);
    }

    #[Test]
    public function default_server_url_is_mwlogin(): void
    {
        $request = Request::create('/');
        $request->setLaravelSession(new Store('test', new ArraySessionHandler(60)));

        $provider = new MicroweberSocialiteProvider(
            $request,
            'id',
            'secret',
            'https://example.com/callback'
        );

        $provider->stateless();

        $redirectResponse = $provider->redirect();
        $url = $redirectResponse->getTargetUrl();

        $this->assertStringContainsString('https://mwlogin.com/oauth/authorize', $url);
    }
}