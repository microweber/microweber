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

        set_cookie('_mw_stats_visitor_data', $visitorData);

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
