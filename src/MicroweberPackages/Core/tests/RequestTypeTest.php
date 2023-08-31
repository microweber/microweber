<?php

namespace MicroweberPackages\Core\tests;


use Illuminate\Foundation\Application;

class RequestTypeTest extends \Illuminate\Foundation\Testing\TestCase
{

    public function createApplication(): Application
    {
        $app = require __DIR__ . '/../../../../bootstrap/app.php';

        $app->make(\Illuminate\Foundation\Console\Kernel::class)->bootstrap();

        return $app;
    }

    public function testJsonRequest()
    {


        $testJsonRequest_a = 'testJsonRequest_a' . rand();
        $testJsonRequest_b = 'testJsonRequest_b' . rand();
        $response = $this->postJson('/RequestTypeTest', ['test' => $testJsonRequest_a, 'test2' => $testJsonRequest_b]);

        $userData = $response->getData();

        $this->assertEquals('testJsonRequest_a', $userData->test);
        $this->assertEquals('testJsonRequest_b', $userData->test2);


    }
}
