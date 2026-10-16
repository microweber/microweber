<?php

namespace Modules\DigitalProduct\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\DigitalProduct\Services\DigitalProductService;

class DownloadController extends Controller
{
    protected DigitalProductService $service;

    public function __construct(DigitalProductService $service)
    {
        $this->service = $service;
    }

    /**
     * Deliver a purchased file by its grant token. 404 when the token is unknown,
     * the file is missing, or the grant is expired / over its download limit —
     * we deliberately do not distinguish those to avoid leaking grant state.
     */
    public function show(string $token)
    {
        $response = $this->service->streamDownload($token);

        if ($response === null) {
            abort(404);
        }

        return $response;
    }
}
