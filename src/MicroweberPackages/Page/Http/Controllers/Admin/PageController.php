<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */
namespace MicroweberPackages\Page\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Admin\Http\Controllers\AdminController;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Page\Repositories\PageRepository;

class PageController extends AdminController
{
    public $repository;

    public function __construct(PageRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    public function index(Request $request) {
        return view('page::admin.page.index');
    }

    public function create(Request $request)
    {
        $request_data = $request->all();

        $data = [];
        $data['layout'] = false;
        if (isset($request_data['layout'])) {
            $data['layout'] = $request_data['layout'];
        }

        $data['content_id'] = 0;
        $data['recommended_category_id'] = 0;
        $data['recommended_content_id'] = 0;
        if (isset($request_data['recommended_category_id'])) {
            $data['recommended_category_id'] = intval($request_data['recommended_category_id']);
        }
        if (isset($request_data['recommended_content_id'])) {
            $data['recommended_content_id'] = intval($request_data['recommended_content_id']);
        }

        if (!defined('ACTIVE_SITE_TEMPLATE')) {
            app()->content_manager->define_constants($data);
        }

        $layout_options = array();
        $layout_options['site_template'] = ACTIVE_SITE_TEMPLATE;
        $layout_options['no_cache'] = true;
        $layout_options['no_folder_sort'] = true;

        $layouts = mw()->layouts_manager->get_all($layout_options);

        $data['allLayouts'] = $layouts;

        $data['content_type'] = 'page';

        return view('page::admin.page.create', $data);
    }

    public function edit(Request $request, $id) {

        $data = [];
        $data['content_type'] = 'page';
        $data['content_id'] = intval($id);

        return view('page::admin.page.edit', $data);
    }

    public function design() {

        $data = [];

        if (!defined('ACTIVE_SITE_TEMPLATE')) {
            app()->content_manager->define_constants($data);
        }

        $templateName = '';
        $templateVersion = '';
        $templateConfigFile = template_dir() . 'config.php';
        if (is_file($templateConfigFile)) {
            include $templateConfigFile;
            $templateName = $config['name'];
            $templateVersion = $config['version'];
        }

        $data['templateName'] = $templateName;
        $data['templateVersion'] = $templateVersion;

        $layout_options = array();
        $layout_options['site_template'] = ACTIVE_SITE_TEMPLATE;
        $layout_options['no_cache'] = true;
        $layout_options['no_folder_sort'] = true;

        $layoutUrls = [];

        $getPages = get_pages();
//        $getLayouts = mw()->layouts_manager->get_all($layout_options);
//        foreach ($getLayouts as $layout) {
//
//            $layoutUrl = [];
//            $layoutUrl['preview_url'] = $layout['layout_file_preview_url'];
//            $layoutUrl['name'] = $layout['name'];
//            $layoutUrl['edit_url'] = false;
//            $layoutUrl['create_url'] = route('admin.page.create') . '?layout=' . $layout['layout_file_preview'];
//            $layoutUrl['pages'] = [];
//
//            foreach ($getPages as $page) {
//
//                $pageUrl = [];
//                $pageUrl['name'] = $page['title'];
//                $pageUrl['edit_url'] = $page['url'] . '?editmode=y';
//                $pageUrl['create_url'] = false;
//                $pageUrl['preview_url'] = $page['url'] . '?no_editmode=true';
//
//                if (!empty($page['layout_file']) && $page['layout_file'] == $layout['layout_file_preview']) {
//                    $layoutUrl['pages'][] = $pageUrl;
//                }
//            }
//
//            if (count($layoutUrl['pages']) == 1) {
//                $layoutUrl['create_url'] = false;
//                $layoutUrl['edit_url'] = $layoutUrl['pages'][0]['edit_url'];
//            } else {
//                $layoutUrl['pages'][] = [
//                  'name' => 'Create new page',
//                   'edit_url' => false,
//                   'create_url' => $layoutUrl['create_url'],
//                     'preview_url' => $layout['layout_file_preview_url']
//                ];
//            }
//
//            $layoutUrls[] = $layoutUrl;
//        }


        $layoutUrls = [];
        foreach ($getPages as $page) {
            $pageUrl = [];
            $pageUrl['name'] = $page['title'];
            $pageUrl['edit_url'] = $page['url'] . '?editmode=y';
            $pageUrl['create_url'] = false;
            $pageUrl['preview_url'] = $page['url'] . '?no_editmode=true';
            $layoutUrls[] = $pageUrl;
        }

        $data['allLayouts'] = $layoutUrls;

        return view('page::admin.page.design', $data);
    }
}
