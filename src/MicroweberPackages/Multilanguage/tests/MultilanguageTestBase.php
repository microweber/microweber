<?php

namespace MicroweberPackages\Multilanguage\tests;

use \MicroweberPackages\Multilanguage\MultilanguageApi;

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

            $option = array();
            $option['option_value'] = 'y';
            $option['option_key'] = 'is_active';
            $option['option_group'] = 'multilanguage_settings';
            save_option($option);
        }
    }

}
