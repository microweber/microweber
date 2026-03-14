<?php

namespace MicroweberPackages\Core\tests;

use PHPUnit\Framework\Attributes\Test;



class RequestTypeTest extends TestCase
{


    #[Test]


    public function it_request_response_code(): void {
        $this->get('/example-route-testRequestResponseCode')
            ->assertStatus(123);
    }


    #[Test]


    public function it_json_post_request(): void {
        $testJsonRequest_a = 'testJsonRequest_a' . rand();
        $testJsonRequest_b = 'testJsonRequest_b' . rand();

        $response = $this->postJson('/example-route-testJsonPost', ['test' => $testJsonRequest_a, 'test2' => $testJsonRequest_b]);

        $userData = $response->json();
        $this->assertEquals($testJsonRequest_a, $userData['test']);
        $this->assertEquals($testJsonRequest_b, $userData['test2']);

    }

    #[Test]

    public function it_post_request(): void {
        $testJsonRequest_a = 'testJsonRequest_a' . rand();
        $testJsonRequest_b = 'testJsonRequest_b' . rand();

        $response = $this->post('/example-route-testJsonPost', ['test' => $testJsonRequest_a, 'test2' => $testJsonRequest_b]);
        $userData = $response->json();
        $this->assertEquals($testJsonRequest_a, $userData['test']);
        $this->assertEquals($testJsonRequest_b, $userData['test2']);

    }


    #[Test]


    public function it_patch_json_request(): void {
        $testJsonRequest_a = 'testJsonRequest_a' . rand();
        $testJsonRequest_b = 'testJsonRequest_b' . rand();

        $response = $this->patchJson('/example-route-testJsonPatch', ['test' => $testJsonRequest_a, 'test2' => $testJsonRequest_b]);
        $userData = $response->json();
        $this->assertEquals($testJsonRequest_a, $userData['test']);
        $this->assertEquals($testJsonRequest_b, $userData['test2']);

    }
}
