<?php

namespace MicroweberPackages\Cache\CacheFileHandler;

class MemoryCacheFileHandler extends CacheFileHandler
{
    public $cacheMemory = [];

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
        return parent::writeToCache($key, $data, $dp);
    }


}