<?php

namespace MicroweberPackages\App\Http\Middleware;

use Illuminate\Support\Str;

use Closure;
use \Illuminate\Session\Middleware\StartSession;
use Illuminate\Http\Request;

class StartSessionExtended extends StartSession // Extend the base StartSession middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return parent::handle($request, $next); // defer to the right stuff
    }

    /**
     * Store the current URL for the request if necessary.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Session\SessionInterface $session
     * @return void
     */
    protected function storeCurrentUrl(Request $request, $session)
    {


        $full_url = $request->fullUrl();

        $result = Str::startsWith($full_url, api_url());
        if(!$result){
            $result = Str::contains('_debugbar',$full_url);
        }

        if ($result and !is_ajax() ) {
            $session->setPreviousUrl($session->previousUrl());
        } else if (!$result and !is_ajax() ) {
            $session->setPreviousUrl($request->fullUrl());
        }else if (is_ajax() ) {
            $referer = $request->headers->get('referer');
            $session->setPreviousUrl($referer);
        }
    }
}

