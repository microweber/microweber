<?php

namespace Modules\Cloudflare\Helpers;

use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;

class CloudflareHelpers
{
    public static function fetchCloudFlareIps()
    {
        $ips = Cache::get('cloudflare_ips');
        if ($ips) {
            return $ips;
        }

        try {
            $guzzle = new Client();
            $return = $guzzle->get("https://api.cloudflare.com/client/v4/ips");
            if ($return->getStatusCode() == 200) {

                $list = @json_decode($return->getBody(), true);
                $ips = array_merge($list["result"]["ipv4_cidrs"], $list["result"]["ipv6_cidrs"]);

                Cache::put('cloudflare_ips', $ips, 60 * 60 * 24);

                return $ips;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return [];
        }
    }
}
