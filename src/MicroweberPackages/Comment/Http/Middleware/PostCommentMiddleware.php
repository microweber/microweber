<?php

namespace MicroweberPackages\Comment\Http\Middleware;

use Illuminate\Http\Request;
use MicroweberPackages\Helper\HTMLClean;

use Closure;
use GrahamCampbell\SecurityCore\Security;

class PostCommentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();

        $clean = new HTMLClean();

        array_walk_recursive($input, function (&$input) use ($clean) {
            if (is_string($input)) {
                $input = $clean->onlyTags($input);
            }
        });

        $request->merge($input);
        return $next($request);
    }
}
