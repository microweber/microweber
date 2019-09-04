<?php

namespace TusPhp\Tus;

use TusPhp\File;
use Carbon\Carbon;
use TusPhp\Request;
use TusPhp\Response;
use Ramsey\Uuid\Uuid;
use TusPhp\Cache\Cacheable;
use TusPhp\Events\UploadMerged;
use TusPhp\Events\UploadCreated;
use TusPhp\Events\UploadComplete;
use TusPhp\Events\UploadProgress;
use TusPhp\Middleware\Middleware;
use TusPhp\Exception\FileException;
use TusPhp\Exception\ConnectionException;
use TusPhp\Exception\OutOfRangeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
class Server extends AbstractTus
{
    /** @const string Tus Creation Extension */
    const TUS_EXTENSION_CREATION = 'creation';
    /** @const string Tus Termination Extension */
    const TUS_EXTENSION_TERMINATION = 'termination';
    /** @const string Tus Checksum Extension */
    const TUS_EXTENSION_CHECKSUM = 'checksum';
    /** @const string Tus Expiration Extension */
    const TUS_EXTENSION_EXPIRATION = 'expiration';
    /** @const string Tus Concatenation Extension */
    const TUS_EXTENSION_CONCATENATION = 'concatenation';
    /** @const array All supported tus extensions */
    const TUS_EXTENSIONS = [self::TUS_EXTENSION_CREATION, self::TUS_EXTENSION_TERMINATION, self::TUS_EXTENSION_CHECKSUM, self::TUS_EXTENSION_EXPIRATION, self::TUS_EXTENSION_CONCATENATION];
    /** @const int 460 Checksum Mismatch */
    const HTTP_CHECKSUM_MISMATCH = 460;
    /** @const string Default checksum algorithm */
    const DEFAULT_CHECKSUM_ALGORITHM = 'sha256';
    /** @var Request */
    protected $request;
    /** @var Response */
    protected $response;
    /** @var string */
    protected $uploadDir;
    /** @var string */
    protected $uploadKey;
    /** @var Middleware */
    protected $middleware;
    /**
     * @var int Max upload size in bytes
     *          Default 0, no restriction.
     */
    protected $maxUploadSize = 0;
    /**
     * TusServer constructor.
     *
     * @param Cacheable|string $cacheAdapter
     *
     * @throws \ReflectionException
     */
    public function __construct($cacheAdapter = 'file')
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->middleware = new Middleware();
        $this->uploadDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'uploads';
        $this->setCache($cacheAdapter);
    }
    /**
     * Set upload dir.
     *
     * @param string $path
     *
     * @return Server
     */
    public function setUploadDir($path)
    {
        $this->uploadDir = $path;
        return $this;
    }
    /**
     * Get upload dir.
     *
     * @return string
     */
    public function getUploadDir()
    {
        return $this->uploadDir;
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
     * Get request.
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
    /**
     * Get file checksum.
     *
     * @param string $filePath
     *
     * @return string
     */
    public function getServerChecksum($filePath)
    {
        return hash_file($this->getChecksumAlgorithm(), $filePath);
    }
    /**
     * Get checksum algorithm.
     *
     * @return string|null
     */
    public function getChecksumAlgorithm()
    {
        $checksumHeader = $this->getRequest()->header('Upload-Checksum');
        if (empty($checksumHeader)) {
            return self::DEFAULT_CHECKSUM_ALGORITHM;
        }
        list($checksumAlgorithm, ) = explode(' ', $checksumHeader);
        return $checksumAlgorithm;
    }
    /**
     * Set upload key.
     *
     * @param string $key
     *
     * @return Server
     */
    public function setUploadKey($key)
    {
        $this->uploadKey = $key;
        return $this;
    }
    /**
     * Get upload key from header.
     *
     * @return string|HttpResponse
     */
    public function getUploadKey()
    {
        if (!empty($this->uploadKey)) {
            return $this->uploadKey;
        }
        $key = !empty($this->getRequest()->header('Upload-Key')) ? $this->getRequest()->header('Upload-Key') : Uuid::uuid4()->toString();
        if (empty($key)) {
            return $this->response->send(null, HttpResponse::HTTP_BAD_REQUEST);
        }
        $this->uploadKey = $key;
        return $this->uploadKey;
    }
    /**
     * Set middleware.
     *
     * @param Middleware $middleware
     *
     * @return self
     */
    public function setMiddleware(Middleware $middleware)
    {
        $this->middleware = $middleware;
        return $this;
    }
    /**
     * Get middleware.
     *
     * @return Middleware
     */
    public function middleware()
    {
        return $this->middleware;
    }
    /**
     * Set max upload size.
     *
     * @param int $uploadSize
     *
     * @return Server
     */
    public function setMaxUploadSize($uploadSize)
    {
        $this->maxUploadSize = $uploadSize;
        return $this;
    }
    /**
     * Get max upload size.
     *
     * @return int
     */
    public function getMaxUploadSize()
    {
        return $this->maxUploadSize;
    }
    /**
     * Handle all HTTP request.
     *
     * @return HttpResponse|BinaryFileResponse
     */
    public function serve()
    {
        $this->applyMiddleware();
        $requestMethod = $this->getRequest()->method();
        if (!in_array($requestMethod, $this->getRequest()->allowedHttpVerbs())) {
            return $this->response->send(null, HttpResponse::HTTP_METHOD_NOT_ALLOWED);
        }
        $clientVersion = $this->getRequest()->header('Tus-Resumable');
        if ($clientVersion && $clientVersion !== self::TUS_PROTOCOL_VERSION) {
            return $this->response->send(null, HttpResponse::HTTP_PRECONDITION_FAILED, ['Tus-Version' => self::TUS_PROTOCOL_VERSION]);
        }
        $method = 'handle' . ucfirst(strtolower($requestMethod));
        return $this->{$method}();
    }
    /**
     * Apply middleware.
     *
     * @return void
     */
    protected function applyMiddleware()
    {
        $middleware = $this->middleware()->list();
        foreach ($middleware as $m) {
            $m->handle($this->getRequest(), $this->getResponse());
        }
    }
    /**
     * Handle OPTIONS request.
     *
     * @return HttpResponse
     */
    protected function handleOptions()
    {
        $headers = ['Allow' => implode(',', $this->request->allowedHttpVerbs()), 'Tus-Version' => self::TUS_PROTOCOL_VERSION, 'Tus-Extension' => implode(',', self::TUS_EXTENSIONS), 'Tus-Checksum-Algorithm' => $this->getSupportedHashAlgorithms()];
        $maxUploadSize = $this->getMaxUploadSize();
        if ($maxUploadSize > 0) {
            $headers['Tus-Max-Size'] = $maxUploadSize;
        }
        return $this->response->send(null, HttpResponse::HTTP_OK, $headers);
    }
    /**
     * Handle HEAD request.
     *
     * @return HttpResponse
     */
    protected function handleHead()
    {
        $key = $this->request->key();
        if (!($fileMeta = $this->cache->get($key))) {
            return $this->response->send(null, HttpResponse::HTTP_NOT_FOUND);
        }
        $offset = isset($fileMeta['offset']) ? $fileMeta['offset'] : false;
        if (false === $offset) {
            return $this->response->send(null, HttpResponse::HTTP_GONE);
        }
        return $this->response->send(null, HttpResponse::HTTP_OK, $this->getHeadersForHeadRequest($fileMeta));
    }
    /**
     * Handle POST request.
     *
     * @return HttpResponse
     */
    protected function handlePost()
    {
        $fileName = $this->getRequest()->extractFileName();
        $uploadType = self::UPLOAD_TYPE_NORMAL;
        if (empty($fileName)) {
            return $this->response->send(null, HttpResponse::HTTP_BAD_REQUEST);
        }
        if (!$this->verifyUploadSize()) {
            return $this->response->send(null, HttpResponse::HTTP_REQUEST_ENTITY_TOO_LARGE);
        }
        $uploadKey = $this->getUploadKey();
        $filePath = $this->uploadDir . DIRECTORY_SEPARATOR . $fileName;
        if ($this->getRequest()->isFinal()) {
            return $this->handleConcatenation($fileName, $filePath);
        }
        if ($this->getRequest()->isPartial()) {
            $filePath = $this->getPathForPartialUpload($uploadKey) . $fileName;
            $uploadType = self::UPLOAD_TYPE_PARTIAL;
        }
        $checksum = $this->getClientChecksum();
        $location = $this->getRequest()->url() . $this->getApiPath() . '/' . $uploadKey;
        $file = $this->buildFile(['name' => $fileName, 'offset' => 0, 'size' => $this->getRequest()->header('Upload-Length'), 'file_path' => $filePath, 'location' => $location])->setKey($uploadKey)->setChecksum($checksum);
        $this->cache->set($uploadKey, $file->details() + ['upload_type' => $uploadType]);
        $this->event()->dispatch(UploadCreated::NAME, new UploadCreated($file, $this->getRequest(), $this->getResponse()));
        return $this->response->send(null, HttpResponse::HTTP_CREATED, ['Location' => $location, 'Upload-Expires' => $this->cache->get($uploadKey)['expires_at']]);
    }
    /**
     * Handle file concatenation.
     *
     * @param string $fileName
     * @param string $filePath
     *
     * @return HttpResponse
     */
    protected function handleConcatenation($fileName, $filePath)
    {
        $partials = $this->getRequest()->extractPartials();
        $uploadKey = $this->getUploadKey();
        $files = $this->getPartialsMeta($partials);
        $filePaths = array_column($files, 'file_path');
        $location = $this->getRequest()->url() . $this->getApiPath() . '/' . $uploadKey;
        $file = $this->buildFile(['name' => $fileName, 'offset' => 0, 'size' => 0, 'file_path' => $filePath, 'location' => $location])->setFilePath($filePath)->setKey($uploadKey);
        $file->setOffset($file->merge($files));
        // Verify checksum.
        $checksum = $this->getServerChecksum($filePath);
        if ($checksum !== $this->getClientChecksum()) {
            return $this->response->send(null, self::HTTP_CHECKSUM_MISMATCH);
        }
        $file->setChecksum($checksum);
        $this->cache->set($uploadKey, $file->details() + ['upload_type' => self::UPLOAD_TYPE_FINAL]);
        // Cleanup.
        if ($file->delete($filePaths, true)) {
            $this->cache->deleteAll($partials);
        }
        $this->event()->dispatch(UploadMerged::NAME, new UploadMerged($file, $this->getRequest(), $this->getResponse()));
        return $this->response->send(['data' => ['checksum' => $checksum]], HttpResponse::HTTP_CREATED, ['Location' => $location]);
    }
    /**
     * Handle PATCH request.
     *
     * @return HttpResponse
     */
    protected function handlePatch()
    {
        $uploadKey = $this->request->key();
        if (!($meta = $this->cache->get($uploadKey))) {
            return $this->response->send(null, HttpResponse::HTTP_GONE);
        }
        $status = $this->verifyPatchRequest($meta);
        if (HttpResponse::HTTP_OK !== $status) {
            return $this->response->send(null, $status);
        }
        $file = $this->buildFile($meta);
        $checksum = $meta['checksum'];
        try {
            $fileSize = $file->getFileSize();
            $offset = $file->setKey($uploadKey)->setChecksum($checksum)->upload($fileSize);
            // If upload is done, verify checksum.
            if ($offset === $fileSize) {
                if (!$this->verifyChecksum($checksum, $meta['file_path'])) {
                    return $this->response->send(null, self::HTTP_CHECKSUM_MISMATCH);
                }
                $this->event()->dispatch(UploadComplete::NAME, new UploadComplete($file, $this->getRequest(), $this->getResponse()));
            } else {
                $this->event()->dispatch(UploadProgress::NAME, new UploadProgress($file, $this->getRequest(), $this->getResponse()));
            }
        } catch (FileException $e) {
            return $this->response->send($e->getMessage(), HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        } catch (OutOfRangeException $e) {
            return $this->response->send(null, HttpResponse::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE);
        } catch (ConnectionException $e) {
            return $this->response->send(null, HttpResponse::HTTP_CONTINUE);
        }
        return $this->response->send(null, HttpResponse::HTTP_NO_CONTENT, ['Content-Type' => self::HEADER_CONTENT_TYPE, 'Upload-Expires' => $this->cache->get($uploadKey)['expires_at'], 'Upload-Offset' => $offset]);
    }
    /**
     * Verify PATCH request.
     *
     * @param array $meta
     *
     * @return int
     */
    protected function verifyPatchRequest(array $meta)
    {
        if (self::UPLOAD_TYPE_FINAL === $meta['upload_type']) {
            return HttpResponse::HTTP_FORBIDDEN;
        }
        $uploadOffset = $this->request->header('upload-offset');
        if ($uploadOffset && $uploadOffset !== (string) $meta['offset']) {
            return HttpResponse::HTTP_CONFLICT;
        }
        $contentType = $this->request->header('Content-Type');
        if ($contentType !== self::HEADER_CONTENT_TYPE) {
            return HTTPRESPONSE::HTTP_UNSUPPORTED_MEDIA_TYPE;
        }
        return HttpResponse::HTTP_OK;
    }
    /**
     * Handle GET request.
     *
     * As per RFC7231, we need to treat HEAD and GET as an identical request.
     * All major PHP frameworks follows the same and silently transforms each
     * HEAD requests to GET.
     *
     * @return BinaryFileResponse|HttpResponse
     */
    protected function handleGet()
    {
        // We will treat '/files/<key>/get' as a download request.
        if ('get' === $this->request->key()) {
            return $this->handleDownload();
        }
        return $this->handleHead();
    }
    /**
     * Handle Download request.
     *
     * @return BinaryFileResponse|HttpResponse
     */
    protected function handleDownload()
    {
        $path = explode('/', str_replace('/get', '', $this->request->path()));
        $key = end($path);
        if (!($fileMeta = $this->cache->get($key))) {
            return $this->response->send('404 upload not found.', HttpResponse::HTTP_NOT_FOUND);
        }
        $resource = isset($fileMeta['file_path']) ? $fileMeta['file_path'] : null;
        $fileName = isset($fileMeta['name']) ? $fileMeta['name'] : null;
        if (!$resource || !file_exists($resource)) {
            return $this->response->send('404 upload not found.', HttpResponse::HTTP_NOT_FOUND);
        }
        return $this->response->download($resource, $fileName);
    }
    /**
     * Handle DELETE request.
     *
     * @return HttpResponse
     */
    protected function handleDelete()
    {
        $key = $this->request->key();
        $fileMeta = $this->cache->get($key);
        $resource = isset($fileMeta['file_path']) ? $fileMeta['file_path'] : null;
        if (!$resource) {
            return $this->response->send(null, HttpResponse::HTTP_NOT_FOUND);
        }
        $isDeleted = $this->cache->delete($key);
        if (!$isDeleted || !file_exists($resource)) {
            return $this->response->send(null, HttpResponse::HTTP_GONE);
        }
        unlink($resource);
        return $this->response->send(null, HttpResponse::HTTP_NO_CONTENT, ['Tus-Extension' => self::TUS_EXTENSION_TERMINATION]);
    }
    /**
     * Get required headers for head request.
     *
     * @param array $fileMeta
     *
     * @return array
     */
    protected function getHeadersForHeadRequest(array $fileMeta)
    {
        $headers = ['Upload-Length' => (int) $fileMeta['size'], 'Upload-Offset' => (int) $fileMeta['offset'], 'Cache-Control' => 'no-store'];
        if (self::UPLOAD_TYPE_FINAL === $fileMeta['upload_type'] && $fileMeta['size'] !== $fileMeta['offset']) {
            unset($headers['Upload-Offset']);
        }
        if (self::UPLOAD_TYPE_NORMAL !== $fileMeta['upload_type']) {
            $headers += ['Upload-Concat' => $fileMeta['upload_type']];
        }
        return $headers;
    }
    /**
     * Build file object.
     *
     * @param array $meta
     *
     * @return File
     */
    protected function buildFile(array $meta)
    {
        $file = new File($meta['name'], $this->cache);
        if (array_key_exists('offset', $meta)) {
            $file->setMeta($meta['offset'], $meta['size'], $meta['file_path'], $meta['location']);
        }
        return $file;
    }
    /**
     * Get list of supported hash algorithms.
     *
     * @return string
     */
    protected function getSupportedHashAlgorithms()
    {
        $supportedAlgorithms = hash_algos();
        $algorithms = [];
        foreach ($supportedAlgorithms as $hashAlgo) {
            if (false !== strpos($hashAlgo, ',')) {
                $algorithms[] = "'{$hashAlgo}'";
            } else {
                $algorithms[] = $hashAlgo;
            }
        }
        return implode(',', $algorithms);
    }
    /**
     * Verify and get upload checksum from header.
     *
     * @return string|HttpResponse
     */
    protected function getClientChecksum()
    {
        $checksumHeader = $this->getRequest()->header('Upload-Checksum');
        if (empty($checksumHeader)) {
            return '';
        }
        list($checksumAlgorithm, $checksum) = explode(' ', $checksumHeader);
        $checksum = base64_decode($checksum);
        if (!in_array($checksumAlgorithm, hash_algos()) || false === $checksum) {
            return $this->response->send(null, HttpResponse::HTTP_BAD_REQUEST);
        }
        return $checksum;
    }
    /**
     * Get expired but incomplete uploads.
     *
     * @param array|null $contents
     *
     * @return bool
     */
    protected function isExpired($contents)
    {
        $isExpired = empty($contents['expires_at']) || Carbon::parse($contents['expires_at'])->lt(Carbon::now());
        if ($isExpired && $contents['offset'] !== $contents['size']) {
            return true;
        }
        return false;
    }
    /**
     * Get path for partial upload.
     *
     * @param string $key
     *
     * @return string
     */
    protected function getPathForPartialUpload($key)
    {
        list($actualKey, ) = explode(self::PARTIAL_UPLOAD_NAME_SEPARATOR, $key);
        $path = $this->uploadDir . DIRECTORY_SEPARATOR . $actualKey . DIRECTORY_SEPARATOR;
        if (!file_exists($path)) {
            mkdir($path);
        }
        return $path;
    }
    /**
     * Get metadata of partials.
     *
     * @param array $partials
     *
     * @return array
     */
    protected function getPartialsMeta(array $partials)
    {
        $files = [];
        foreach ($partials as $partial) {
            $fileMeta = $this->getCache()->get($partial);
            $files[] = $fileMeta;
        }
        return $files;
    }
    /**
     * Delete expired resources.
     *
     * @return array
     */
    public function handleExpiration()
    {
        $deleted = [];
        $cacheKeys = $this->cache->keys();
        foreach ($cacheKeys as $key) {
            $fileMeta = $this->cache->get($key, true);
            if (!$this->isExpired($fileMeta)) {
                continue;
            }
            if (!$this->cache->delete($key)) {
                continue;
            }
            if (is_writable($fileMeta['file_path'])) {
                unlink($fileMeta['file_path']);
            }
            $deleted[] = $fileMeta;
        }
        return $deleted;
    }
    /**
     * Verify max upload size.
     *
     * @return bool
     */
    protected function verifyUploadSize()
    {
        $maxUploadSize = $this->getMaxUploadSize();
        if ($maxUploadSize > 0 && $this->getRequest()->header('Upload-Length') > $maxUploadSize) {
            return false;
        }
        return true;
    }
    /**
     * Verify checksum if available.
     *
     * @param string $checksum
     * @param string $filePath
     *
     * @return bool
     */
    protected function verifyChecksum($checksum, $filePath)
    {
        // Skip if checksum is empty.
        if (empty($checksum)) {
            return true;
        }
        return $checksum === $this->getServerChecksum($filePath);
    }
    /**
     * No other methods are allowed.
     *
     * @param string $method
     * @param array  $params
     *
     * @return HttpResponse
     */
    public function __call($method, array $params)
    {
        return $this->response->send(null, HttpResponse::HTTP_BAD_REQUEST);
    }
}