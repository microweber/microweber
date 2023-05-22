<?php
namespace MicroweberPackages\Modules\Comments\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\App\Http\Controllers\ModuleFrontController;

class CommentsController extends ModuleFrontController {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $moduleId = $request->get('id');

        return $this->view('comments::index');
    }
}
