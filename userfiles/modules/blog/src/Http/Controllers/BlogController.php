<?php

namespace MicroweberPackages\Blog\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\Content\Content;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Product\Models\Product;

class BlogController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $moduleId = $request->get('id');

        $postQuery = Product::query();

        $postResults = $postQuery->frontendFilter([
            'moduleId'=>$moduleId
        ]);

        return view('blog::index', ['posts'=>$postResults]);
    }

}
