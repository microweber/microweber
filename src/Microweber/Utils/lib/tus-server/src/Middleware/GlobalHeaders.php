<?php

namespace TusPhp\Middleware;

use TusPhp\Request;
use TusPhp\Response;
use TusPhp\Tus\Server;
class GlobalHeaders implements TusMiddleware
{
    /**
     * {@inheritDoc}
     */
    public function handle(Request $request, Response $response)
    {
        $headers = ['X-Content-Type-Options' => 'nosniff', 'Tus-Resumable' => Server::TUS_PROTOCOL_VERSION];
        $response->setHeaders($headers);
    }
}