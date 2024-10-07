<?php

namespace Modules\OpenApi\Tests;

use Tests\TestCase;

class SwaggerControllerTest extends TestCase
{
    public function testIfSwaggerDocsJsonIsNotGivingError()
    {
        $swagger = app()->make(\Modules\OpenApi\Http\Controllers\SwaggerController::class);
        $resp = $swagger->docs(request());
        $this->assertEquals(true, is_object($resp));
        $this->assertEquals(true, !empty($resp));
    }

    public function testIfRssModuleSwaggerHasApiSwagger()
    {
        if (app()->modules->find('RssFeed') == null) {
            $this->markTestSkipped('RssFeed module is not installed');
        }


        $swagger = app()->make(\Modules\OpenApi\Http\Controllers\SwaggerController::class);
        $response = $swagger->docs(request());

        $this->assertIsObject($response);
        $this->assertIsString($response->content());

        $swaggerJson = json_decode($response->content(), true);

        $this->assertArrayHasKey('/rss', $swaggerJson['paths']);
        $this->assertArrayHasKey('get', $swaggerJson['paths']['/rss']);
        $this->assertEquals('Modules\\RssFeed\\Http\\Controllers\\RssController@index', $swaggerJson['paths']['/rss']['get']['description']);
        $this->assertFalse($swaggerJson['paths']['/rss']['get']['deprecated']);
        $this->assertArrayHasKey('200', $swaggerJson['paths']['/rss']['get']['responses']);
        $this->assertEquals('OK', $swaggerJson['paths']['/rss']['get']['responses']['200']['description']);
    }
}
