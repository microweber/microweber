<?php

namespace TusPhp;

use TusPhp\Tus\Server;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
class Request
{
    /** @var HttpRequest */
    protected $request;
    /**
     * Request constructor.
     */
    public function __construct()
    {
        if (null === $this->request) {
            $this->request = HttpRequest::createFromGlobals();
        }
    }
    /**
     * Get http method from current request.
     *
     * @return string
     */
    public function method()
    {
        return $this->request->getMethod();
    }
    /**
     * Get the current path info for the request.
     *
     * @return string
     */
    public function path()
    {
        return $this->request->getPathInfo();
    }
    /**
     * Get upload key from url.
     *
     * @return string
     */
    public function key()
    {
        return basename($this->path());
    }
    /**
     * Supported http requests.
     *
     * @return array
     */
    public function allowedHttpVerbs()
    {
        return [HttpRequest::METHOD_GET, HttpRequest::METHOD_POST, HttpRequest::METHOD_PATCH, HttpRequest::METHOD_DELETE, HttpRequest::METHOD_HEAD, HttpRequest::METHOD_OPTIONS];
    }
    /**
     * Retrieve a header from the request.
     *
     * @param string               $key
     * @param string|string[]|null $default
     *
     * @return string|null
     */
    public function header($key, $default = null)
    {
        return $this->request->headers->get($key, $default);
    }
    /**
     * Get the root URL for the request.
     *
     * @return string
     */
    public function url()
    {
        return rtrim($this->request->getUriForPath('/'), '/');
    }
    /**
     * Extract metadata from header.
     *
     * @param string $key
     * @param string $value
     *
     * @return array
     */
    public function extractFromHeader($key, $value)
    {
        $meta = $this->header($key);
        if (false !== strpos($meta, $value)) {
            $meta = trim(str_replace($value, '', $meta));
            return !empty(explode(' ', $meta)) ? explode(' ', $meta) : [];
        }
        return [];
    }
    /**
     * Extract base64 encoded filename from header.
     *
     * @return string
     */
    public function extractFileName()
    {
        $name = $this->extractMeta('name') ?: $this->extractMeta('filename');
        if (!$this->isValidFilename($name)) {
            return '';
        }
        return $name;
    }
    /**
     * Extracts the meta data from the request header.
     *
     * @param string $requestedKey
     *
     * @return string
     */
    public function extractMeta($requestedKey)
    {
        $uploadMetaData = $this->request->headers->get('Upload-Metadata');
        if (empty($uploadMetaData)) {
            return '';
        }
        $uploadMetaDataChunks = explode(',', $uploadMetaData);
        foreach ($uploadMetaDataChunks as $chunk) {
            list($key, $value) = explode(' ', $chunk);
            if ($key === $requestedKey) {
                return base64_decode($value);
            }
        }
        return '';
    }
    /**
     * Extract partials from header.
     *
     * @return array
     */
    public function extractPartials()
    {
        return $this->extractFromHeader('Upload-Concat', Server::UPLOAD_TYPE_FINAL . ';');
    }
    /**
     * Check if this is a partial upload request.
     *
     * @return bool
     */
    public function isPartial()
    {
        return Server::UPLOAD_TYPE_PARTIAL === $this->header('Upload-Concat');
    }
    /**
     * Check if this is a final concatenation request.
     *
     * @return bool
     */
    public function isFinal()
    {
        return false !== strpos($this->header('Upload-Concat'), Server::UPLOAD_TYPE_FINAL . ';');
    }
    /**
     * Get request.
     *
     * @return HttpRequest
     */
    public function getRequest()
    {
        return $this->request;
    }
    /**
     * Validate file name.
     *
     * @param string $filename
     *
     * @return bool
     */
    protected function isValidFilename($filename)
    {
        $forbidden = ['../', '"', "'", '&', '/', '\\', '?', '#', ':'];
        foreach ($forbidden as $char) {
            if (false !== strpos($filename, $char)) {
                return false;
            }
        }
        return true;
    }
}