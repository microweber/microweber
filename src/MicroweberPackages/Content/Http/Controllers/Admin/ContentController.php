<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */
namespace MicroweberPackages\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Admin\Http\Controllers\AdminController;
use MicroweberPackages\Content\Repositories\ContentRepository;

class ContentController extends AdminController
{
    public $repository;

    public function __construct(ContentRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    public function index(Request $request) {

        return view('content::admin.content.index');
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

        return view('content::admin.content.edit',$data);
    }

    public function edit(Request $request, $id) {

        return view('content::admin.content.edit', [
            'content_id'=>intval($id)
        ]);
    }
}
