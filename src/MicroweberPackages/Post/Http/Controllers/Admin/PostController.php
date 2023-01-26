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
        return $this->view('post::admin.posts.index');
    }

    public function create(Request $request) {

        $request_data = $request->all();

        $data = [];
        $data['content_id'] = 0;
        $data['recommended_category_id'] = 0;
        $data['recommended_content_id'] = 0;
        if (isset($request_data['recommended_category_id'])) {
            $data['recommended_category_id'] = intval($request_data['recommended_category_id']);
        }
        if (isset($request_data['recommended_content_id'])) {
            $data['recommended_content_id'] = intval($request_data['recommended_content_id']);
        }



        return $this->view('post::admin.posts.edit',$data);
    }

    public function edit(Request $request, $id) {

        return $this->view('post::admin.posts.edit', [
            'content_id'=>intval($id)
        ]);
    }
}
