<?php

function showMicroweberAdsBar() {
    $showBar = false;

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

            if (isset($checkDomain['free']) && $checkDomain['free'] == true) {
                $showBar = true;
            }
        }

    }

    return $showBar;
}