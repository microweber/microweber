<?php

namespace MicroweberPackages\Multilanguage\tests;

use \MicroweberPackages\Multilanguage\MultilanguageApi;

class MultilanguageTestBase extends \Microweber\tests\TestCase
{
    protected function assertPreConditions(): void
    {
        if (!is_module('multilanguage')) {
            $this->markTestSkipped(
                'The Multilanguage module is not available.'
            );
        }
    }

}
