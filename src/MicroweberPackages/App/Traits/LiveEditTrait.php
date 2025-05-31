<?php

namespace MicroweberPackages\App\Traits;

use Illuminate\Support\Facades\Vite;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\View\View;

trait LiveEditTrait
{

    /**
     * Input the html to append live edit functionality
     * @param $html
     * @return mixed|string|string[]
     * @throws \Exception
     * @deprecated This method is deprecated and should not be used anymore.
     */
    public function appendLiveEdit($html)
    {
        return $html;
        if (is_admin()) {
            if (is_live_edit()) {
                if (!defined('IN_EDIT')) {
                    define('IN_EDIT', true);
                }
                $html = $this->liveEditToolbar($html);
            } else {
                $html = $this->liveEditToolbarBack($html);
            }
        }

        return $html;
    }

    public function liveEditToolbarIframeData($html, $page)
    {

        $pageTitle = addslashes($page['title']);

        $is_home = 0;
        $is_shop = 0;

        if (isset($page['is_home']) and $page['is_home'] == 1) {
            $is_home = 1;
        }
        if (isset($page['is_shop']) and $page['is_shop'] == 1) {
            $is_shop = 1;
        }

        $contentType = 'page';
        if (isset($page['content_type'])) {
            $contentType = $page['content_type'];
        }
        $contentType = addslashes($contentType);

        $templateName = app()->template_manager->name();
        $templateConfig = app()->template_manager->get_config();
        $templateComposer = app()->template_manager->get_composer_json();
        $templateName = addslashes($templateName);

        $pageId = '';
        $contentLink = '';
        $contentEditLink = '';
        if (isset($page['id'])) {
            $pageId = $page['id'];
            $contentEditLink = content_edit_link($page['id']);
            $contentLink = content_link($page['id']);
        }


        $multiLanguageIsEnabled = false;
        $current_lang = false;
        $multiLanguageEnabledLanguages = [];
         if (MultilanguageHelpers::multilanguageIsEnabled()) {
            $multiLanguageIsEnabled = true;
            $multiLanguageEnabledLanguages = MultilanguageHelpers::getSupportedLanguages();

            $current_lang = current_lang();
        }
        $multiLanguageEnabledLanguages = json_encode($multiLanguageEnabledLanguages);


        $templateConfigReady = [];
        $templateConfigReady['dir_name'] = $templateConfig['dir_name'] ?? '';
        $templateConfigReady['name'] = $templateConfig['name'] ?? '';
        $templateConfigReady['is_symlink'] = $templateConfig['is_symlink'] ?? false;


        $fonts = \MicroweberPackages\Utils\Misc\GoogleFonts::getEnabledFonts();

        $templateConfigReady['fonts'] = $fonts ;



        $templateConfigReady = json_encode($templateConfigReady);
        $templateComposer = json_encode($templateComposer);
        $contentDetailsScript = "
\n<script type='application/x-javascript' id='mw-iframe-page-data-script'>
        mw.liveEditIframeData = mw.liveEditIframeData || {};


        mw.liveEditIframeData.content = {};
        mw.liveEditIframeData.content.id = '{$pageId}';
        mw.liveEditIframeData.content.title = '{$pageTitle}';
        mw.liveEditIframeData.content.is_home = {$is_home};
        mw.liveEditIframeData.content.is_shop = {$is_shop};
        mw.liveEditIframeData.content.content_type = '{$contentType}';
        mw.liveEditIframeData.content.content_edit_link = '{$contentEditLink}';


        mw.liveEditIframeData.back_to_admin_link = '" . admin_url() . "';
        mw.liveEditIframeData.content_link = '" . $contentLink . "';
        mw.liveEditIframeData.template_name = '{$templateName}';
        mw.liveEditIframeData.template_config = {$templateConfigReady};
        mw.liveEditIframeData.template_composer = {$templateComposer};
        mw.liveEditIframeData.multiLanguageIsEnabled = '{$multiLanguageIsEnabled}';
        mw.liveEditIframeData.multiLanguageCurrentLanguage = '{$current_lang}';
        mw.liveEditIframeData.multiLanguageEnabledLanguages = {$multiLanguageEnabledLanguages};
</script>\n";
        $html = str_ireplace('</head>', $contentDetailsScript . '</head>', $html, $c);
        return $html;
    }

    public function liveEditToolbarIframe($html, $content)
    {

        $liveEditUrl = admin_url() . 'live-edit';

        if (isset($content['id'])) {
            $liveEditUrl = $liveEditUrl .= '?url=' . content_link($content['id']);
        }


        // $viteScript = Vite::asset('src/MicroweberPackages/LiveEdit/resources/js/ui/live-edit-page-scripts.js');
        //  $viteScriptCss = Vite::asset('packages/frontend-assets/resources/assets/css/scss/liveedit.scss', 'build');
        //$viteScriptCss = Vite::asset('resources/assets/css/scss/liveedit.scss', 'vendor/microweber-packages/frontend-assets/build');
        $viteScriptCss = asset('vendor/microweber-packages/frontend-assets/build/liveedit.css');

        $viteScript = asset('vendor/microweber-packages/frontend-assets/build/live-edit-page-scripts.js');
        //$viteScriptCss = public_asset() . 'vendor/microweber-packages/frontend-assets/css/live-edit-css.css';

        if ($viteScript) {


            $viteScriptSrc = '<script id="mw-live-edit-page-scripts" src="' . $viteScript . '"></script>';
            $viteScriptSrc .= '<script>window.mwLiveEditIframeBackUrl = "' . $liveEditUrl . '"; </script>';

            $viteScriptSrc .= '<link rel="stylesheet" href="' . $viteScriptCss . '">';

            $html = str_ireplace('</body>', $viteScriptSrc . '</body>', $html, $c);
            return $html;
        }
    }



    public function liveEditToolbarBack($html)
    {
        $tooblabBack = mw_includes_path() . DS . 'toolbar' . DS . 'toolbar_back.php';

        $layoutToolbar = new View($tooblabBack);
        $layoutToolbar = $layoutToolbar->__toString();

        if ($layoutToolbar != '') {
         //   $layoutToolbar = app()->parser->process($layoutToolbar);
            $c = 1;
            $html = str_ireplace('</body>', $layoutToolbar . '</body>', $html, $c);
        }

        return $html;
    }
}
