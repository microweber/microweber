<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;


class RequestTypeTest extends TestCase
{


    public function testRequestResponseCode()
    {
        $this->get('/example-route-testRequestResponseCode')
            ->assertStatus(123);
    }


    public function testJsonPostRequest()
    {
        $testJsonRequest_a = 'testJsonRequest_a' . rand();
        $testJsonRequest_b = 'testJsonRequest_b' . rand();

        $response = $this->postJson('/example-route-testJsonPost', ['test' => $testJsonRequest_a, 'test2' => $testJsonRequest_b]);
        $userData = $response->getData();
        $this->assertEquals($testJsonRequest_a, $userData->test);
        $this->assertEquals($testJsonRequest_b, $userData->test2);

    }

    public function testPostRequest()
    {
        $testJsonRequest_a = 'testJsonRequest_a' . rand();
        $testJsonRequest_b = 'testJsonRequest_b' . rand();

        $response = $this->post('/example-route-testJsonPost', ['test' => $testJsonRequest_a, 'test2' => $testJsonRequest_b]);
        $userData = $response->getData();
        $this->assertEquals($testJsonRequest_a, $userData->test);
        $this->assertEquals($testJsonRequest_b, $userData->test2);

    }


    public function testPatchJsonRequest()
    {
        $testJsonRequest_a = 'testJsonRequest_a' . rand();
        $testJsonRequest_b = 'testJsonRequest_b' . rand();

        $response = $this->patchJson('/example-route-testJsonPatch', ['test' => $testJsonRequest_a, 'test2' => $testJsonRequest_b]);
        $userData = $response->getData();
        $this->assertEquals($testJsonRequest_a, $userData->test);
        $this->assertEquals($testJsonRequest_b, $userData->test2);

    }
}
