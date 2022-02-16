<?php

namespace MicroweberPackages\User\Http\Controllers;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MicroweberPackages\App\Http\Middleware\SameSiteRefererMiddleware;

class UserLogoutController extends Controller
{
    public $middleware = [
        [
            'middleware' => 'xss',
            'options' => []
        ]
    ];

    public function __construct()
    {
        event_trigger('mw.init');
    }

    /**
     * Display a listing of Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ref = $request->headers->get('referer');

        $same_site = app()->make(SameSiteRefererMiddleware::class);
        $is_same_site = $same_site->isSameSite($ref);

        if ($is_same_site) {
            return logout();
        }

        return view('user::logout.index');
    }

    public function submit(Request $request)
    {
        return logout();
    }

}
