<?php

namespace MicroweberPackages\MetaTags\tests;

use MicroweberPackages\Content\tests\TestHelpers;
use MicroweberPackages\User\Models\User;

class TemplateMetaTagsSeoTagsTest extends \MicroweberPackages\Core\tests\TestCase
{


    public $template_name = 'Bootstrap';

    use TestHelpers;

    protected function assertPreConditions(): void
    {
        parent::assertPreConditions();

        $is_dir = templates_dir() . $this->template_name;
        if (!$is_dir) {
            $this->markTestSkipped('Template not found');
        }

    }

    public function testGetLayoutBootrapSeoMetaTagsTemplate()
    {
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
        $response = $this->get($title);
        $responseData = $response->getContent();

        $this->assertStringContainsString('<title>' . $title . '</title>', $responseData);
        $this->assertStringContainsString('<meta name="description" content="test seo description">', $responseData);
        $this->assertStringContainsString('<meta name="keywords" content="test,seo,keywords">', $responseData);

    }


}
