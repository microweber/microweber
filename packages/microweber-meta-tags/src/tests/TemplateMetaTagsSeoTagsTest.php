<?php

namespace MicroweberPackages\MetaTags\tests;

use PHPUnit\Framework\Attributes\Test;

use MicroweberPackages\App\Http\Controllers\FrontendController;
use MicroweberPackages\User\Models\User;
use Modules\Content\Tests\Unit\TestHelpers;

use Tests\TestCase;

class TemplateMetaTagsSeoTagsTest extends TestCase
{


    public $template_name = 'Bootstrap';

    use TestHelpers;

    protected function assertPreConditions(): void
    {
        parent::assertPreConditions();

        $templateDir = templates_dir() . $this->template_name;
        if (!is_dir($templateDir)) {
            $this->markTestSkipped('Template not found');
        }
    }

    #[Test]

    public function it_get_layout_bootrap_seo_meta_tags_template(): void {
        $templateName = $this->template_name;

        $author = User::where('is_admin', '=', '1')->first();

        $title = 'seotitletest' . uniqid();
        $newCleanPageId = save_content([
            'content_type' => 'page',
            'title' => $title,
            'url' => $title,
            'active_site_template' => $templateName,
            'layout_file' => 'clean.blade.php',
            'content_meta_keywords' => 'test,seo,keywords',
            'description' => 'test seo description',
            'created_by' => $author->id,
            'updated_by' => $author->id,
            'is_active' => 1,
        ]);

        $this->assertIsInt($newCleanPageId);

        // Use direct FrontendController rendering with content_id
        // (the $this->get() approach fails because permalink URL resolution
        // does not work reliably in PHPUnit test context)
        $_REQUEST['content_id'] = $newCleanPageId;
        $params = ['content_id' => $newCleanPageId];
        $frontRender = new FrontendController();
        $responseData = $frontRender->frontend($params);

        $this->assertStringContainsString('<title>' . $title . '</title>', $responseData);
        $this->assertStringContainsString('<meta name="description" content="test seo description">', $responseData);
        $this->assertStringContainsString('<meta name="keywords" content="test,seo,keywords">', $responseData);

    }


}
