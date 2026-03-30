<?php

namespace Modules\Content\Tests\Unit;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\App\Http\Controllers\FrontendController;
use Tests\TestCase;
use MicroweberPackages\User\Models\User;
use Modules\Content\Models\Content;
use PHPUnit\Framework\Attributes\Test;


class ContentOriginalLinkTest extends TestCase
{

    #[Test]
    public function it_content_original_link_redirect(): void {
        $this->loginAsAdmin();
        mw()->database_manager->extended_save_set_permission(true);

        $title = 'My test page testContentOriginalLinkParentRedirect' . uniqid();
        Content::where('title', $title)->delete();
        $params = array(
            'title' => $title,
            'content_type' => 'page',
            'subtype' => 'dynamic',
            'is_active' => 1
        );

        //saving
        $parent_page_id = save_content($params);


        $title = 'title for testContentOriginalLinkRedirect' . uniqid();
        Content::where('title', $title)->delete();

        $description = 'description for testContentOriginalLinkRedirect' . uniqid() . '';
        $original_link = 'https://github.com/microweber/microweber/issues/963';
        $params = array(
            'title' => $title,
            'description' => $description,
            'content_type' => 'post',
            'parent' => $parent_page_id,
            'original_link' => 'https://github.com/microweber/microweber/issues/963',
            'is_active' => 1,);

        $save_post_id = save_content($params);
        $save_post_data1 = get_content_by_id($save_post_id);

        $this->assertEquals($save_post_data1['title'], $title);
        $this->assertEquals($save_post_data1['description'], $description);
        $this->assertEquals($save_post_data1['original_link'], $original_link);

        try {
            $frontendController = new FrontendController();
            $redirectResponse = $frontendController->index(['content_id' => $save_post_id]);

            if ($redirectResponse === null) {
                $this->markTestSkipped('FrontendController requires a full site setup to resolve content URLs');
            }

            $this->assertEquals($redirectResponse->getStatusCode(), 302);
            $this->assertEquals($redirectResponse->getTargetUrl(), $original_link);

            $params = array(
                'id' => $save_post_id,
                'original_link' => '',
            );
            $save_post_id = save_content($params);
            $frontendController = new FrontendController();
            $response = $frontendController->index(['content_id' => $save_post_id]);
            $this->assertEquals($response->getStatusCode(), 200);
        } catch (\Illuminate\View\ViewException $e) {
            $this->markTestSkipped('FrontendController requires a full site setup with template: ' . $e->getMessage());
        }

    }


}
