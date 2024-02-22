<?php

namespace MicroweberPackages\MetaTags\Providers;

use Butschster\Head\Facades\PackageManager;
use Butschster\Head\Packages\Package;
use Illuminate\Support\ServiceProvider;

class MetaTagsUnitTestServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->registerTestEventBinds();
    }

    private function registerTestEventBinds()
    {

        template_head(function () {

            $link = '<link rel="unit-test"  id="mw-meta-tags-test-inserted-from-template_head" type="unit-test">';
            return $link;

        });

        template_head('<link rel="unit-test"  id="mw-meta-tags-test-inserted-from-template_head_as_string" type="unit-test">');


        template_foot(function () {

            $link = '<link rel="unit-test"  id="mw-meta-tags-test-inserted-from-template_foot" type="unit-test">';
            return $link;

        });
        template_foot('<link rel="unit-test"  id="mw-meta-tags-test-inserted-from-template_foot_as_string" type="unit-test">');
        event_bind('site_header', function () {
            $link = '<link rel="unit-test"  id="meta-tags-test-inserted-from-event-site_header" type="unit-test">';
            return $link;
        });

    }

}
