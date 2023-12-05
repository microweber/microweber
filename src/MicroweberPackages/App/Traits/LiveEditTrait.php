<?php

namespace MicroweberPackages\App\Traits;

use Illuminate\Support\Facades\Vite;
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
        if(isset($page['content_type'])){
            $contentType = $page['content_type'];
        }
        $contentType = addslashes($contentType);

        $templateName = app()->template->name();
        $templateName = addslashes($templateName);

        $pageId = '';
        $contentEditLink = '';
        if (isset($page['id'])) {
            $pageId = $page['id'];
            $contentEditLink = content_edit_link($page['id']);
            $contentLink = content_link($page['id']);
        }

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
</script>\n";
        $html = str_ireplace('</head>', $contentDetailsScript . '</head>', $html, $c);
        return $html;
    }

    public function liveEditToolbarIframe($html,$content)
    {

        $liveEditUrl = admin_url() . 'live-edit';

        if (isset($content['id'])) {
            $liveEditUrl = $liveEditUrl .= '?url=' . content_link($content['id']);
        }



        $viteScript = Vite::asset('src/MicroweberPackages/LiveEdit/resources/js/ui/live-edit-page-scripts.js');

        if ($viteScript) {

            $viteScriptSrc = '<script src="' . $viteScript . '"></script>';
             $viteScriptSrc .= '<script>window.mwLiveEditIframeBackUrl = "' . $liveEditUrl . '"; </script>';
            $html = str_ireplace('</body>', $viteScriptSrc . '</body>', $html, $c);
            return $html;
        }
    }


    /**
     * @deprecated This method is deprecated and should not be used anymore.
     *
     */
    public function liveEditToolbar($html)
    {
        $toolbar = mw_includes_path() . DS . 'toolbar' . DS . 'toolbar.php';

        $layoutToolbar = new View($toolbar);
        $isEditModeBasic = false;
        $userData = app()->user_manager->get();
        if (isset($userData['basic_mode']) and trim($userData['basic_mode'] == 'y')) {
            $isEditModeBasic = true;
        }

        if (isset($isEditModeBasic) and $isEditModeBasic == true) {
            $layoutToolbar->assign('basic_mode', true);
        } else {
            $layoutToolbar->assign('basic_mode', false);
        }

        event_trigger('mw.live_edit');

        $layoutToolbar = $layoutToolbar->__toString();
        if ($layoutToolbar != '') {
            $layoutToolbar = app()->parser->process($layoutToolbar, $options = array('no_apc' => 1));
            $c = 1;
            $html = str_ireplace('</body>', $layoutToolbar . '</body>', $html, $c);
        }

        $customLiveEdit = TEMPLATES_DIR . DS . TEMPLATE_NAME . DS . 'live_edit.php';
        $customLiveEdit = normalize_path($customLiveEdit, false);
        if (is_file($customLiveEdit)) {
            $layoutLiveEdit = new View($customLiveEdit);
            $layoutLiveEdit = $layoutLiveEdit->__toString();
            if ($layoutLiveEdit != '') {
                $html = str_ireplace('</body>', $layoutLiveEdit . '</body>', $html, $c);
            }
        }

        return $html;
    }


    public function liveEditToolbarBack($html)
    {
        $tooblabBack = mw_includes_path() . DS . 'toolbar' . DS . 'toolbar_back.php';

        $layoutToolbar = new View($tooblabBack);
        $layoutToolbar = $layoutToolbar->__toString();

        if ($layoutToolbar != '') {
            $layoutToolbar = app()->parser->process($layoutToolbar);
            $c = 1;
            $html = str_ireplace('</body>', $layoutToolbar . '</body>', $html, $c);
        }

        return $html;
    }
}
