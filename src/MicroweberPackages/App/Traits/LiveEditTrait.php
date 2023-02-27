<?php
namespace MicroweberPackages\App\Traits;

use MicroweberPackages\View\View;

trait LiveEditTrait {

    /**
     * Input the html to append live edit functionality
     * @param $html
     * @return mixed|string|string[]
     * @throws \Exception
     */
    public function appendLiveEdit($html)
    {
        if (is_admin()) {
            if (is_live_edit()) {
                if (!defined('IN_EDIT')) {
                    define('IN_EDIT',true);
                }
                $html = $this->liveEditToolbar($html);
            } else {
                $html = $this->liveEditToolbarBack($html);
            }
        }

        return $html;
    }

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
