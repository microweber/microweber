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
     * @param  array|null  $args
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Routing\Exceptions\InvalidSignatureException
     */
    public function handle($request, Closure $next, ...$args)
    {
        [$relative, $ignore] = $this->parseArguments($args);

        if ($request->hasValidSignatureWhileIgnoring($ignore, ! $relative)) {
            return $next($request);
        }

        return redirect(route('verification.resend',[
            'id'=>$request->id,
            'hash'=>$request->hash,
        ]));


        throw new InvalidSignatureException;
    }




}
