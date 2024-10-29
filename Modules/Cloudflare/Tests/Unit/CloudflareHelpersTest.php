<?php

namespace Modules\Cloudflare\Tests\Unit;

use Illuminate\Support\Facades\Cache;
use Modules\Cloudflare\Helpers\CloudflareHelpers;
use Tests\TestCase;

class CloudflareHelpersTest extends TestCase
{
    public function testFetchCloudFlareIps()
    {
        $cachedIps = ['192.0.2.0/24', '2001:db8::/32'];
        Cache::put('cloudflare_ips', $cachedIps, 60 * 60 * 24);

        $ipsFromCache = CloudflareHelpers::fetchCloudFlareIps();

        $this->assertEquals($cachedIps, $ipsFromCache);

        Cache::forget('cloudflare_ips');

        $ipsFromApi = CloudflareHelpers::fetchCloudFlareIps();

        $this->assertNotEmpty($ipsFromApi);
        $this->assertIsArray($ipsFromApi);
        $this->assertTrue(Cache::has('cloudflare_ips'));

    }


}
