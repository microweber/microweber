<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */
namespace MicroweberPackages\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Content\Content;
use MicroweberPackages\Content\Repositories\ContentRepository;
use MicroweberPackages\Post\Http\Requests\PostRequest;
use MicroweberPackages\Post\Repositories\PostRepository;

class ContentController extends AdminController
{
    public $repository;

    public function __construct(ContentRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    public function create() {

        return $this->view('content::admin.content.edit', [
            'content_id'=>0
        ]);
    }

    public function edit(Request $request, $id) {

        return $this->view('content::admin.content.edit', [
            'content_id'=>intval($id)
        ]);
    }
}
