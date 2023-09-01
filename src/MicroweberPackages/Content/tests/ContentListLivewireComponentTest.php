<?php

namespace MicroweberPackages\Content\tests;

use Livewire\Livewire;
use MicroweberPackages\App\Http\Controllers\FrontendController;
use MicroweberPackages\Content\Http\Livewire\Admin\ContentList;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\User\tests\UserTestHelperTrait;

class ContentListLivewireComponentTest extends TestCase
{
    use UserTestHelperTrait;

    public function testComponent()
    {
        $this->actingAsAdmin();

        $getAllPages = Page::get();

        $contentListTest = Livewire::test(ContentList::class);
        $contentListTest->call('getRenderData');
        $response = json_decode($contentListTest->lastResponse->content(),TRUE);
        $responseMethod = reset($response['effects']['returns']);
        $contentListResponseData = $responseMethod['data']['contents']['data'];

        $this->assertEquals($getAllPages->count(), count($contentListResponseData));

    }

}
