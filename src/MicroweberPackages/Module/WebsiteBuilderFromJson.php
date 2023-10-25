<?php

namespace MicroweberPackages\Module;

use MicroweberPackages\App\Http\RequestRoute;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\ContentField\Models\ContentField;
use MicroweberPackages\Menu\Menu;
use MicroweberPackages\Page\Models\Page;

class WebsiteBuilderFromJson
{
    public function run() {

        Menu::truncate();
        Page::truncate();
        Content::truncate();
        ContentField::truncate();

        $websiteJson = file_get_contents(__DIR__ . '/my-website-structure.json');
        $websiteJson = json_decode($websiteJson, true);

        if (isset($websiteJson['pages'])) {
            foreach ($websiteJson['pages'] as $page) {

                $newPage = new Page();
                $newPage->title = $page['title'];

                if (isset($page['is_home'])) {
                    $newPage->is_home = $page['is_home'];
                }

                $newPage->url = $page['url'];
                $newPage->layout_file = 'clean.php';
                $newPage->save();

                if (isset($page['layouts'])) {
                    foreach ($page['layouts'] as $layout) {
                        $this->buildLayout($layout, $newPage->id);
                    }
                }

            }
        }

     //   clearcache();
    }

    public function buildLayout($layout, $pageId)
    {
        $findPage = Page::where('id', $pageId)->first();
        if ($findPage) {

            $pageContent = $findPage->content;

            $_SERVER['HTTP_REFERER'] = site_url();
            $_POST['template'] = $layout['layout_file'];
            $_POST['module'] = 'layouts';
            $_POST['id'] = 'mw-module-'.random_int(11111111111, 99999999999);

            $newContent = RequestRoute::postJson(route('api.module.render'), []);
            if (strpos($newContent, 'Click here to select layout') !== false) {
                return;
            }

            $pageContent .= $newContent;

            $findPage->content = $pageContent;
            $findPage->save();

        }
    }
}
