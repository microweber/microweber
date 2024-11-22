<?php

namespace Modules\Pictures\Tests\Unit;

use Illuminate\Support\Facades\View;
use Modules\Media\Models\Media;
use Modules\Pictures\Microweber\PicturesModule;
use Tests\TestCase;

class PicturesModuleFrontendTest extends TestCase
{

    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'test-pictures-id' . uniqid(),
        ];

        $addMedia1 = Media::create([
            'rel_type' => 'module',
            'rel_id' => $params['id'],
            'filename' => 'image1.jpg',
            'media_type' => 'picture',
        ]);
        $addMedia2 = Media::create([
            'rel_type' => 'module',
            'rel_id' => $params['id'],
            'filename' => 'image2.jpg',
            'media_type' => 'picture',
        ]);

        $picturesModule = new PicturesModule($params);
        $viewData = $picturesModule->getViewData();
        $viewOutput = $picturesModule->render();

        $this->assertTrue(View::exists('modules.pictures::templates.default'));
        $this->assertStringContainsString('image1.jpg', $viewOutput);
        $this->assertStringContainsString('image2.jpg', $viewOutput);

        //delete media
        $addMedia1->delete();
        $addMedia2->delete();
    }

}
