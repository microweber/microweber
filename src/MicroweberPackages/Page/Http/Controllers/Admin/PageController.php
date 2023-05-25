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
        $data['page_design'] = false;
        if (isset($request_data['page_design'])) {
            $data['page_design'] = $request_data['page_design'];
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

        $data['layouts'] = $layouts;

        return view('page::admin.page.create', $data);
    }

    public function edit(Request $request, $id) {
        return view('page::admin.page.edit', [
            'content_id'=>intval($id)
        ]);
    }
}
