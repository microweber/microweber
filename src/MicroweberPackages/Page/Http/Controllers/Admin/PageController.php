<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */
namespace MicroweberPackages\Page\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\App\Http\Controllers\AdminController;
use MicroweberPackages\Page\Repositories\PageRepository;

class PageController extends AdminController
{
    public $repository;

    public function __construct(PageRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    public function edit(Request $request, $id) {

        return $this->view('post::admin.posts.edit', [
            'content_id'=>$id
        ]);
    }
}
