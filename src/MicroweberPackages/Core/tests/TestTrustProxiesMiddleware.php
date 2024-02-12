<?php

namespace MicroweberPackages\Core\tests;


/**
 * @runTestsInSeparateProcesses
 */
class TestTrustProxiesMiddleware extends TestCase
{
    public function testIfThrottleMiddlewareWorks()
    {
        save_option('trust_proxies', '', 'website');
        $res = \Artisan::call('cache:clear');

        $this->assertEquals(0, $res);

        $resq = range(1, 10);
        foreach ($resq as $i) {
            $this->get('/example-route-test-throttle-middleware')
                ->assertOk()
                ->assertHeader('X-Ratelimit-Remaining', 10 - $i);
        }
        $this->get('/example-route-test-throttle-middleware')
            ->assertStatus(429);

    }

    public function testIfCanBypassThrottleBySettingXForwardedFor()
    {
        save_option('trust_proxies', '', 'website');
        $option = get_option('trust_proxies', 'website');
        $this->assertEquals('', $option);
        $res = \Artisan::call('cache:clear');
        $this->assertEquals(0, $res);

        $resq = range(1, 10);
        foreach ($resq as $i) {

            $res = $this->get('/example-route-test-throttle-middleware', ['X-FORWARDED-FOR' => '1.2.3.4'])
                ->assertOk()
                ->assertHeader('X-Ratelimit-Remaining', 10 - $i);

            $resData = json_decode($res->getContent(), true);
            $this->assertEquals(1, $resData['ok']);
            $this->assertEquals('127.0.0.1', $resData['ip']);

        }

        $this->get('/example-route-test-throttle-middleware', ['X-FORWARDED-FOR' => '1.2.3.6'])
            ->assertStatus(429);

        $this->get('/example-route-test-throttle-middleware', ['X-FORWARDED-FOR' => '2.2.3.6'])
            ->assertStatus(429);
    }

    public function testIfIpIsCorrectBySettingTrustProxies()
    {

        save_option('trust_proxies', '127.0.0.1', 'website');

        $res = \Artisan::call('config:clear');

        $res = \Artisan::call('cache:clear');

        $this->assertEquals(0, $res);



        $resq = range(1, 10);
        foreach ($resq as $i) {
            $this->get('/example-route-test-throttle-middleware')
                ->assertOk()
                ->assertHeader('X-Ratelimit-Remaining', 10 - $i);
        }

        $this->get('/example-route-test-throttle-middleware')
            ->assertStatus(429);


        $headers = [
            'X-Forwarded-For' => '1.2.3.4',
        ];
        $server['REMOTE_ADDR'] = '127.0.0.1';

        $server = $this->transformHeadersToServerVars($headers);
        $cookies = $this->prepareCookiesForRequest();
        $uri = '/example-route-test-throttle-middleware';
        $res =  $this->call('GET', $uri, [], $cookies, [], $server);
        $res
            ->assertOk()
            ->assertHeader('X-Ratelimit-Remaining', 9);

        $resData = json_decode($res->getContent(), true);
        $this->assertEquals(1, $resData['ok']);
        $this->assertEquals('1.2.3.4', $resData['ip']);



    }

}
