<?php

event_bind('mw.trust_proxies', function () {

    if (request()->header('cdn-loop') == 'cloudflare') {
//        if (\Symfony\Component\HttpFoundation\IpUtils::checkIp(request()->ip(), fetchCloudFlareIps())) {
//            request()->server->add([
//                'ORIGINAL_REMOTE_ADDR' => request()->ip(),
//                'REMOTE_ADDR' => filter_var(request()->header('HTTP_CF_CONNECTING_IP'),
//                    FILTER_VALIDATE_IP) ?: request()->ip()
//            ]);
//        }
        \Illuminate\Support\Facades\Config::set('trustedproxy.proxies', fetchCloudFlareIps());
    }

});

function fetchCloudFlareIps()
{
    $ips = Cache::get('cloudflare_ips');
    if ($ips) {
        return $ips;
    }

    $guzzle = new \GuzzleHttp\Client();
    $return = $guzzle->get("https://api.cloudflare.com/client/v4/ips");
    if ($return->getStatusCode() == 200) {

        $list = json_decode($return->getBody(), true);
        $ips = array_merge($list["result"]["ipv4_cidrs"], $list["result"]["ipv6_cidrs"]);

        \Illuminate\Support\Facades\Cache::put('cloudflare_ips', $ips, 60 * 60 * 24);

        return $ips;
    } else {
        return [];
    }
}
