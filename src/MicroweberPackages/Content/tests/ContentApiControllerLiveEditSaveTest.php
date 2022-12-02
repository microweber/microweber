<?php

namespace MicroweberPackages\Content\tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Helper\XSSClean;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\User\Models\User;

class ContentApiControllerLiveEditSaveTest extends TestCase
{
    public function testSaveContentOnPageLiveEditSingle()
    {
        $this->cleanupAndPrepare();

        $unique = uniqid('testSaveContentOnPage');
        $newCleanMlPage = save_content([
            'subtype' => 'static',
            'content_type' => 'page',
            'layout_file' => 'clean.php',
            'title' => 'pagecontent222' . $unique,
            'url' => 'pagecontent222' . $unique,
            'preview_layout_file' => 'clean.php',
            'active_site_template' => 'new-world',
            'is_active' => 1,
        ]);

        $findPage = Page::whereId($newCleanMlPage)->first();

        $pageId = $findPage->id;
        $this->assertEquals($findPage->id, $newCleanMlPage);

        $pageLink = content_link($pageId);
        $pageLink = '/' . str_replace(site_url(), '', $pageLink);

        $_SERVER['PHP_SELF'] = '/index.php';
        $_SERVER['REQ`UEST_URI'] = $pageLink;
        $_SERVER['REDIRECT_URL'] = $pageLink;
        $_SERVER['HTTP_REFERER'] = content_link($pageId);


        // Save on default lang


        $contentFieldHtml = <<<HTML
<div class="feature-icon bg-primary bg-gradient">
<svg class="bi" width="1em" height="1em"><use xlink:href="#collection"></use></svg>
</div>
<div class="feature-icon bg-primary bg-gradient">
<h2>Featured title</h2>
<p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence and probably just keep going until we run out of words.</p>
<a href="#" class="icon-link">
Call to action
</a>
<a class="mb-2" href=""><i class="mdi mdi-arrow-right"></i></a>
<a class="mb-2" href="https://example.com"><i class="mdi mdi-arrow-up"></i>example link</a>
<a class="mb-3" target="_blank" href="https://example.com/2"><i class="mdi mdi-arrow-left-bold-box"></i>link 2</a>
</div>
HTML;


        $fieldsData = [
            'field_data_0' => [
                'attributes' => [
                    'class' => 'container edit',
                    'rel' => 'content',
                    'rel_id' => $findPage->id,
                    'field' => 'content',
                ],
                'html' => $contentFieldHtml
            ]
        ];
        $encoded = base64_encode(json_encode($fieldsData));

        $response = $this->call(
            'POST',
            route('api.content.save_edit'),
            [
                'data_base64' => $encoded,
            ],
            [],//params
            $_COOKIE,//cookie
            [],//files
            $_SERVER //server
        );


        $fieldSaved = $response->decodeResponseJson();

        return;

        $this->assertEquals(trim($fieldSaved[0]['content']), trim($contentFieldHtml));
        $this->assertEquals($fieldSaved[0]['rel_type'], 'content');
        $this->assertEquals($fieldSaved[0]['field'], 'content');
        $findPage = Page::whereId($findPage->id)->first();

        $this->assertTrue(str_contains($findPage->content, 'svg class="bi" width="1em" height="1em"'));
        $this->assertTrue(str_contains($findPage->content, '<h2>Featured title</h2>'));
        $this->assertTrue(str_contains($findPage->content, '<a class="mb-2" href="https://example.com"><i class="mdi mdi-arrow-up"></i>example link</a>'));
        $this->assertTrue(str_contains($findPage->content, '<a class="mb-3" target="_blank" href="https://example.com/2"><i class="mdi mdi-arrow-left-bold-box"></i>link 2</a>'));


    }


    public function testSaveContentOnPageLiveEditFromBootstrapExamples()
    {
        $this->cleanupAndPrepare();
        $unique = uniqid('testSaveContentOnPage');
        $newCleanMlPage = save_content([
            'subtype' => 'static',
            'content_type' => 'page',
            'layout_file' => 'clean.php',
            'title' => 'pagecontent-bs-examples-save' . $unique,
            'preview_layout_file' => 'clean.php',
            'is_active' => 1,
        ]);



        $findPage = Page::whereId($newCleanMlPage)->first();

        $pageId = $findPage->id;
        $this->assertEquals($findPage->id, $newCleanMlPage);

        $pageLink = content_link($pageId);
        $pageLink = '/' . str_replace(site_url(), '', $pageLink);

        $_SERVER['PHP_SELF'] = '/index.php';
        $_SERVER['REQ`UEST_URI'] = $pageLink;
        $_SERVER['REDIRECT_URL'] = $pageLink;
        $_SERVER['HTTP_REFERER'] = content_link($pageId);

        $zipname = __DIR__ . '/../../Helper/tests/misc/bootstrap-5.0.2-examples.zip';


        //

        $zip = new \ZipArchive();

        $htmls = [];
        if ($zip->open($zipname)) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fn = $zip->getNameIndex($i);
                $ext = get_file_extension($fn);
                if ($ext == 'html') {
                    $string = $zip->getFromName($fn);
                    $htmls[$fn] = $string;
                }
            }
        }
        $zip->close();


        $this->assertTrue(!empty($htmls));


        foreach ($htmls as $k => $html) {


            //remove empty tags
           $html =  preg_replace('/<[^\/>]*>([\s]?)*<\/[^>]*>/', '', $html);

            // remove script tags
            $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $html);

             // remove code tags
            $html = preg_replace('/<code\b[^>]*>(.*?)<\/code>/is', "", $html);


            $l = $html;
            $pq = \phpQuery::newDocument($l);

            // $isolated_html = pq('main')->eq(0)->htmlOuter();
            $contentFieldHtml = pq('main')->eq(0)->htmlOuter();


            $fieldsData = [
                'field_data_0' => [
                    'attributes' => [
                        'class' => 'container edit',
                        'rel' => 'content',
                        'rel_id' => $findPage->id,
                        'field' => 'content',
                    ],
                    'html' => $contentFieldHtml
                ]
            ];




            $encoded = base64_encode(json_encode($fieldsData));

            $response = $this->call(
                'POST',
                route('api.content.save_edit'),
                [
                    'data_base64' => $encoded,
                ],
                [],//params
                $_COOKIE,//cookie
                [],//files
                $_SERVER //server
            );


            $fieldSaved = $response->decodeResponseJson();

            $this->assertEquals(trim($fieldSaved[0]['content']), trim($contentFieldHtml));
            $this->assertEquals($fieldSaved[0]['rel_type'], 'content');
            $this->assertEquals($fieldSaved[0]['field'], 'content');



            $pq2 = \phpQuery::newDocument($contentFieldHtml);
            $this->assertEquals($contentFieldHtml, $pq2->htmlOuter());


            $findPage = Page::whereId($fieldSaved[0]['id'])->first();
            $contentFieldHtml1 = trim($contentFieldHtml);
            $contentFieldHtml2 = trim($findPage->content);

            $this->assertEquals($contentFieldHtml1, $contentFieldHtml2);
            $this->assertEquals($contentFieldHtml, $findPage->content);


        }


    }


    public function testSaveContentOnPageLiveEditXssList()
    {

        $this->cleanupAndPrepare();

        $unique = uniqid('testSaveContentOnPageLiveEditXssList');
        $newCleanMlPage = save_content([
            'subtype' => 'static',
            'content_type' => 'page',
            'layout_file' => 'clean.php',
            'title' => 'xss-test-' . $unique,
            'preview_layout_file' => 'clean.php',
            'is_active' => 1,
        ]);

        $findPage = Page::whereId($newCleanMlPage)->first();

        $pageId = $findPage->id;
        $this->assertEquals($findPage->id, $newCleanMlPage);

        $pageLink = content_link($pageId);
        $pageLink = '/' . str_replace(site_url(), '', $pageLink);

        $_SERVER['PHP_SELF'] = '/index.php';
        $_SERVER['REQ`UEST_URI'] = $pageLink;
        $_SERVER['REDIRECT_URL'] = $pageLink;
        $_SERVER['HTTP_REFERER'] = content_link($pageId);



        $zip = new \ZipArchive();
        $zip->open(__DIR__ . '/../../Helper/tests/misc/xss-test-files.zip');
        $xssList = $zip->getFromName('xss-payload-list.txt');
        $zip->close();

        $xssList = preg_replace('~\R~u', "\r\n", $xssList);
        $xssList = explode(PHP_EOL, $xssList);


        $xssListChunks = array_chunk($xssList, 100);


        foreach ($xssListChunks as $stringChunk) {

            $string = implode(PHP_EOL, $stringChunk);
             if (empty(trim($string))) {
                continue;
            }

            $contentFieldHtml = $string;

            $fieldsData = [
                'field_data_0' => [
                    'attributes' => [
                        'class' => 'container edit',
                        'rel' => 'content',
                        'rel_id' => $findPage->id,
                        'field' => 'content',
                    ],
                    'html' => $contentFieldHtml
                ]
            ];

            $encoded = base64_encode(json_encode($fieldsData));

            $response = $this->call(
                'POST',
                route('api.content.save_edit'),
                [
                    'data_base64' => $encoded,
                ],
                [],//params
                $_COOKIE,//cookie
                [],//files
                $_SERVER //server
            );

            $fieldSaved = $response->decodeResponseJson();

            $this->assertNotEquals(trim($fieldSaved[0]['content']), trim($contentFieldHtml));
            $this->assertEquals($fieldSaved[0]['rel_type'], 'content');
            $this->assertEquals($fieldSaved[0]['field'], 'content');

            $findPage = Page::whereId($fieldSaved[0]['id'])->first();
            $contentFieldHtml1 = trim($contentFieldHtml);
            $contentFieldHtml2 = trim($findPage->content);

           $this->assertNotEquals($contentFieldHtml1, $contentFieldHtml2);


            foreach ($stringChunk as $stringItem) {
                if (trim($stringItem) == '') {
                    continue;
                }
                $this->assertStringNotContainsString($stringItem, $findPage->content);
            }



        }
    }



    public function testSaveContentOnPageLiveEditFromOtherExamples()
    {
        $this->cleanupAndPrepare();


        $unique = uniqid('testSaveContentOnPageLiveEditFromOtherExamples');
        $newCleanMlPage = save_content([
            'subtype' => 'static',
            'content_type' => 'page',
            'layout_file' => 'clean.php',
            'title' => 'pagecontent-other-examples-save' . $unique,
            'preview_layout_file' => 'clean.php',
            'is_active' => 1,
        ]);

        $findPage = Page::whereId($newCleanMlPage)->first();

        $pageId = $findPage->id;
        $this->assertEquals($findPage->id, $newCleanMlPage);

        $pageLink = content_link($pageId);
        $pageLink = '/' . str_replace(site_url(), '', $pageLink);

        $_SERVER['PHP_SELF'] = '/index.php';
        $_SERVER['REQ`UEST_URI'] = $pageLink;
        $_SERVER['REDIRECT_URL'] = $pageLink;
        $_SERVER['HTTP_REFERER'] = content_link($pageId);

        $zipname = __DIR__ . '/../../Helper/tests/misc/edit-fields-other-html.zip';


        $zip = new \ZipArchive();

        $htmls = [];
        if ($zip->open($zipname)) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fn = $zip->getNameIndex($i);
                $ext = get_file_extension($fn);
                if ($ext == 'html') {
                    $string = $zip->getFromName($fn);
                    if(trim($string) == ''){
                        continue;
                    }
                    $htmls[$fn] = $string;
                }
            }
        }
        $zip->close();


        $this->assertTrue(!empty($htmls));

        foreach ($htmls as $k => $html) {

            $contentFieldHtml = $html;
            $contentFieldHtml = self::fixLinksPrecentAttributes($contentFieldHtml);

            $contentFieldHtmlTest1 = app()->parser->make_tags($contentFieldHtml);
            $this->assertEquals($contentFieldHtmlTest1, $contentFieldHtml);


            $xssClean = new XSSClean();
            $contentFieldHtmlTest1 = $xssClean->clean($contentFieldHtml);
            $this->assertEquals($contentFieldHtmlTest1, $contentFieldHtml);


        }

        $htmlsChunks = array_chunk($htmls, 100);



      foreach ($htmlsChunks as $k => $htmlChunk) {

          $html = implode('--------chunk-------', $htmlChunk);
         $contentFieldHtml = $html;


           // $contentFieldHtml = str_replace('{SITE_URL}', site_url(), $contentFieldHtml);
            $fieldsData = [
                'field_data_0' => [
                    'attributes' => [
                        'class' => 'container edit',
                        'rel' => 'content',
                        'rel_id' => $findPage->id,
                        'field' => 'content',
                    ],
                    'html' => $contentFieldHtml
                ]
            ];

            $contentFieldHtml = self::fixLinksPrecentAttributes($contentFieldHtml);


            $encoded = base64_encode(json_encode($fieldsData));

            $response = $this->call(
                'POST',
                route('api.content.save_edit'),
                [
                    'data_base64' => $encoded,
                ],
                [],//params
                $_COOKIE,//cookie
                [],//files
                $_SERVER //server
            );


            $fieldSaved = $response->decodeResponseJson();



            $findPage = Page::whereId($fieldSaved[0]['id'])->first();
            $contentFieldHtml1 = trim($contentFieldHtml);
            $contentFieldHtml2 = trim($findPage->content);

            $this->assertEquals($contentFieldHtml1, $contentFieldHtml2);
            $this->assertEquals($contentFieldHtml, $findPage->content);


       }


    }


    private function cleanupAndPrepare(){
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);
        \Config::set('microweber.disable_model_cache', 1);


        MultilanguageHelpers::setMultilanguageEnabled(0);
        Page::truncate();
    }

    public static function fixLinksPrecentAttributes($text)
    {
        $text = str_ireplace('{SITE_URL}','___mw-site-url-temp-replace-on-clean___', $text);
        $pq = \phpQuery::newDocument($text);

        foreach($pq->find('a') as $stuffs)
        {
            $href = pq($stuffs)->attr('href');
            if($href){
            pq($stuffs)->attr('href', str_replace(' ', '%20', $href));
            }
        }
        $text = $pq->htmlOuter();
        $text = str_ireplace('___mw-site-url-temp-replace-on-clean___','{SITE_URL}',     $text);
        return $text;

    }


}
