<?php

event_bind('mw.trust_proxies', function () {

    if (request()->header('cdn-loop') == 'cloudflare') {
        //check if the user has set in config
        $check = \Illuminate\Support\Facades\Config::get('trustedproxy.proxies');
        if ($check) {
            return;
        }
        \Illuminate\Support\Facades\Config::set('trustedproxy.proxies', fetchCloudFlareIps());

    }

});

function fetchCloudFlareIps()
{
    $ips = \Illuminate\Support\Facades\Cache::get('cloudflare_ips');
    if ($ips) {
        return $ips;
    }

    try {
        $guzzle = new \GuzzleHttp\Client();
        $return = $guzzle->get("https://api.cloudflare.com/client/v4/ips");
        if ($return->getStatusCode() == 200) {

            $list = @json_decode($return->getBody(), true);
            $ips = array_merge($list["result"]["ipv4_cidrs"], $list["result"]["ipv6_cidrs"]);

            \Illuminate\Support\Facades\Cache::put('cloudflare_ips', $ips, 60 * 60 * 24);

            return $ips;
        } else {
            return [];
        }
    } catch (\Exception $e) {
        return [];
    }


}
