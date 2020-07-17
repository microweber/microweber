<?php

namespace MicroweberPackages\Role\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    use ValidatesRequests;

    protected $guards;

    public function __construct()
    {
        $guards = array_keys(config('auth.guards', []));
        $this->guards = array_combine($guards, $guards);
    }

    public function index(Request $request)
    {

        return 'ludnica bojkata';
    }

}
