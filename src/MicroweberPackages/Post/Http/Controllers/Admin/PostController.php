<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */
namespace MicroweberPackages\Post\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\Admin\Http\Controllers\AdminController;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Post\Http\Requests\PostRequest;
use MicroweberPackages\Post\Repositories\PostRepository;

class PostController extends AdminController
{
    public $repository;

    public function __construct(PostRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    public function index(Request $request) {
        return view('post::admin.posts.index');
    }

    public function create(Request $request) {

        $request_data = $request->all();

        $data = [];

        $data['post_design'] = false;
        if (isset($request_data['layout'])) {
            $data['post_design'] = $request_data['layout'];
        }

        $data['content_type'] = 'post';
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
        $layout_options['content_type'] = 'post';

        $layouts = mw()->layouts_manager->get_all($layout_options);
        if (empty($layouts)) {
            return view('post::admin.posts.edit',$data);
        }

        $data['allLayouts'] = $layouts;

        return view('post::admin.posts.create',$data);
    }

    public function edit(Request $request, $id) {

        $data = [];
        $data['content_type'] = 'post';
        $data['content_id'] = intval($id);

        return view('post::admin.posts.edit', $data);
    }
}
