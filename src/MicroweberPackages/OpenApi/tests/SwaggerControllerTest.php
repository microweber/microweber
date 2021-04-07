<?php

namespace MicroweberPackages\OpenApi\tests;

use MicroweberPackages\Core\tests\TestCase;


class SwaggerControllerTest extends TestCase
{

    public function testIfSwaggerDocsJsonIsNotGivingError()
    {

        $swagger = app()->make('\MicroweberPackages\OpenApi\Http\Controllers\SwaggerController');
        $resp = $swagger->docs(request());
        $this->assertEquals(true, is_object($resp));
        $this->assertEquals(true, !empty($resp));
    }


}
