<?php

namespace TusPhp\Cache;

use TusPhp\File;
use Carbon\Carbon;
use TusPhp\Config;
class FileStore extends AbstractCache
{
    /** @var string */
    protected $cacheDir;
    /** @var string */
    protected $cacheFile;
    /**
     * FileStore constructor.
     *
     * @param string|null $cacheDir
     * @param string|null $cacheFile
     */
    public function __construct($cacheDir = null, $cacheFile = null)
    {
        $cacheDir = isset($cacheDir) ? $cacheDir : Config::get('file.dir');
        $cacheFile = isset($cacheFile) ? $cacheFile : Config::get('file.name');
        $this->setCacheDir($cacheDir);
        $this->setCacheFile($cacheFile);
    }
    /**
     * Set cache dir.
     *
     * @param string $path
     *
     * @return self
     */
    public function setCacheDir($path)
    {
        $this->cacheDir = $path;
        return $this;
    }
    /**
     * Get cache dir.
     *
     * @return string
     */
    public function getCacheDir()
    {
        return $this->cacheDir;
    }
    /**
     * Set cache file.
     *
     * @param string $file
     *
     * @return self
     */
    public function setCacheFile($file)
    {
        $this->cacheFile = $file;
        return $this;
    }
    /**
     * Get cache file.
     *
     * @return string
     */
    public function getCacheFile()
    {
        return $this->cacheDir . $this->cacheFile;
    }
    /**
     * Create cache dir if not exists.
     *
     * @return void
     */
    protected function createCacheDir()
    {
        if (!file_exists($this->cacheDir)) {
            mkdir($this->cacheDir);
        }
    }
    /**
     * Create a cache file.
     *
     * @return void
     */
    protected function createCacheFile()
    {
        $this->createCacheDir();
        $cacheFilePath = $this->getCacheFile();
        if (!file_exists($cacheFilePath)) {
            touch($cacheFilePath);
        }
    }
    /**
     * {@inheritDoc}
     */
    public function get($key, $withExpired = false)
    {
        $key = $this->getActualCacheKey($key);
        $contents = $this->getCacheContents();
        if (empty($contents[$key])) {
            return null;
        }
        if ($withExpired) {
            return $contents[$key];
        }
        return $this->isValid($key) ? $contents[$key] : null;
    }
    /**
     * Get contents of a file with shared access.
     *
     * @param string $path
     *
     * @return string
     */
    public function sharedGet($path)
    {
        $contents = '';
        $handle = @fopen($path, File::READ_BINARY);
        if (false === $handle) {
            return $contents;
        }
        try {
            if (flock($handle, LOCK_SH)) {
                clearstatcache(true, $path);
                $contents = fread($handle, filesize($path) ?: 1);
                flock($handle, LOCK_UN);
            }
        } finally {
            fclose($handle);
        }
        return $contents;
    }
    /**
     * Write the contents of a file with exclusive lock.
     *
     * @param string $path
     * @param string $contents
     *
     * @return int
     */
    public function put($path, $contents)
    {
        return file_put_contents($path, $contents, LOCK_EX);
    }
    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        $cacheKey = $this->getActualCacheKey($key);
        $cacheFile = $this->getCacheFile();
        if (!file_exists($cacheFile) || !$this->isValid($cacheKey)) {
            $this->createCacheFile();
        }
        $contents = !empty(json_decode($this->sharedGet($cacheFile), true)) ? json_decode($this->sharedGet($cacheFile), true) : [];
        if (!empty($contents[$cacheKey]) && is_array($value)) {
            $contents[$cacheKey] = $value + $contents[$cacheKey];
        } else {
            $contents[$cacheKey] = $value;
        }
        return $this->put($cacheFile, json_encode($contents));
    }
    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        $cacheKey = $this->getActualCacheKey($key);
        $contents = $this->getCacheContents();
        if (isset($contents[$cacheKey])) {
            unset($contents[$cacheKey]);
            return false !== $this->put($this->getCacheFile(), json_encode($contents));
        }
        return false;
    }
    /**
     * {@inheritDoc}
     */
    public function keys()
    {
        $contents = $this->getCacheContents();
        if (is_array($contents)) {
            return array_keys($contents);
        }
        return [];
    }
    /**
     * Check if cache is still valid.
     *
     * @param string $key
     *
     * @return bool
     */
    public function isValid($key)
    {
        $key = $this->getActualCacheKey($key);
        $meta = isset($this->getCacheContents()[$key]) ? $this->getCacheContents()[$key] : [];
        if (empty($meta['expires_at'])) {
            return false;
        }
        return Carbon::now() < Carbon::createFromFormat(self::RFC_7231, $meta['expires_at']);
    }
    /**
     * Get cache contents.
     *
     * @return array|bool
     */
    public function getCacheContents()
    {
        $cacheFile = $this->getCacheFile();
        if (!file_exists($cacheFile)) {
            return false;
        }
        return !empty(json_decode($this->sharedGet($cacheFile), true)) ? json_decode($this->sharedGet($cacheFile), true) : [];
    }
    /**
     * Get actual cache key with prefix.
     *
     * @param string $key
     *
     * @return string
     */
    public function getActualCacheKey($key)
    {
        $prefix = $this->getPrefix();
        if (false === strpos($key, $prefix)) {
            $key = $prefix . $key;
        }
        return $key;
    }
}