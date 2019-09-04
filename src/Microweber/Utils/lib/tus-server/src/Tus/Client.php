<?php

namespace TusPhp\Tus;

use TusPhp\File;
use Carbon\Carbon;
use TusPhp\Config;
use TusPhp\Exception\TusException;
use TusPhp\Exception\FileException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use TusPhp\Exception\ConnectionException;
use GuzzleHttp\Exception\ConnectException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
class Client extends AbstractTus
{
    /** @var GuzzleClient */
    protected $client;
    /** @var string */
    protected $filePath;
    /** @var int */
    protected $fileSize = 0;
    /** @var string */
    protected $fileName;
    /** @var string */
    protected $key;
    /** @var string */
    protected $url;
    /** @var string */
    protected $checksum;
    /** @var int */
    protected $partialOffset = -1;
    /** @var bool */
    protected $partial = false;
    /** @var string */
    protected $checksumAlgorithm = 'sha256';
    /** @var array */
    protected $metadata = [];
    /**
     * Client constructor.
     *
     * @param string $baseUri
     * @param array  $options
     *
     * @throws \ReflectionException
     */
    public function __construct($baseUri, array $options = [])
    {
        $options['headers'] = ['Tus-Resumable' => self::TUS_PROTOCOL_VERSION] + (isset($options['headers']) ? $options['headers'] : []);
        $this->client = new GuzzleClient(['base_uri' => $baseUri] + $options);
        Config::set(__DIR__ . '/../Config/client.php');
        $this->setCache('file');
    }
    /**
     * Set file properties.
     *
     * @param string $file File path.
     * @param string $name File name.
     *
     * @return Client
     */
    public function file($file, $name = null)
    {
        $this->filePath = $file;
        if (!file_exists($file) || !is_readable($file)) {
            throw new FileException('Cannot read file: ' . $file);
        }
        $this->fileName = isset($name) ? $name : basename($this->filePath);
        $this->fileSize = filesize($file);
        $this->addMetadata('filename', $this->fileName);
        return $this;
    }
    /**
     * Get file path.
     *
     * @return string|null
     */
    public function getFilePath()
    {
        return $this->filePath;
    }
    /**
     * Set file name.
     *
     * @param string $name
     *
     * @return Client
     */
    public function setFileName($name)
    {
        $this->addMetadata('filename', $this->fileName = $name);
        return $this;
    }
    /**
     * Get file name.
     *
     * @return string|null
     */
    public function getFileName()
    {
        return $this->fileName;
    }
    /**
     * Get file size.
     *
     * @return int
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }
    /**
     * Get guzzle client.
     *
     * @return GuzzleClient
     */
    public function getClient()
    {
        return $this->client;
    }
    /**
     * Set checksum.
     *
     * @param string $checksum
     *
     * @return Client
     */
    public function setChecksum($checksum)
    {
        $this->checksum = $checksum;
        return $this;
    }
    /**
     * Get checksum.
     *
     * @return string
     */
    public function getChecksum()
    {
        if (empty($this->checksum)) {
            $this->setChecksum(hash_file($this->getChecksumAlgorithm(), $this->getFilePath()));
        }
        return $this->checksum;
    }
    /**
     * Add metadata.
     *
     * @param string $key
     * @param string $value
     *
     * @return Client
     */
    public function addMetadata($key, $value)
    {
        $this->metadata[$key] = base64_encode($value);
        return $this;
    }
    /**
     * Remove metadata.
     *
     * @param string $key
     *
     * @return Client
     */
    public function removeMetadata($key)
    {
        unset($this->metadata[$key]);
        return $this;
    }
    /**
     * Set metadata.
     *
     * @param array $items
     *
     * @return Client
     */
    public function setMetadata(array $items)
    {
        $items = array_map('base64_encode', $items);
        $this->metadata = $items;
        return $this;
    }
    /**
     * Get metadata.
     *
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
    /**
     * Get metadata for Upload-Metadata header.
     *
     * @return string
     */
    protected function getUploadMetadataHeader()
    {
        $metadata = [];
        foreach ($this->getMetadata() as $key => $value) {
            $metadata[] = "{$key} {$value}";
        }
        return implode(',', $metadata);
    }
    /**
     * Set key.
     *
     * @param string $key
     *
     * @return Client
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }
    /**
     * Get key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
    /**
     * Get url.
     *
     * @return string|null
     */
    public function getUrl()
    {
        $this->url = isset($this->getCache()->get($this->getKey())['location']) ? $this->getCache()->get($this->getKey())['location'] : null;
        if (!$this->url) {
            throw new FileException('File not found.');
        }
        return $this->url;
    }
    /**
     * Set checksum algorithm.
     *
     * @param string $algorithm
     *
     * @return Client
     */
    public function setChecksumAlgorithm($algorithm)
    {
        $this->checksumAlgorithm = $algorithm;
        return $this;
    }
    /**
     * Get checksum algorithm.
     *
     * @return string
     */
    public function getChecksumAlgorithm()
    {
        return $this->checksumAlgorithm;
    }
    /**
     * Check if current upload is expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        $expiresAt = isset($this->getCache()->get($this->getKey())['expires_at']) ? $this->getCache()->get($this->getKey())['expires_at'] : null;
        return empty($expiresAt) || Carbon::parse($expiresAt)->lt(Carbon::now());
    }
    /**
     * Check if this is a partial upload request.
     *
     * @return bool
     */
    public function isPartial()
    {
        return $this->partial;
    }
    /**
     * Get partial offset.
     *
     * @return int
     */
    public function getPartialOffset()
    {
        return $this->partialOffset;
    }
    /**
     * Set offset and force this to be a partial upload request.
     *
     * @param int $offset
     *
     * @return self
     */
    public function seek($offset)
    {
        $this->partialOffset = $offset;
        $this->partial();
        return $this;
    }
    /**
     * Upload file.
     *
     * @param int $bytes Bytes to upload
     *
     * @throws TusException
     * @throws ConnectionException
     *
     * @return int
     */
    public function upload($bytes = -1)
    {
        $bytes = $bytes < 0 ? $this->getFileSize() : $bytes;
        $offset = $this->partialOffset < 0 ? 0 : $this->partialOffset;
        try {
            // Check if this upload exists with HEAD request.
            $offset = $this->sendHeadRequest();
        } catch (FileException|ClientException $e) {
            // Create a new upload.
            $this->url = $this->create($this->getKey());
        } catch (ConnectException $e) {
            throw new ConnectionException("Couldn't connect to server.");
        }
        // Verify that upload is not yet expired.
        if ($this->isExpired()) {
            throw new TusException('Upload expired.');
        }
        // Now, resume upload with PATCH request.
        return $this->sendPatchRequest($bytes, $offset);
    }
    /**
     * Returns offset if file is partially uploaded.
     *
     * @return bool|int
     */
    public function getOffset()
    {
        try {
            $offset = $this->sendHeadRequest();
        } catch (FileException|ClientException $e) {
            return false;
        }
        return $offset;
    }
    /**
     * Create resource with POST request.
     *
     * @param string $key
     *
     * @throws FileException
     *
     * @return string
     */
    public function create($key)
    {
        $headers = ['Upload-Length' => $this->fileSize, 'Upload-Key' => $key, 'Upload-Checksum' => $this->getUploadChecksumHeader(), 'Upload-Metadata' => $this->getUploadMetadataHeader()];
        if ($this->isPartial()) {
            $headers += ['Upload-Concat' => 'partial'];
        }
        $response = $this->getClient()->post($this->apiPath, ['headers' => $headers]);
        $statusCode = $response->getStatusCode();
        if (HttpResponse::HTTP_CREATED !== $statusCode) {
            throw new FileException('Unable to create resource.');
        }
        $uploadLocation = current($response->getHeader('location'));
        $this->getCache()->set($this->getKey(), ['location' => $uploadLocation, 'expires_at' => Carbon::now()->addSeconds($this->getCache()->getTtl())->format($this->getCache()::RFC_7231)]);
        return $uploadLocation;
    }
    /**
     * Concatenate 2 or more partial uploads.
     *
     * @param string $key
     * @param mixed  $partials
     *
     * @return string
     */
    public function concat($key, ...$partials)
    {
        $response = $this->getClient()->post($this->apiPath, ['headers' => ['Upload-Length' => $this->fileSize, 'Upload-Key' => $key, 'Upload-Checksum' => $this->getUploadChecksumHeader(), 'Upload-Metadata' => $this->getUploadMetadataHeader(), 'Upload-Concat' => self::UPLOAD_TYPE_FINAL . ';' . implode(' ', $partials)]]);
        $data = json_decode($response->getBody(), true);
        $checksum = isset($data['data']['checksum']) ? $data['data']['checksum'] : null;
        $statusCode = $response->getStatusCode();
        if (HttpResponse::HTTP_CREATED !== $statusCode || !$checksum) {
            throw new FileException('Unable to create resource.');
        }
        return $checksum;
    }
    /**
     * Send DELETE request.
     *
     * @throws FileException
     *
     * @return void
     */
    public function delete()
    {
        try {
            $this->getClient()->delete($this->getUrl());
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            if (HttpResponse::HTTP_NOT_FOUND === $statusCode || HttpResponse::HTTP_GONE === $statusCode) {
                throw new FileException('File not found.');
            }
        }
    }
    /**
     * Set as partial request.
     *
     * @param bool $state
     *
     * @return void
     */
    protected function partial($state = true)
    {
        $this->partial = $state;
        if (!$this->partial) {
            return;
        }
        $key = $this->getKey();
        if (false !== strpos($key, self::PARTIAL_UPLOAD_NAME_SEPARATOR)) {
            list($key, ) = explode(self::PARTIAL_UPLOAD_NAME_SEPARATOR, $key);
        }
        $this->key = $key . uniqid(self::PARTIAL_UPLOAD_NAME_SEPARATOR);
    }
    /**
     * Send HEAD request.
     *
     * @throws FileException
     *
     * @return int
     */
    protected function sendHeadRequest()
    {
        $response = $this->getClient()->head($this->getUrl());
        $statusCode = $response->getStatusCode();
        if (HttpResponse::HTTP_OK !== $statusCode) {
            throw new FileException('File not found.');
        }
        return (int) current($response->getHeader('upload-offset'));
    }
    /**
     * Send PATCH request.
     *
     * @param int $bytes
     * @param int $offset
     *
     * @throws TusException
     * @throws FileException
     * @throws ConnectionException
     *
     * @return int
     */
    protected function sendPatchRequest($bytes, $offset)
    {
        $data = $this->getData($offset, $bytes);
        $headers = ['Content-Type' => self::HEADER_CONTENT_TYPE, 'Content-Length' => strlen($data), 'Upload-Checksum' => $this->getUploadChecksumHeader()];
        if ($this->isPartial()) {
            $headers += ['Upload-Concat' => self::UPLOAD_TYPE_PARTIAL];
        } else {
            $headers += ['Upload-Offset' => $offset];
        }
        try {
            $response = $this->getClient()->patch($this->getUrl(), ['body' => $data, 'headers' => $headers]);
            return (int) current($response->getHeader('upload-offset'));
        } catch (ClientException $e) {
            throw $this->handleClientException($e);
        } catch (ConnectException $e) {
            throw new ConnectionException("Couldn't connect to server.");
        }
    }
    /**
     * Handle client exception during patch request.
     *
     * @param ClientException $e
     *
     * @return mixed
     */
    protected function handleClientException(ClientException $e)
    {
        $statusCode = $e->getResponse()->getStatusCode();
        if (HttpResponse::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE === $statusCode) {
            return new FileException('The uploaded file is corrupt.');
        }
        if (HttpResponse::HTTP_CONTINUE === $statusCode) {
            return new ConnectionException('Connection aborted by user.');
        }
        if (HttpResponse::HTTP_UNSUPPORTED_MEDIA_TYPE === $statusCode) {
            return new TusException('Unsupported media types.');
        }
        return new TusException($e->getResponse()->getBody(), $statusCode);
    }
    /**
     * Get X bytes of data from file.
     *
     * @param int $offset
     * @param int $bytes
     *
     * @return string
     */
    protected function getData($offset, $bytes)
    {
        $file = new File();
        $handle = $file->open($this->getFilePath(), $file::READ_BINARY);
        $file->seek($handle, $offset);
        $data = $file->read($handle, $bytes);
        $file->close($handle);
        return (string) $data;
    }
    /**
     * Get upload checksum header.
     *
     * @return string
     */
    protected function getUploadChecksumHeader()
    {
        return $this->getChecksumAlgorithm() . ' ' . base64_encode($this->getChecksum());
    }
}