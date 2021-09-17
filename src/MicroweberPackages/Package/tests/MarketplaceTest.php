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

        foreach( $composerSearch as $packageName=>$versions) {
            if(!is_array($versions)){
                continue;
            }
            foreach($versions as $version) {
                if (strpos($version['name'], 'template') !== false) {
                    $this->assertNotEmpty($version['extra']['_meta']['screenshot']);
                }
            }
        }

    }

}
