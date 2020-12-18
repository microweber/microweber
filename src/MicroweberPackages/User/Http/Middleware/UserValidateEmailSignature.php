<?php

namespace MicroweberPackages\User\Http\Middleware;

use Illuminate\Routing\Middleware\ValidateSignature;
use Closure;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

class UserValidateEmailSignature extends ValidateSignature  {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $relative
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Routing\Exceptions\InvalidSignatureException
     */
    public function handle($request, Closure $next, $relative = null)
    {
        if ($request->hasValidSignature($relative !== 'relative')) {
            return $next($request);
        }


        return redirect(route('verification.resend',[
            'id'=>$request->id,
            'hash'=>$request->hash,
        ]));

    }

}