<?php

namespace MicroweberPackages\Package\tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Package\MicroweberComposerClient;
use MicroweberPackages\User\Models\User;

class MarketplaceTest extends TestCase
{
    public function testMarketplaceIndex()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $composerClient = new MicroweberComposerClient();
        $composerSearch = $composerClient->search();

        foreach($composerSearch as $packageName=>$versions) {
            if (!is_array($versions)) {
                continue;
            }
            $latestVersion = [];
            foreach($versions as $version) {
                $latestVersion = $version;
            }
            if (strpos($latestVersion['name'], 'template') !== false) {

                if (!isset($latestVersion['extra']['_meta']['screenshot'])) {
                    throw new \Exception('Screenshot is empty for ' . $latestVersion['name']);
                }

                $this->assertTrue(!empty($latestVersion['extra']['_meta']['screenshot']) , 'Screenshot is empty for ' . $latestVersion['name']);
                $this->assertTrue(!empty($latestVersion['dist']['url']), 'Dist url is empty for ' . $latestVersion['name']);
            }
        }

    }

}
