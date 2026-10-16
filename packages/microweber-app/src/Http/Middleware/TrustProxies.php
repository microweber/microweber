<?php

namespace MicroweberPackages\App\Http\Middleware;

use \Illuminate\Http\Middleware\TrustProxies as TrustProxiesMiddleware;
use Illuminate\Http\Request;


class TrustProxies extends TrustProxiesMiddleware
{
    public function handle($request, \Closure $next)
    {

        if (mw_is_installed()) {

            event_trigger('mw.trust_proxies');


            $proxy_ips = [];
            $trust_proxies = get_option('trust_proxies', 'website');
            if ($trust_proxies) {
                $trust_proxies = explode("\n", $trust_proxies);
                if (!empty($trust_proxies)) {
                    foreach ($trust_proxies as $trust_proxy) {
                        $trust_proxy = trim($trust_proxy);
                        if ($trust_proxy) {
                            if (filter_var($trust_proxy, FILTER_VALIDATE_IP)) {
                                $proxy_ips[] = $trust_proxy;
                            } elseif (strpos($trust_proxy, '/') !== false) {
                                list($subnet, $mask) = explode('/', $trust_proxy);
                                if ($this->isValidSubnet($subnet, $mask)) {
                                    $proxy_ips[] = $trust_proxy;
                                }
                            }
                        }
                    }
                }
            }
            if($proxy_ips) {
                $this->proxies = $proxy_ips;
            }
            return parent::handle($request, $next);

        }
        return parent::handle($request, $next);

    }

    private function isValidSubnet($subnet, $mask)
    {
        if (filter_var($subnet, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return $mask >= 0 && $mask <= 32;
        } elseif (filter_var($subnet, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return $mask >= 0 && $mask <= 128;
        }
        return false;
    }
}
