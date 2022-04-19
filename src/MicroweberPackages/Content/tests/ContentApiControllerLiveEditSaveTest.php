<?php
namespace MicroweberPackages\Content\tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\User\Models\User;

class ContentApiControllerLiveEditSaveTest extends TestCase
{
    public function testSaveContentOnPageLiveEdit()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);


        MultilanguageHelpers::setMultilanguageEnabled(0);

        $unique = uniqid('testSaveContentOnPage');
        $newCleanMlPage = save_content([
            'subtype' => 'static',
            'content_type' => 'page',
            'layout_file' => 'clean.php',
            'title' => 'pagecontentsvg'.$unique,
            'url' => 'pagecontentsvg'.$unique,
            'preview_layout_file' => 'clean.php',
            'active_site_template'=> 'new-world',
            'is_active' => 1,
        ]);

        $findPage = Page::whereId($newCleanMlPage)->first();

        $pageId = $findPage->id;
        $this->assertEquals($findPage->id, $newCleanMlPage);

        $pageLink = content_link($pageId);
        $pageLink = '/' . str_replace(site_url(),'', $pageLink);

        $_SERVER['PHP_SELF'] = '/index.php';
        $_SERVER['REQUEST_URI'] = $pageLink;
        $_SERVER['REDIRECT_URL'] = $pageLink;
        $_SERVER['HTTP_REFERER'] = content_link($pageId);


        // Save on default lang


        $contentFieldHtml = <<<HTML
        <div class="feature-icon bg-primary bg-gradient">
                    <svg class="bi" width="1em" height="1em"><use xlink:href="#collection"></use></svg>
                </div>
                <h2>Featured title</h2>
                <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence and probably just keep going until we run out of words.</p>
                <a href="#" class="icon-link">
                    Call to action
      </a><a class="mb-2" href=""><i class="mdi mdi-arrow-right"></i></a>
HTML;


        $fieldsData = [
            'field_data_0'=>[
                'attributes'=>[
                    'class'=>'container edit',
                    'rel'=>'content',
                    'rel_id'=>$findPage->id,
                    'field'=>'content',
                ],
                'html'=>$contentFieldHtml
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

    }

}
