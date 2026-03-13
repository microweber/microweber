<?php

namespace MicroweberPackages\Template\tests;

use PHPUnit\Framework\Attributes\Test;


use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use MicroweberPackages\User\Models\User;

class LegacyConstantsTest extends TestCase
{

    #[Test]
    public function it_constants_are_defined(): void {
        $templateName = 'my-test-template-for-constants';

        $user = User::where('is_admin', '=', '1')->first();
        if (!$user) {
            $user = new User();
            $user->username = 'test' . uniqid();
            $user->password = 'test';
            $user->email = 'testconstants@example.com';
            $user->is_admin = 1;
            $user->save();
        }
        Auth::login($user);

        $newCleanPageId = save_content([
            'subtype' => 'dynamic',
            'content_type' => 'page',
            'layout_file' => 'clean.php',
            'title' => 'PagetestConstantsAreDefinedTest',
            'active_site_template' => $templateName,
            'is_active' => 1,
        ]);

        app()->content_manager->define_constants(['id' => $newCleanPageId]);


        $this->assertTrue(defined('PAGE_ID'));
        $this->assertTrue(defined('POST_ID'));
        $this->assertTrue(defined('CONTENT_ID'));
        $this->assertTrue(defined('MAIN_PAGE_ID'));
        $this->assertTrue(defined('ROOT_PAGE_ID'));
        $this->assertTrue(defined('PARENT_PAGE_ID'));
        $this->assertTrue(defined('CATEGORY_ID'));


        $this->assertTrue(defined('DEFAULT_TEMPLATE_DIR'));
        $this->assertTrue(defined('DEFAULT_TEMPLATE_URL'));
        $this->assertTrue(defined('THIS_TEMPLATE_FOLDER_NAME'));
        $this->assertTrue(defined('THIS_TEMPLATE_URL'));
        $this->assertTrue(defined('THIS_TEMPLATE_DIR'));
        $this->assertTrue(defined('ACTIVE_SITE_TEMPLATE'));
        $this->assertTrue(defined('ACTIVE_TEMPLATE_DIR'));
        $this->assertTrue(defined('TEMPLATE_NAME'));
        $this->assertTrue(defined('TEMPLATES_DIR'));
        $this->assertTrue(defined('TEMPLATE_DIR'));
        $this->assertTrue(defined('TEMPLATE_URL'));

        // Verify values via helper functions which use object properties
        // (constants cannot be redefined in a single process)
        $this->assertEquals($newCleanPageId, page_id());
        $this->assertEquals($newCleanPageId, content_id());

        $this->assertEquals(0, app()->template_manager->getMainPageId());
        $this->assertEquals(0, category_id());
        $this->assertEquals(0, post_id());

        $this->assertEquals($templateName, template_name());
        $this->assertEquals(templates_dir(), TEMPLATES_DIR);
        $this->assertEquals(template_name(), $templateName);
        $this->assertEquals(template_url(), template_url());
        $this->assertEquals(template_dir(), template_dir());


    }



}
