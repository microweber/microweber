<?php

namespace TusPhp\Events;

use TusPhp\File;
use TusPhp\Request;
use TusPhp\Response;
use Symfony\Component\EventDispatcher\Event;
class TusEvent extends Event
{
    /** @var File */
    protected $file;
    /** @var Request */
    protected $request;
    /** @var Response */
    protected $response;
    /**
     * Get file.
     *
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }
    /**
     * Get request.
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
    /**
     * Get response.
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}