<?php

namespace MicroweberPackages\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Admin\Events\ServingAdmin;
use MicroweberPackages\User\Models\User;
use function mw_is_installed;
use function optional;
use function redirect;
use function route;

class Admin
{
    /**
     * The routes that should be excluded from verification.
     *
     * @var array
     */
    protected $except = [
        'admin.login.*',
        'admin.reset.*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next)
    {
        if (!mw_is_installed()) {
            return redirect(site_url());
        }

        ServingAdmin::dispatch();

        event_trigger('mw.admin');
        event_trigger('mw_backend');


        if ($this->requestIsInIframe($request)) {
            view()->share('isIframe', true);
        } else {
            view()->share('isIframe', false);
        }

        if ($this->inExceptArray($request) || (Auth::check() && intval(Auth::user()->is_admin) === 1)) {
             return $next($request);
        }

        $hasNoAdmin = User::where('is_admin', 1)->limit(1)->count();
        if (!$hasNoAdmin) {
            return $next($request);
        }

        return redirect()->guest(route('admin.login'));
    }
    private function requestIsInIframe($request)
    {

        $secFetchDest = $request->header('Sec-Fetch-Dest');

        if ($secFetchDest == 'iframe') {
            return true;
        }
    }

    /**
     * Determine if the request URI is in except array.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            $routeName = optional($request->route())->getName();

            if (preg_match("/{$except}/", $routeName)) {
                return true;
            }
        }

        return false;
    }
}
