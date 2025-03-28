<?php

namespace Modules\Content\Tests\Unit;

use Illuminate\Support\Facades\Config;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Helper\XSSClean;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use Modules\Page\Models\Page;


class ContentApiControllerLiveEditSaveTestXss extends TestCase
{



    public function testSaveContentOnPageLiveEditXssList()
    {

        $this->loginAsAdmin();

        $unique = uniqid('testSaveContentOnPageLiveEditXssList');
        $newCleanMlPage = save_content([
            'subtype' => 'static',
            'content_type' => 'page',
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
        $zip->open(base_path() . '/src/MicroweberPackages/Helper/tests/misc/xss-test-files.zip');
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



    public function testSaveContentOnPageLiveEditSaveWithXssInRefferer()
    {
        $this->loginAsAdmin();

      $unique = uniqid('testSaveContentOnPageLiveEditSaceWithXssInRefferer');

        $pageLink = site_url().'ref-'.$unique.'<script>alert(1)</script>';
        $expected = site_url().'ref-'.strtolower($unique);

        $_SERVER['PHP_SELF'] = '/index.php';
        $_SERVER['REQUEST_URI'] = $pageLink;
        $_SERVER['REDIRECT_URL'] = $pageLink;
        $_SERVER['HTTP_REFERER'] =$pageLink ;

        // Save on default lang


        $contentFieldHtml = <<<HTML
text $unique
HTML;

        $fieldsData = [
            'field_data_0' => [
                'attributes' => [
                    'class' => 'container edit',
                    'rel' => 'content',
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
        $this->assertEquals(strtolower($fieldSaved['new_page_url']), strtolower($expected));
        $this->assertEquals($fieldSaved[0]['content'],  $contentFieldHtml);
     }




    public function testSaveContentXssSpaceInUrlAttribute()
    {

        $this->loginAsAdmin();
        $unique = uniqid('testSaveContentXssInUrlAttribute');
        $url = 'url with space ' . $unique . '  <script>alert(1)</script>';
        $newCleanPage = save_content([
            'subtype' => 'static',
            'content_type' => 'page',
            'layout_file' => 'clean.php',
            'title' => 'testSaveContentXssInUrlAttribute-save' . $unique,
            'preview_layout_file' => 'clean.php',
            'url' =>$url,
            'is_active' => 1,
        ]);

        $findPage = Page::whereId($newCleanPage)->first();


        $this->assertNotEquals($findPage->url, $url);

    }



    private function cleanupAndPrepare()
    {
       $this->loginAsAdmin();
        Config::set('microweber.disable_model_cache', 1);


        MultilanguageHelpers::setMultilanguageEnabled(0);
      //  Page::truncate();
    }

    public static function fixLinksPrecentAttributes($text)
    {
        $text = str_ireplace('{SITE_URL}', '___mw-site-url-temp-replace-on-clean___', $text);
        $pq = \phpQuery::newDocument($text);

        foreach ($pq->find('a') as $stuffs) {
            $href = pq($stuffs)->attr('href');
            if ($href) {
                pq($stuffs)->attr('href', str_replace(' ', '%20', $href));
            }
        }
        $text = $pq->htmlOuter();
        $text = str_ireplace('___mw-site-url-temp-replace-on-clean___', '{SITE_URL}', $text);
        return $text;

    }


}
