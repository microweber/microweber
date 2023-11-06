<?php
namespace MicroweberPackages\SiteStats;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;


class UtmVisitorData
{
    public static function setVisitorData($visitorData)
    {
        $visitorData = json_encode($visitorData);
        $visitorData = base64_encode($visitorData);
        $visitorData = \Crypt::encryptString($visitorData);

        setcookie('_mw_stats_visitor_data', $visitorData, time() + (86400 * 30), "/");
        $_COOKIE['_mw_stats_visitor_data'] = $visitorData;
        \Cookie::queue('_mw_stats_visitor_data', $visitorData, 86400 * 30);

    }

    public static function getVisitorData()
    {
        if (isset($_COOKIE['_mw_stats_visitor_data'])) {
            $visitorData = $_COOKIE['_mw_stats_visitor_data'];

            try {
                $visitorData = \Crypt::decryptString($visitorData);
                $visitorData = base64_decode($visitorData);
                $visitorData = json_decode($visitorData, true);

                return $visitorData;
            } catch (\DecryptException $e) {
                return false;
            }
        }

        return false;
    }
}
