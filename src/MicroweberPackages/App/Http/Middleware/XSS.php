<?php

namespace MicroweberPackages\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use GrahamCampbell\SecurityCore\Security;
use MicroweberPackages\Helper\HTMLClean;

class XSS
{
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();

        if (($request->isMethod('post')  or $request->isMethod('patch') or $request->isMethod('put') ) and !empty($input)) {
            $clean = new HTMLClean();
            array_walk_recursive($input, function (&$input) use ($clean) {
                if (is_string($input)) {
                    $input = $clean->clean($input);
                    // clean also blade curly braces
                    $input = str_replace(['{{', '}}'], ['&#123;&#123;', '&#125;&#125;'], $input);
                }
            });
        }

        $request->merge($input);
        return $next($request);
    }

}
