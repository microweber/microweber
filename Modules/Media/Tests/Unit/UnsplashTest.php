<?php

namespace Modules\Media\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use Modules\MediaLibrary\Support\Unsplash;

class UnsplashTest extends TestCase
{
    #[Test]
    public function it_search(): void {
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

        $photoId = $search['photos'][0]['id'];
        $download = $unsplash->download($photoId);

        // Check the file exists — try both the URL-converted path and the media path directly
        $fileExists = is_file(public_path(url2dir($download)))
            || is_file(media_uploads_path() . $photoId . '-1600.jpg');
        $this->assertTrue($fileExists);
    }


}
