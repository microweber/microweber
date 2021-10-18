<?php
namespace MicroweberPackages\Multilanguage\tests;

use MicroweberPackages\Page\Models\Page;

class MultilanguageLiveEditTest extends MultilanguageTestBase
{
    public function testSaveContentOnPage()
    {
        $newCleanMlPage = save_content([
           'subtype' => 'static',
           'content_type' => 'page',
           'layout_file' => 'clean.php',
           'title' => 'LiveEditMultilanguagePage',
           'url' => 'liveeditmultilanguagepage',
           'preview_layout_file' => 'clean.php',
           'is_active' => 1,
        ]);

        $fingPage = Page::whereId($newCleanMlPage)->first();
        $this->assertEquals($fingPage->id, $newCleanMlPage);



    }
}
