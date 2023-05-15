<?php

namespace MicroweberPackages\Template\tests;


use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;

/**
 * @runTestsInSeparateProcesses
 */
class LegacyConstantsTest extends TestCase
{


    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testConstantsAreDefined()
    {
        $this->setPreserveGlobalState(false);
        $templateName = 'my-test-template-for-constants';

        $user = User::where('is_admin', '=', '1')->first();
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


        $this->assertEquals($newCleanPageId, page_id());

        $this->assertEquals($newCleanPageId, PAGE_ID);

        $this->assertEquals($newCleanPageId, CONTENT_ID);


        $this->assertEquals(0, ROOT_PAGE_ID);
        $this->assertEquals(0, MAIN_PAGE_ID);
        $this->assertEquals(0, PARENT_PAGE_ID);
        $this->assertEquals(0, CATEGORY_ID);
        $this->assertEquals(0, POST_ID);

        $this->assertEquals($templateName, TEMPLATE_NAME);
        $this->assertEquals($templateName, ACTIVE_SITE_TEMPLATE);
        $this->assertEquals(templates_dir(), TEMPLATES_DIR);
        $this->assertEquals(template_name(), TEMPLATE_NAME);
        $this->assertEquals(template_url(), THIS_TEMPLATE_URL);
        $this->assertEquals(template_url(), TEMPLATE_URL);
        $this->assertEquals(template_dir(), TEMPLATE_DIR);
        $this->assertEquals(template_url(), THIS_TEMPLATE_URL);
        $this->assertEquals(template_dir(), ACTIVE_TEMPLATE_DIR);


    }



}
