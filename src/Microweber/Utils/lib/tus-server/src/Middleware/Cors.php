<?php

namespace TusPhp\Middleware;

use TusPhp\Request;
use TusPhp\Response;
class Cors implements TusMiddleware
{
    /** @const int 24 hours access control max age header */
    const HEADER_ACCESS_CONTROL_MAX_AGE = 86400;
    /**
     * {@inheritDoc}
     */
    public function handle(Request $request, Response $response)
    {
        $response->setHeaders(['Access-Control-Allow-Origin' => $request->header('Origin'), 'Access-Control-Allow-Methods' => implode(',', $request->allowedHttpVerbs()), 'Access-Control-Allow-Headers' => 'Origin, X-Requested-With, Content-Type, Content-Length, Upload-Key, Upload-Checksum, Upload-Length, Upload-Offset, Tus-Version, Tus-Resumable, Upload-Metadata', 'Access-Control-Expose-Headers' => 'Upload-Key, Upload-Checksum, Upload-Length, Upload-Offset, Upload-Metadata, Tus-Version, Tus-Resumable, Tus-Extension, Location', 'Access-Control-Max-Age' => self::HEADER_ACCESS_CONTROL_MAX_AGE]);
    }
}