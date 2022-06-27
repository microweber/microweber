<?php

namespace MicroweberPackages\App\Http\Middleware;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Cookie\CookieValuePrefix;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Cookie;

class VerifyCsrfToken extends Middleware
{
    /**
     * Add the CSRF token to the response cookies.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function addCookieToResponse($request, $response)
    {
        if(!is_object($response)){
            return;
        }

        return parent::addCookieToResponse($request, $response);
    }
    /**
     * Get the CSRF token from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function getTokenFromRequest($request)
    {
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');

        if (! $token && $header = $request->header('X-XSRF-TOKEN')) {
            try {
                $token = CookieValuePrefix::remove($this->encrypter->decrypt($header, static::serialized()));
            } catch (DecryptException $e) {
                $token = '';
            }
        } else {
            $token = CookieValuePrefix::remove($this->encrypter->decrypt( $request->header('X-CSRF-TOKEN'), static::serialized()));
        }
        return $token;
    }
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];
}
