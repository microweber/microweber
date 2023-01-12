<?php

namespace MicroweberPackages\Admin\Http\Controllers;

use Illuminate\Routing\Controller;
use MicroweberPackages\App\Traits\LiveEditTrait;
use MicroweberPackages\View\View;


class AdminEditorToolsController
{

    public function index($tool = false)
    {

        if (!defined('IN_ADMIN') and is_admin()) {
            define('IN_ADMIN', true);
        }
        if (!defined('IN_EDITOR_TOOLS')) {
            define('IN_EDITOR_TOOLS', true);
        }

        if (mw_is_installed() == true) {

            //event_trigger('mw_db_init');
            //  event_trigger('mw_cron');
        }

        if (!$tool) {
            $tool = app()->url_manager->segment(1);

            if ($tool) {
            } else {
                $tool = 'index';
            }
        }
        $page = false;
        if (isset($_REQUEST['content_id'])) {
            if (intval($_REQUEST['content_id']) == 0) {
                $this->create_new_page = true;

                $custom_content_data_req = $_REQUEST;
                $custom_content_data = array();
                if (isset($custom_content_data_req['content_type'])) {
                    //    $custom_content_data['content_type'] = $custom_content_data_req['content_type'];
                }
                if (isset($custom_content_data_req['content_type'])) {
                    $custom_content_data['content_type'] = $custom_content_data_req['content_type'];
                }
                if (isset($custom_content_data_req['subtype'])) {
                    $custom_content_data['subtype'] = $custom_content_data_req['subtype'];
                }
                if (isset($custom_content_data_req['parent_page']) and is_numeric($custom_content_data_req['parent_page'])) {
                    $custom_content_data['parent'] = intval($custom_content_data_req['parent_page']);
                }
                if (isset($custom_content_data_req['preview_layout'])) {
                    //  $custom_content_data['preview_layout'] =($custom_content_data_req['preview_layout']);
                }
                if (!empty($custom_content_data)) {
                    $custom_content_data['id'] = 0;
                    $this->content_data = $custom_content_data;
                }

                $this->return_data = 1;
                $page = $this->frontend();
            } else {
                $page = app()->content_manager->get_by_id($_REQUEST['content_id']);
            }
        } elseif (isset($_SERVER['HTTP_REFERER'])) {
            $url = $_SERVER['HTTP_REFERER'];
            $url = explode('?', $url);
            $url = $url[0];

            if (trim($url) == '' or trim($url) == app()->url_manager->site()) {

                //$page = app()->content_manager->get_by_url($url);
                $page = app()->content_manager->homepage();
            } else {
                $page = app()->content_manager->get_by_url($url);
            }
        } else {
            $url = app()->url_manager->string();
        }

        if (!isset($page['active_site_template'])) {
            $page['active_site_template'] = 'default';
        }

        if (isset($_GET['preview_template'])) {
            $page['active_site_template'] = $_GET['preview_template'];
        }
        if (isset($_GET['content_type'])) {
            $page['content_type'] = $_GET['content_type'];
        }
        if (isset($_GET['preview_layout']) and $_GET['preview_layout'] != 'inherit') {
            $page['layout_file'] = $_GET['preview_layout'];
        }

        app()->content_manager->define_constants($page);

        $page['render_file'] = app()->template->get_layout($page);

        if (defined('TEMPLATE_DIR')) {
            app()->template_manager->boot_template();
        }

        // $params = $_REQUEST;
        $params = array_merge($_GET, $_POST);
        $tool = sanitize_path($tool);

        $p_index = mw_includes_path() . 'toolbar/editor_tools/index.php';
        $p_index = normalize_path($p_index, false);

        $standalone_edit = true;
        $p = mw_includes_path() . 'toolbar/editor_tools/' . $tool . '/index.php';
        $standalone_edit = false;
        if ($tool == 'plupload') {
            $standalone_edit = true;
        }
        if ($tool == 'plupload') {
            $standalone_edit = true;
        }
        if ($tool == 'imageeditor') {
            $standalone_edit = true;
        }

        if ($tool == 'rte_image_editor') {
            $standalone_edit = true;
        }
        if ($tool == 'editor_toolbar') {
            $standalone_edit = true;
        }

        if ($tool == 'wysiwyg') {
            $standalone_edit = false;
            $ed_file_from_template = TEMPLATE_DIR . 'editor.php';

            if (is_file($ed_file_from_template)) {
                $p_index = $ed_file_from_template;
            }

            if (isset($page['content_type']) and $page['content_type'] != 'post' and $page['content_type'] != 'page' and $page['content_type'] != 'product') {
                if (isset($page['subtype']) and ($page['subtype'] != 'post' and $page['subtype'] != 'product')) {
                    $standalone_edit = true;
                }
            } elseif (isset($page['content_type']) and $page['content_type'] == 'post') {
                if (isset($page['subtype']) and ($page['subtype'] != 'post' and $page['subtype'] != 'product')) {
                    $standalone_edit = true;
                }
            }

            if ($standalone_edit) {
                if (!isset($page['content'])) {
                    $page['content'] = '<div class="element"></div>';
                }
                $page['content'] = '<div class="edit" field="content" rel="content" contenteditable="true">' . $page['content'] . '</div>';
                $page['render_file'] = false;
            }

            //
            //  $page['content'] = '<div class="edit" field="content" rel="content" contenteditable="true">' . $page['content'] . '</div>';
        }
        $default_css = '';
        $apijs_settings_loaded = '';
        $apijs_loaded = '';

        $p = normalize_path($p, false);

        $l = new View($p_index);
        $l->params = $params;
        $layout = $l->__toString();
        $apijs_loaded = false;
        if ($layout != false) {

            $headers = event_trigger('site_header', TEMPLATE_NAME);
            $template_headers_append = '';
            $one = 1;
            if (is_array($headers)) {
                foreach ($headers as $modify) {
                    if ($modify != false and is_string($modify) and $modify != '') {
                        $template_headers_append = $template_headers_append . $modify;
                    }
                }
                if ($template_headers_append != false and $template_headers_append != '') {
                    $layout = str_ireplace('</head>', $template_headers_append . '</head>', $l, $one);
                }
            }
            if (function_exists('template_headers_src')) {
                $template_headers_src = template_headers_src();
                if ($template_headers_src != false and $template_headers_src != '') {
                    $layout = str_ireplace('</head>', $template_headers_src . '</head>', $l, $one);
                }
            }

            if (isset($page['active_site_template'])) {
                if ($page['active_site_template'] == '') {
                    $page['active_site_template'] = 'default';
                }

                if ($page['active_site_template'] == 'default') {
                    $active_site_template = app()->option_manager->get('current_template', 'template');
                } else {
                    $active_site_template = $page['active_site_template'];
                    if ($active_site_template == 'mw_default') {
                        $active_site_template = 'default';
                    }
                }

                $live_edit_css_folder = userfiles_path() . 'css' . DS . $active_site_template . DS;
                $custom_live_edit = $live_edit_css_folder . DS . 'live_edit.css';
                if (is_file($custom_live_edit)) {
                    $live_edit_url_folder = userfiles_url() . 'css/' . $active_site_template . '/';
                    $custom_live_editmtime = filemtime($custom_live_edit);
                    $liv_ed_css = '<link rel="stylesheet" href="' . $live_edit_url_folder . 'live_edit.css?version=' . $custom_live_editmtime . '" id="mw-template-settings" type="text/css" />';
                    $layout = str_ireplace('</head>', $liv_ed_css . '</head>', $l);
                }
            }
        }

        if (isset($_REQUEST['plain'])) {
            if (is_file($p)) {
                $p = new View($p);
                $p->params = $params;
                $layout = $p->__toString();
                return response($layout);

            }
        } elseif (is_file($p)) {
            $p = new View($p);
            $p->params = $params;
            $layout_tool = $p->__toString();
            $layout = str_replace('{content}', $layout_tool, $layout);
        } else {
            $layout = str_replace('{content}', 'Not found!', $layout);
        }
        $category = false;
        if (defined('CATEGORY_ID')) {
            $category = app()->category_manager->get_by_id(CATEGORY_ID);
        }

        //    $page['render_file'] = $render_file;

        if (!$standalone_edit and $tool == 'wysiwyg') {
            if (isset($page['render_file'])) {
                if (!isset($page['layout_file'])) {
                    $page['layout_file'] = str_replace(template_dir(), '', $page['render_file']);
                }


                event_trigger('mw.front', $page);
                $l = new View($page['render_file']);
                $l->page_id = PAGE_ID;
                $l->content_id = CONTENT_ID;
                $l->post_id = POST_ID;
                $l->category_id = CATEGORY_ID;
                $l->content = $page;
                $l->category = $category;
                $l->params = $params;
                $l->page = $page;
                $l->application = $this->app;
                $l = $l->__toString();

                if (is_object($l)) {
                    return $l;
                }

                $l = app()->parser->process($l, $options = false);



                $editable = app()->parser->isolate_content_field($l, true);

                if ($editable != false) {
                    $page['content'] = $editable;
                } else {
                    if ($tool == 'wysiwyg') {
                        $err = 'no editable content region found';
                        if (isset($page['layout_file'])) {
                            $file = $page['layout_file'];
                            $file = str_replace('__', '/', $page['layout_file']);
                            $err = $err . ' in file ' . $file;
                        }
                        if (isset($page['active_site_template'])) {
                            $err = $err . ' (' . $page['active_site_template'] . ' template)';
                        }

                        return $err;
                    }
                }
            }
        }

         if (isset($page['content'])) {
            if ($standalone_edit) {
                if (!isset($render_file)) {
                    if (stristr($page['content'], 'field="content"') or stristr($page['content'], 'field=\'content\'')) {
                        $page['content'] = '<div class="edit" field="content" rel="content" contenteditable="true">' . $page['content'] . '</div>';
                    }
                }
            }

            $layout = str_replace('{content}', $page['content'], $layout);
        }


        $layout = app()->parser->process($layout, $options = false);

        $layout = execute_document_ready($layout);

        $layout = str_replace('{head}', '', $layout);

        $layout = str_replace('{content}', '', $layout);
        return response($layout);


    }
}
