<?php

namespace Modules\WhiteLabel\Services;

class WhiteLabelService
{
    /**
     * Path to the main white label settings file
     */
    protected string $settingsFile;

    /**
     * Path to the local white label settings file
     */
    protected string $settingsFileLocal;

    /**
     * Path to the SaaS branding settings file
     */
    protected string $settingsFileSaas;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->settingsFile = storage_path() . DIRECTORY_SEPARATOR . 'whitelabel_settings.json';
        $this->settingsFileLocal = storage_path() . DIRECTORY_SEPARATOR . 'branding.json';
        $this->settingsFileSaas = storage_path() . DIRECTORY_SEPARATOR . 'branding_saas.json';
    }

    /**
     * Apply white label settings to the UI
     */
    public function applyWhiteLabelSettings()
    {
        $settings = $this->getWhiteLabelConfig();
        $ui = app('ui');

        if (isset($settings['logo_admin']) && trim($settings['logo_admin']) != '') {
            $ui->admin_logo = $settings['logo_admin'];
        }

        if (isset($settings['logo_live_edit']) && trim($settings['logo_live_edit']) != '') {
            $ui->logo_live_edit = $settings['logo_live_edit'];
        }

        if (isset($settings['logo_login']) && trim($settings['logo_login']) != '') {
            $ui->admin_logo_login = $settings['logo_login'];
        }

        if (isset($settings['admin_logo_login_link']) && trim($settings['admin_logo_login_link']) != '') {
            $ui->admin_logo_login_link = $settings['admin_logo_login_link'];
        }

        if (isset($settings['powered_by']) && $settings['powered_by'] != false) {
            $ui->powered_by = $settings['powered_by'];
        }

        if (isset($settings['powered_by_link']) && $settings['powered_by_link'] != false) {
            $ui->powered_by_link = $settings['powered_by_link'];
        }

        if (isset($settings['brand_name']) && $settings['brand_name'] != false) {
            $ui->brand_name = $settings['brand_name'];
        }

        if (isset($settings['enable_service_links'])) {
            $ui->enable_service_links = $settings['enable_service_links'];
        }

        if (isset($settings['disable_marketplace'])) {
            $ui->disable_marketplace = $settings['disable_marketplace'];
        }

        if (isset($settings['admin_colors_sass'])) {
            $ui->admin_colors_sass = $settings['admin_colors_sass'];
        }

        if (isset($settings['disable_powered_by_link']) && intval($settings['disable_powered_by_link']) != 0) {
            $ui->disable_powered_by_link = true;
        }

        if (isset($settings['marketplace_provider_id']) && trim($settings['marketplace_provider_id']) != false) {
            $ui->marketplace_provider_id = trim($settings['marketplace_provider_id']);
        }

        if (isset($settings['marketplace_access_code']) && trim($settings['marketplace_access_code']) != false) {
            $ui->marketplace_access_code = trim($settings['marketplace_access_code']);
        }

        if (isset($settings['custom_support_url'])) {
            $ui->custom_support_url = $settings['custom_support_url'];
        }

        if (isset($settings['marketplace_repositories_urls'])) {
            $ui->package_manager_urls = $settings['marketplace_repositories_urls'];
        }

        if (isset($settings['brand_favicon']) && $settings['brand_favicon']) {
            $ui->brand_favicon = $settings['brand_favicon'];
        }
    }

    /**
     * Save white label configuration
     */
    public function saveWhiteLabelConfig($params)
    {
        $settingsFile = $this->settingsFile;
        $settingsFileLocal = $this->settingsFileLocal;

        if (isset($params['powered_by_link']) && trim(strip_tags($params['powered_by_link'])) == '') {
            unset($params['powered_by_link']);
        }

        $params = json_encode($params);

        if (!is_writable($settingsFile)) {
            $settingsFile = $settingsFileLocal;
        }

        return file_put_contents($settingsFile, $params);
    }

    /**
     * Get white label configuration
     */
    public function getWhiteLabelConfig()
    {
        $settingsFile = $this->settingsFile;
        $settingsFileLocal = $this->settingsFileLocal;

        if ($settingsFileLocal && is_file($settingsFileLocal)) {
            $cont = file_get_contents($settingsFileLocal);
            $localSettingsJson = json_decode($cont, true);

            $saasFile = $this->settingsFileSaas;
            if (is_file($saasFile)) {
                $cont = file_get_contents($saasFile);
                $saasFileJson = json_decode($cont, true);
                foreach ($saasFileJson as $key => $value) {
                    if (!isset($localSettingsJson[$key])) {
                        $localSettingsJson[$key] = $value;
                    } else {
                        if (empty($localSettingsJson[$key])) {
                            $localSettingsJson[$key] = $value;
                        }
                    }
                }
            }

            return $localSettingsJson;
        }

        if (is_file($settingsFile)) {
            $cont = file_get_contents($settingsFile);
            $params = json_decode($cont, true);

            return $params;
        }

        $saasFile = $this->settingsFileSaas;
        if (is_file($saasFile)) {
            $cont = file_get_contents($saasFile);
            $saasFileJson = json_decode($cont, true);

            return $saasFileJson;
        }

        return [];
    }
}
