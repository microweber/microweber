<?php

namespace MicroweberPackages\Shop\Products\Filter\Http\Controllers;

use Illuminate\Http\Request;

class ProductFilterController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        
        return view('productFilter::index', []);
    }

}
