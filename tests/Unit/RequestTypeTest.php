<?php

namespace Tests\Unit;

use Tests\TestCase;


class RequestTypeTest extends TestCase
{
    public function testJsonRequest()
    {




        $response = $this->postJson('/RequestTypeTest11', ['test' => 'testJsonRequest_a', 'test2' => 'testJsonRequest_b']);
    //      dd($response->getContent());
        $userData = $response->getData();

        $this->assertEquals('testJsonRequest_a', $userData->test);
        $this->assertEquals('testJsonRequest_b', $userData->test2);


    }
}
