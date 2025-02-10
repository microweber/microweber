<?php

namespace Modules\Media\Tests\Unit;

use MicroweberPackages\Core\tests\TestCase;
use Modules\MediaLibrary\Support\Unsplash;

class UnsplashTest extends TestCase
{
    public function testSearch()
    {
        $unsplash = new Unsplash();
        $search = $unsplash->search('apple');
        if (!is_array($search)) {
            $this->markTestSkipped(
                'The Unsplash search is not available.'
            );
            return;
        }

        $this->assertTrue($search['success']);
        $this->assertTrue(is_array($search['photos']));
        $this->assertTrue(!empty($search['photos']));

        $download = $unsplash->download($search['photos'][0]['id']);

        $this->assertTrue(is_file(public_path(url2dir($download))));
    }


}
