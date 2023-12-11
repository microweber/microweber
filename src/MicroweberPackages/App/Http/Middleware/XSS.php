<?php

namespace MicroweberPackages\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use GrahamCampbell\SecurityCore\Security;
use Illuminate\Support\Facades\Log;
use MicroweberPackages\Helper\HTMLClean;

class XSS
{
    public function handle(Request $request, Closure $next)
    {
        //return $next($request);
        $input = $request->all();

        if (($request->isMethod('post')  or $request->isMethod('patch') or $request->isMethod('put') ) and !empty($input)) {
            $clean = new HTMLClean();
            $options = [];
            if(is_admin()){
                //allows more tags and images
                $options['admin_mode'] = true;
            }
            array_walk_recursive($input, function (&$input) use ($clean,$options) {
                if (is_string($input)) {
                    $input = $clean->clean($input,$options);
                }
            });
        }


        $request->merge($input);
        return $next($request);
    }

}
