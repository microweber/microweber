<?php

function showMicroweberAdsBar() {

    $showBar = true;
    $showBarUrl = 'https://members.microweber.bg/index.php?m=microweber_addon&function=show_ads_bar';

    $whmcsSettingsFile = modules_path() . 'whmcs_connector/settings.json';
    $whmcsSettingsFile = normalize_path($whmcsSettingsFile, false);
    if (is_file($whmcsSettingsFile)) {

        $whmcsUrl = false;
        $whmcsSettingsFileContent = file_get_contents($whmcsSettingsFile);
        $settings = json_decode($whmcsSettingsFileContent, true);
        if (isset($settings['url'])) {
            $whmcsUrl = $settings['url'];
        }
        if (isset($settings['whmcs_url'])) {
            $whmcsUrl = $settings['whmcs_url'];
        }

        if ($whmcsUrl) {
            $checkDomainUrl = $whmcsUrl . 'index.php?m=microweber_addon&function=check_domain_is_premium&domain=' . $_SERVER['HTTP_HOST'];
            $checkDomain = file_get_contents($checkDomainUrl);
            $checkDomain = json_decode($checkDomain, true);

            if (isset($checkDomain['free']) && $checkDomain['free'] == true && isset($checkDomain['ads_bar_url'])) {
                $showBarUrl = $whmcsUrl . $checkDomain['ads_bar_url'];
                $showBar = true;
            }
        }

    }

    return array('show'=>$showBar, 'iframe_url'=>$showBarUrl);
}

event_bind('mw.front', function () {
    $css = '
        <style>
        body {
            padding-top:60px;
        }
        .js-microweber-add-iframe {
            z-index: 99999;
            padding: 7px;
            min-height: 73px;
            position: absolute;
            height: 73px;
            border: 0;
            left: 0;
            right: 0;
            top: 0;
            width: 100%;
            overflow: hidden;
        }
        </style>
    ';

    $bar = showMicroweberAdsBar();

    if ($bar['show']) {
       mw()->template->foot($css . '<iframe class="js-microweber-add-iframe" scrolling="no" frameborder="0" src="'.$bar['iframe_url'].'"></iframe>');
    }

});