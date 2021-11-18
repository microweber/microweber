<?php

namespace MicroweberPackages\Media\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Utils\Media\Adapters\Unsplash;

class UnsplashTest extends TestCase
{
    public function testSearch()
    {
        $unsplash = new Unsplash();
        $search = $unsplash->search('apple');

        $this->assertTrue($search['success']);
        $this->assertIsArray($search['photos']);
        $this->assertNotEmpty($search['photos']);

        $download = $unsplash->download($search['photos'][0]['id']);

        $this->assertTrue(is_file($download));
    }
}
