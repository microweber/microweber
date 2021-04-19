<?php

namespace MicroweberPackages\Package;

use MicroweberPackages\App\Models\SystemLicenses;

class MicroweberComposerClient {

    public $licenses = [];
    public $packages = [
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

    public function search()
    {

    }

    public function install()
    {

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
                "Authorization: Basic " . $this->licenses
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return ["error"=>"cURL Error #:" . $err];
        } else {
            $getPackages = json_decode($response, true);
            return $getPackages['packages'];
        }
    }

}
