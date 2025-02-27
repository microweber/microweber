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
        $result['request_time'] = date('Y-m-d H:i:s');

        // Get geolocation provider
        $provider = get_option('geolocation_provider', 'multilanguage_settings');
        $result['provider'] = $provider;
        $result['settings'] = [
            'is_active' => get_option('is_active', 'multilanguage_settings'),
            'use_geolocation' => get_option('use_geolocation', 'multilanguage_settings'),
            'add_prefix_for_all_languages' => get_option('add_prefix_for_all_languages', 'multilanguage_settings'),
            'homepage_language' => get_option('homepage_language', 'website')
        ];

        switch ($provider) {
            case 'browser_detection':
                $result['browser_language'] = $request->header('Accept-Language');
                $languages = explode(',', $request->header('Accept-Language'));
                $result['detected_languages'] = [];

                foreach ($languages as $language) {
                    $parts = explode(';', $language);
                    $lang = trim($parts[0]);
                    $q = isset($parts[1]) ? (float) str_replace('q=', '', $parts[1]) : 1.0;
                    $result['detected_languages'][] = ['language' => $lang, 'quality' => $q];
                }
                break;

            case 'domain_detection':
                $result['domain'] = $request->getHost();
                $result['domain_parts'] = explode('.', $request->getHost());
                break;

            case 'geoip_browser_detection':
                $result['browser_language'] = $request->header('Accept-Language');
                $languages = explode(',', $request->header('Accept-Language'));
                $result['detected_languages'] = [];

                foreach ($languages as $language) {
                    $parts = explode(';', $language);
                    $lang = trim($parts[0]);
                    $q = isset($parts[1]) ? (float) str_replace('q=', '', $parts[1]) : 1.0;
                    $result['detected_languages'][] = ['language' => $lang, 'quality' => $q];
                }

                // Add GeoIP detection
                $result['geoip_note'] = 'GeoIP detection would be implemented here';
                break;

            case 'microweber':
                // Call Microweber Geo API
                $result['microweber_api'] = 'Would call Microweber API here';
                $result['microweber_note'] = 'This would connect to Microweber\'s geolocation service';
                break;

            case 'ipstack_com':
                $apiKey = get_option('ipstack_api_access_key', 'multilanguage_settings');
                $result['ipstack_api_key_provided'] = !empty($apiKey);

                if (!empty($apiKey)) {
                    $ip = $request->ip();
                    $url = "http://api.ipstack.com/{$ip}?access_key={$apiKey}";

                    try {
                        $response = file_get_contents($url);
                        $ipData = json_decode($response, true);
                        $result['ipstack_data'] = $ipData;

                        if (isset($ipData['country_code'])) {
                            $result['detected_country_code'] = $ipData['country_code'];
                        }
                    } catch (\Exception $e) {
                        $result['ipstack_error'] = $e->getMessage();
                    }
                } else {
                    $result['ipstack_error'] = 'API key not provided';
                    $result['ipstack_help'] = 'Please add your IPStack API key in the settings tab';
                }
                break;
        }

        // Add supported languages for reference
        $supportedLocales = \MicroweberPackages\Multilanguage\Models\MultilanguageSupportedLocales::where('is_active', 1)->get();
        $result['supported_languages'] = $supportedLocales->map(function($locale) {
            return [
                'locale' => $locale->locale,
                'language' => $locale->language
            ];
        });

        return response()->json($result);
    }
}
