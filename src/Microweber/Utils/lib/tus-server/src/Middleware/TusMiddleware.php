<?php

namespace TusPhp\Middleware;

use TusPhp\Request;
use TusPhp\Response;
interface TusMiddleware
{
    /**
     * Handle request/response.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return mixed
     */
    public function handle(Request $request, Response $response);
}