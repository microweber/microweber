<?php

namespace MicroweberPackages\Multilanguage\tests;

use MicroweberPackages\App\Managers\PermalinkManager;
use \MicroweberPackages\Multilanguage\MultilanguageApi;
use MicroweberPackages\Multilanguage\MultilanguagePermalinkManager;

class MultilanguageTestBase extends \Microweber\tests\TestCase
{
    protected $preserveGlobalState = FALSE;
 //   protected $runTestInSeparateProcess = TRUE;
    /**
     * This method is called before the first test of this test class is run.
     */
    public static function setUpBeforeClass(): void
    {

    }

    protected function assertPostConditions(): void
    {
        \MicroweberPackages\Multilanguage\MultilanguageHelpers::setMultilanguageEnabled(false);

           \DB::table('options')
            ->where('option_group', 'multilanguage_settings')
            ->delete();

        app()->bind('permalink_manager', function () {
            return new PermalinkManager();
        });

    }

    /**
     * This method is called after the last test of this test class is run.
     */
    public static function tearDownAfterClass(): void
    {


    }

    protected function assertPreConditions(): void
    {
        if (!is_module('multilanguage')) {
            $this->markTestSkipped(
                'The Multilanguage module is not available.'
            );
        } else {
            \MicroweberPackages\Multilanguage\MultilanguageHelpers::setMultilanguageEnabled(true);

            app()->bind('permalink_manager', function () {
                return new MultilanguagePermalinkManager();
            });

            $option = array();
            $option['option_value'] = 'y';
            $option['option_key'] = 'is_active';
            $option['option_group'] = 'multilanguage_settings';
            save_option($option);
        }
    }

}
