<?php

if (!defined('MW_WHITE_LABEL_SETTINGS_FILE')) {
    define('MW_WHITE_LABEL_SETTINGS_FILE', __DIR__ . DIRECTORY_SEPARATOR . 'settings.json');
    define('MW_WHITE_LABEL_SETTINGS_FILE_LOCAL', storage_path() . DIRECTORY_SEPARATOR . 'branding.json');
}

api_expose_admin('save_white_label_config');
function save_white_label_config($params)
{
    $file = MW_WHITE_LABEL_SETTINGS_FILE;
    $file_local = MW_WHITE_LABEL_SETTINGS_FILE_LOCAL;

    if (isset($params['powered_by_link']) and trim(strip_tags($params['powered_by_link'])) == '') {
        unset($params['powered_by_link']);
    }

    $params = json_encode($params);

    if (!is_writable($file)) {
        $file = $file_local;
    }

    return file_put_contents($file, $params);
}

function get_white_label_config()
{
    $file = MW_WHITE_LABEL_SETTINGS_FILE;
    $file_local = MW_WHITE_LABEL_SETTINGS_FILE_LOCAL;

    if (is_file($file_local)) {
        $cont = file_get_contents($file_local);
        return json_decode($cont, true);
    }

    if (is_file($file)) {
        $cont = file_get_contents($file);
        $params = json_decode($cont, true);

        return $params;
    }
}

event_bind('mw.after.boot', 'make_white_label');

function make_white_label()
{
    $settings = get_white_label_config();

    if (isset($settings['logo_admin']) and trim($settings['logo_admin']) != '') {
        $logo_admin = $settings['logo_admin'];
        mw()->ui->admin_logo = $logo_admin;
    }
    if (isset($settings['logo_live_edit']) and trim($settings['logo_live_edit']) != '') {
        $logo_live_edit = $settings['logo_live_edit'];
        mw()->ui->logo_live_edit = $logo_live_edit;

    }
    if (isset($settings['logo_login']) and trim($settings['logo_login']) != '') {
        $logo_login = $settings['logo_login'];
        mw()->ui->admin_logo_login = $logo_login;
    }


    if (isset($settings['admin_logo_login_link']) and trim($settings['admin_logo_login_link']) != '') {
        $logo_login = $settings['admin_logo_login_link'];
        mw()->ui->admin_logo_login_link = $logo_login;
    }


    if (isset($settings['powered_by']) and $settings['powered_by'] != false) {
        $powered_by = $settings['powered_by'];
        mw()->ui->powered_by = $powered_by;

    }
    if (isset($settings['powered_by_link']) and $settings['powered_by_link'] != false) {
        $powered_by_link = $settings['powered_by_link'];
        mw()->ui->powered_by_link = $powered_by_link;

    }
    if (isset($settings['brand_name']) and $settings['brand_name'] != false) {
        $brand_name = $settings['brand_name'];
        mw()->ui->brand_name = $brand_name;
    }


    if (isset($settings['enable_service_links'])) {
        mw()->ui->enable_service_links = $settings['enable_service_links'];
    }
    if (isset($settings['disable_marketplace'])) {
        mw()->ui->disable_marketplace = $settings['disable_marketplace'];
    }

    if (isset($settings['admin_colors_sass'])) {
        mw()->ui->admin_colors_sass = $settings['admin_colors_sass'];
    }


    if (isset($settings['disable_powered_by_link']) and intval($settings['disable_powered_by_link']) != 0) {
        mw()->ui->disable_powered_by_link = true;

    }

    if (isset($settings['marketplace_provider_id']) and trim($settings['marketplace_provider_id']) != false) {
        mw()->ui->marketplace_provider_id = trim($settings['marketplace_provider_id']);
    }
    if (isset($settings['marketplace_access_code']) and trim($settings['marketplace_access_code']) != false) {
        mw()->ui->marketplace_access_code = trim($settings['marketplace_access_code']);
    }
    if (isset($settings['custom_support_url'])) {
        mw()->ui->custom_support_url = $settings['custom_support_url'];
    }

    if (isset($settings['marketplace_repositories_urls'])) {
        mw()->ui->package_manager_urls = $settings['marketplace_repositories_urls'];
    }


    if (isset($settings['brand_favicon']) and $settings['brand_favicon']) {
        mw()->ui->brand_favicon = $settings['brand_favicon'];
    }

}
