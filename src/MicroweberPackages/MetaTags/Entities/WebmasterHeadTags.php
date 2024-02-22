<?php

namespace MicroweberPackages\MetaTags\Entities;

use Arcanedev\SeoHelper\Entities\Analytics;
use Arcanedev\SeoHelper\Entities\Webmasters;
use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;
use Illuminate\Support\Facades\Cookie;

class WebmasterHeadTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {


        $template_headers_src = '';

        $headers = $this->get_template_meta_webmaster_tags();
        foreach ($headers as $headers_append) {
            if (is_string($headers_append)) {
                $template_headers_src = $template_headers_src . "\n" . $headers_append;
            }
        }


        return $template_headers_src;

    }

    public function getPlacement(): string
    {
        return Meta::PLACEMENT_HEAD;
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }


    public function toArray(): array
    {
        return [
            'type' => 'webmaster_head_tags',
        ];
    }

    private function get_template_meta_webmaster_tags(): array
    {
        $headers = array();
        $headers[] = $this->_render_webmasters_tags();

        $analyticsTag = true;
        $fbPixel = true;
        $settings = get_option('settings', 'init_scwCookiedefault');
        if ($settings) {
            $getCookieNotice = json_decode($settings, true);
            if (isset($getCookieNotice['cookies_policy']) && $getCookieNotice['cookies_policy'] == 'y') {
                $analyticsTag = true;
                $fbPixel = false;
                if (Cookie::get('google-analytics-allow') == 1) {
                    $analyticsTag = true;
                }
                if (Cookie::get('facebook-pixel-allow') == 1) {
                    $fbPixel = true;
                }
            }
        }
        if ($analyticsTag) {
            $headers[] = $this->_render_analytics_tags();
        }
        if ($fbPixel) {
            $headers[] = $this->_render_fb_pixel_tags();
        }

        return $headers;
    }


    private function _render_webmasters_tags()
    {
        $websiteOptions = app()->option_repository->getWebsiteOptions();

        $configs = [];

        if (isset($websiteOptions['google-site-verification-code']) && $websiteOptions['google-site-verification-code'] != false) {
            $configs['google'] = $websiteOptions['google-site-verification-code'];
        }
        if (isset($websiteOptions['bing-site-verification-code']) && $websiteOptions['bing-site-verification-code'] != false) {
            $configs['bing'] = $websiteOptions['bing-site-verification-code'];
        }
        if (isset($websiteOptions['alexa-site-verification-code']) && $websiteOptions['alexa-site-verification-code'] != false) {
            $configs['alexa'] = $websiteOptions['alexa-site-verification-code'];
        }
        if (isset($websiteOptions['pinterest-site-verification-code']) && $websiteOptions['pinterest-site-verification-code'] != false) {
            $configs['pinterest'] = $websiteOptions['pinterest-site-verification-code'];
        }
        if (isset($websiteOptions['yandex-site-verification-code']) && $websiteOptions['yandex-site-verification-code'] != false) {
            $configs['yandex'] = $websiteOptions['yandex-site-verification-code'];
        }
        $webmasters = Webmasters::make($configs);

        return $webmasters->render();
    }

    private function _render_fb_pixel_tags()
    {
        $websiteOptions = app()->option_repository->getWebsiteOptions();
        $code = $websiteOptions['facebook-pixel-id'];

        if ($code) {
            $pixel = PHP_EOL;
            $pixel .= <<<EOT
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '$code');
fbq('track', 'PageView');
</script>
<noscript>
 <img height="1" width="1"
src="https://www.facebook.com/tr?id=$code&ev=PageView
&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->
EOT;
            return $pixel;
        }
    }

    private function _render_analytics_tags()
    {
        $websiteOptions = app()->option_repository->getWebsiteOptions();

        $code = $websiteOptions['google-analytics-id'];

        if ($code) {
            $analytics = new Analytics;
            $analytics->setGoogle($code);
            return $analytics->render();
        }

    }


}
