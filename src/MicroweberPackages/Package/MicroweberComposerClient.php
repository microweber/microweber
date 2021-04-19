<?php

namespace MicroweberPackages\Package;

use Composer\Package\Package;
use MicroweberPackages\App\Models\SystemLicenses;

class MicroweberComposerClient {

    public $licenses = [];
    public $packageServers = [
         'https://packages-satis.microweberapi.com/packages.json',
    ];

    public function __construct()
    {
        // Fill the user licenses
        $findLicenses = SystemLicenses::all();
        if ($findLicenses !== null) {
            $this->licenses = $findLicenses->toArray();
        }
    }

    public function search($filter)
    {
        $packages = [];
        foreach($this->packageServers as $package) {
            $getRepositories = $this->getPackageFile($package);
            foreach($getRepositories as $packageName=>$packageVersions) {
                foreach($packageVersions as $packageVersion=>$packageVersionData) {
                    if (($filter['require_version'] == $packageVersion) &&
                        ($filter['require_name'] == $packageName)) {
                        $packages[] = $packageVersionData;
                        break;
                    }
                }
            }
        }

        return $packages;
    }

    public function install($params)
    {
        $search = $this->search([
           'require_version'=>$params['require_version'],
           'require_name'=>$params['require_name'],
        ]);

        if (!$search) {
            return array('error' => 'Error. Cannot find any packages.');
        }

        $needConfirm = true;
        $confirmKey = 'composer-confirm-key-' . rand();
        if (isset($params['confirm_key'])) {
            $isConfirmed = cache_get($params['confirm_key'], 'composer');
            if ($isConfirmed) {
                $needConfirm = false;
            }
        }

        if ($needConfirm) {
            $composerConfirm = array();
            $composerConfirm['user'] = [];
            $composerConfirm['packages'] = [];
            cache_save($composerConfirm, $confirmKey, 'composer');

            return array(
                'error' => 'Please confirm installation',
                'form_data_module' => 'admin/developer_tools/package_manager/confirm_install',
                'form_data_module_params' => array(
                    'confirm_key' => $confirmKey,
                    'require_name' => $params['require_name'],
                    'require_version' => $params['require_version']
                )
            );
        }


    }

    public function getPackageFile($packagesUrl)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $packagesUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => [
                "Authorization: Basic " . base64_encode(json_encode($this->licenses))
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return ["error"=>"cURL Error #:" . $err];
        } else {
            $getPackages = json_decode($response, true);
            if (isset($getPackages['packages']) && is_array($getPackages['packages'])) {
                return $getPackages['packages'];
            }
            return [];
        }
    }

}
