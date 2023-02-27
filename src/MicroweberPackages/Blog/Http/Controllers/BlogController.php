<?php

namespace MicroweberPackages\Blog\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\App\Http\Controllers\ModuleFrontController;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Post\Models\Post;

class BlogController extends ModuleFrontController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $moduleId = $request->get('id');

        $postQuery = Post::query();

        $postResults = $postQuery->frontendFilter([
            'request'=>$request,
            'moduleId'=>$moduleId
        ]);

        return $this->view( 'blog::index', [
            'posts'=>$postResults,
            'moduleId'=>$moduleId
        ]);
    }

}
