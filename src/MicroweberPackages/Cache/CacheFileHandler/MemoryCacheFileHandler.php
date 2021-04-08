<?php

namespace MicroweberPackages\Cache\CacheFileHandler;

class MemoryCacheFileHandler extends CacheFileHandler
{
    public $cacheMemory = ['files'=>[]];

    // LOCK_SH to acquire a shared lock (reader).
    public function readMetaAndLock($file, int $lock = LOCK_SH)
    {
        if (isset($this->cacheMemory[$file])) {
            return $this->cacheMemory[$file];
        }

        return $this->cacheMemory[$file] = parent::readMetaAndLock($file, $lock);
    }

    /**
     * Deletes and closes file.
     * @param  resource $handle
     */
    public function writeToCache(string $key, $data, array $dp = [])
    {
        if (isset($this->cacheMemory[$key])) {
            unset($this->cacheMemory[$key]);
        }

        if (isset($this->cacheMemory['files']) and isset($this->cacheMemory['files'][$key])) {
           unset($this->cacheMemory['files'][$key]);
        }

        return parent::writeToCache($key, $data, $dp);
    }

    protected function readData(array $meta)
    {

        if (isset($meta['file']) and isset($this->cacheMemory['files'][$meta['file']])) {
           return $this->cacheMemory['files'][$meta['file']];
        }

        if (is_resource($meta[self::HANDLE])) {
            $v = parent::readData($meta);;
            $this->cacheMemory['files'][$meta['file']] = $v;
            return $v;
        }

    }

}