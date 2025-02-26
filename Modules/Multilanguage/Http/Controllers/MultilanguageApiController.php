<?php

namespace Modules\Multilanguage\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MultilanguageApiController extends Controller
{
    public function geolocaitonTest(Request $request)
    {
        $result = [];
        $result['ip'] = $request->ip();
        $result['user_agent'] = $request->header('User-Agent');
        
        // Get geolocation provider
        $provider = get_option('geolocation_provider', 'multilanguage_settings');
        $result['provider'] = $provider;
        
        switch ($provider) {
            case 'browser_detection':
                $result['browser_language'] = $request->header('Accept-Language');
                break;
                
            case 'domain_detection':
                $result['domain'] = $request->getHost();
                break;
                
            case 'geoip_browser_detection':
                $result['browser_language'] = $request->header('Accept-Language');
                // Would add GeoIP detection here
                break;
                
            case 'microweber':
                // Call Microweber Geo API
                $result['microweber_api'] = 'Would call Microweber API here';
                break;
                
            case 'ipstack_com':
                $apiKey = get_option('ipstack_api_access_key', 'multilanguage_settings');
                if (!empty($apiKey)) {
                    $ip = $request->ip();
                    $url = "http://api.ipstack.com/{$ip}?access_key={$apiKey}";
                    
                    try {
                        $response = file_get_contents($url);
                        $ipData = json_decode($response, true);
                        $result['ipstack_data'] = $ipData;
                    } catch (\Exception $e) {
                        $result['ipstack_error'] = $e->getMessage();
                    }
                } else {
                    $result['ipstack_error'] = 'API key not provided';
                }
                break;
        }
        
        return response()->json($result);
    }
}
