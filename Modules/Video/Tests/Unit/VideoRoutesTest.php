<?php

namespace Modules\Video\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VideoRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_web_routes_accessible()
    {
        $response = $this->get(route('video.index'));
        $response->assertStatus(200);
        
        $response = $this->get(route('video.show', ['id' => 1]));
        $response->assertStatus(200);
    }

    public function test_api_routes_accessible()
    {
        $response = $this->getJson('/api/video');
        $response->assertStatus(200);
        
        $response = $this->postJson('/api/video/embed', [
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'
        ]);
        $response->assertStatus(200)
                ->assertJsonStructure(['embed_code']);
    }

    public function test_api_embed_validation()
    {
        $response = $this->postJson('/api/video/embed', [
            'url' => 'invalid-url'
        ]);
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['url']);
    }

    public function test_api_embed_options()
    {
        $response = $this->postJson('/api/video/embed', [
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'autoplay' => 1,
            'controls' => 0
        ]);
        
        $response->assertStatus(200);
        $this->assertStringContainsString(
            'autoplay=1',
            $response->json()['embed_code']
        );
    }
}