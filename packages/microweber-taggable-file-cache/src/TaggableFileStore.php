<?php

namespace MicroweberPackages\TaggableFileCache;

use Closure;
use Illuminate\Cache\CacheLock;
use Illuminate\Cache\Events\CacheHit;
use Illuminate\Cache\Events\CacheMissed;
use Illuminate\Cache\Events\KeyForgotten;
use Illuminate\Cache\Events\KeyWritten;
use Illuminate\Cache\RetrievesMultipleKeys;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Carbon;
use Illuminate\Support\InteractsWithTime;
use MicroweberPackages\TaggableFileCache\CacheFileHandler\MemoryCacheFileHandler;

#[AllowDynamicProperties]
class TaggableFileStore implements Store
{
    use InteractsWithTime, RetrievesMultipleKeys;

    /**
     * The TaggableFilesystemManager instance.
     *
     * @var \MicroweberPackages\TaggableFileCache\TaggableFilesystemManager
     */
    protected $files;

    public $config;

    /**
     * The file cache directory.
     *
     * @var string
     */
    protected $directory;

    /**
     * The directory data of cache files
     * @var string|false
     */
    protected $directoryData = false;

    /**
     * The default cache time in seconds.
     *
     * @param  int|null $seconds
     */
    protected $default = null;

    /**
     * The directory data of tag map files
     * @var string|false
     */
    protected $directoryTags = false;

    /**
     * The prefix for the cache folder
     * @var string
     */
    protected $prefix = 'tfile';

    /**
     * The map of all tags
     * @var array
     */
    protected $tags = array();

    /**
     * Tags that are deleted (instance-scoped to avoid static memory accumulation)
     * @var array
     */
    protected $flushedTags = array();
    public $events = array();
    public $options = array();

    protected $emitEvents = false;

    /**
     * Create a new file cache store instance.
     *
     * @param  TaggableFilesystemManager $files
     * @param  string $directory
     * @param  array $options
     * @param  array $tags
     */
    public function __construct(TaggableFilesystemManager $files, $directory, $options = [], $tags = [])
    {
        $this->files = $files;
        $this->options = $options;
        $this->tags = $tags;

        // Use the directory passed in — the service provider is responsible for
        // including the environment segment so this class stays framework-agnostic.
        $this->directory = $this->normalizePath($directory);

        $this->directoryTags = $this->directory . (!empty($this->prefix) ? '/' . $this->prefix : '') . '/tags';
        $this->directoryData = $this->directory . (!empty($this->prefix) ? '/' . $this->prefix : '') . '/data';

        $this->directoryTags = $this->normalizePath($this->directoryTags);
        $this->directoryData = $this->normalizePath($this->directoryData);

        // By emitting events the RAM usage goes up twice, so we check if debugbar collector for cache is enabled
        try {
            if (function_exists('config') && function_exists('app') && app()->bound('config')) {
                $this->emitEvents = config('debugbar.collectors.cache', false);
            }
        } catch (\Throwable $e) {
            // No container available — skip event emission config
        }
    }


    public function has($key)
    {
        if (isset($this->files->cachedDataMemory[$key])) {
            return true;
        }

        $findTagPath = $this->_findCachePathByKey($key);
        if (!$findTagPath) {
            return false;
        }

        $findTagPath = $this->getPath() . $findTagPath;
        if ($this->files->exists($findTagPath)) {
            return true;
        }

        return false;
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        $cacheKey = $this->_cacheKey($key);

        if (isset($this->files->cachedDataMemory[$cacheKey])) {
            $data = $this->files->cachedDataMemory[$cacheKey];
            if ($this->emitEvents) {
                event(new CacheHit('file', $key, $data, $this->tags));
            }
            return $data;
        }

        $findTagPath = $this->_findCachePathByKey($key);
        if (!$findTagPath) {
            return;
        }

        $findTagPath = $this->getPath() . $findTagPath;
        if (!$this->files->exists($findTagPath)) {
            return;
        }
        $contents = null;
        try {
            $contents = $this->files->cacheHandler->readFromCache($findTagPath);
            $expire = substr($contents, 0, 10);
        } catch (\Exception $e) {
            $this->files->cachedDataMemory = [];
            return;
        }

        // If the current time is greater than expiration timestamps we will delete
        // the file and return null. This helps clean up the old files and keeps
        // this directory much cleaner for us as old files aren't hanging out.
        if ($this->currentTime() >= $expire) {
            $this->forget($key);
            return;
        }

        try {
            $data = unserialize(substr($contents, 10), ['allowed_classes' => false]);
        } catch (\Exception $e) {
            $this->forget($key);
            return;
        }

        if ($this->emitEvents) {
            if (is_null($data)) {
                event(new CacheMissed('file', $key, $this->tags));
            } else {
                event(new CacheHit('file', $key, $data, $this->tags));
            }
        }

        $this->files->cachedDataMemory[$cacheKey] = $data;
        return $data;
    }

    private function _cacheKey($key)
    {
        // on php 8 crc32 is faster than md5
        $cacheKey = $key . (is_array($this->tags) ? 'crc32-' . crc32(json_encode($this->tags)) : false);
        return $cacheKey;
    }

    private function _findCachePathByKey($key)
    {
        if (empty($this->tags)) {
            $this->tags[] = '___global';
        }

        $findTagPath = false;
        foreach ($this->tags as $tag) {
            $tagMap = $this->_getTagMapByName($tag);
            if (isset($tagMap[$key])) {
                $findTagPath = $tagMap[$key];
                break;
            }
        }

        return $findTagPath;
    }

    /**
     * Store an item in the cache for a given number of seconds.
     *
     * @param string $key
     * @param mixed $value
     * @param int $seconds
     */
    public function put($key, $value, $seconds = false)
    {
        $cacheKey = $this->_cacheKey($key);

        if (isset($this->files->cachedDataMemory[$cacheKey]) and $this->files->cachedDataMemory[$cacheKey] !== $value) {
            unset($this->files->cachedDataMemory[$cacheKey]);
        }

        if (!isset($this->files->cachedDataMemory[$cacheKey])) {
            $this->files->cachedDataMemory[$cacheKey] = $value;
            if (!$seconds) {
                $seconds = now()->addYear(4);
            }

            $value = $this->expiration($seconds) . serialize($value);

            $filename = $this->generatePathFilename($key);
            $cachePath = $this->getPath();
            $subPath = substr($filename, 0, 3) . DIRECTORY_SEPARATOR;

            if (!$this->files->isDirectory($cachePath . $subPath)) {
                $this->makeDirRecursive($cachePath . $subPath);
            }

            $path = $cachePath . DIRECTORY_SEPARATOR . $subPath . $filename;
            $path = $this->normalizePath($path, false);

            // Generate tag map files
            $this->_makeTagMapFiles();

            // Add key path to tag map
            $this->_addKeyPathToTagMap($key, $subPath . $filename);

            // Save key value in file
            $this->files->cacheHandler->writeToCache($path, $value);

            if ($this->emitEvents) {
                event(new KeyWritten($key, $value, $seconds));
            }
        }
    }

    public function putMany(array $values, $seconds)
    {
        foreach ($values as $key => $value) {
            $this->put($key, $value, $seconds);
        }

        return true;
    }

    public function many(array $keys)
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->get($key);
        }
        return $result;
    }

    /**
     * Set the event dispatcher instance.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher $events
     * @return void
     */
    public function setEventDispatcher(Dispatcher $events)
    {
        $this->events = $events;
    }

    /**
     * Set the default cache time in seconds.
     *
     * @param  int|null $seconds
     * @return $this
     */
    public function setDefaultCacheTime($seconds)
    {
        $this->default = $seconds;

        return $this;
    }

    /**
     * Tags for cache.
     *
     * @param mixed $tags
     * @return static
     */
    public function tags($tags)
    {
        $prepareTags = array();
        if (is_string($tags)) {
            $prepareTags = explode(',', $tags);
        } elseif (is_array($tags)) {
            $prepareTags = $tags;
            array_walk($prepareTags, 'trim');
        }

        // Clear Tags
        $clearTags = [];
        foreach ($prepareTags as $tag) {
            if (strpos($tag, '/')) {
                $tags = explode('/', $tag);
                $clearTags = array_merge($clearTags, $tags);
                continue;
            }
            $clearTags[] = $tag;
        }
        return new static($this->files, $this->directory, $this->options, $clearTags);
    }

    /**
     * Save Tags for cache.
     */
    private function _makeTagMapFiles()
    {
        $cacheFolder = $this->directoryTags;
        if (!is_dir($cacheFolder)) {
            $this->makeDirRecursive($cacheFolder);
        }

        if (empty($this->tags)) {
            $this->tags[] = '___global';
        }

        foreach ($this->tags as $tag) {
            $cacheFile = $this->_getTagMapPathByName($tag);
            if (!is_file($cacheFile)) {
                $this->files->cacheHandler->writeToCache($cacheFile, json_encode([]));
            }
        }
    }

    private function _getTagMapByName($tagName)
    {
        if (isset($this->files->tagMapCacheMemory[$tagName])) {
            return $this->files->tagMapCacheMemory[$tagName];
        }

        $cacheFile = $this->_getTagMapPathByName($tagName);
        $cacheFile = $this->normalizePath($cacheFile, false);
        if (!$this->files->isFile($cacheFile)) {
            return;
        }
        $cacheMapContent = false;
        if (is_file($cacheFile)) {
            $cacheMapContent = $this->files->cacheHandler->readFromCache($cacheFile);
            $cacheMapContent = @json_decode($cacheMapContent, true);
        }
        if (!$cacheMapContent) {
            $this->files->tagMapCacheMemory[$tagName] = [];
            return [];
        }
        $this->files->tagMapCacheMemory[$tagName] = $cacheMapContent;
        return $cacheMapContent;
    }

    private function _addKeyPathToTagMap($key, $filename)
    {
        foreach ($this->tags as $tag) {
            if (!isset($this->files->tagMapPathsCacheMemory[$tag])) {
                $cacheFile = $this->_getTagMapPathByName($tag);
                $cacheMapContent = false;
                if (is_file($cacheFile)) {
                    $cacheMapContent = $this->files->cacheHandler->readFromCache($cacheFile);
                    $cacheMapContent = json_decode($cacheMapContent, true);
                }
                if (!$cacheMapContent) {
                    $cacheMapContent = [];
                }
            } else {
                $cacheMapContent = $this->files->tagMapPathsCacheMemory[$tag];
            }

            if (!isset($cacheMapContent[$key])) {
                $cacheMapContent[$key] = $filename;
                $this->files->tagMapPathsCacheMemory[$tag] = $cacheMapContent;
                $cacheFile = $this->_getTagMapPathByName($tag);
                $this->files->cacheHandler->writeToCache($cacheFile, json_encode($cacheMapContent));
            }
        }
    }

    private function _getTagMapPathByName($tagName)
    {
        $cacheFile = $this->directoryTags . DIRECTORY_SEPARATOR . $tagName . '.json';
        $cacheFile = $this->normalizePath($cacheFile, false);

        return $cacheFile;
    }

    /**
     * Get an item from the cache, or store the default value.
     *
     * @param string $key
     * @param \DateTime|int $seconds
     * @param Closure $callback
     * @return mixed
     */
    public function remember($key, $seconds, Closure $callback)
    {
        $value = $this->get($key);

        if (!is_null($value)) {
            return $value;
        }

        $this->put($key, $value = $callback(), $seconds);

        return $value;
    }

    /**
     * Get an item from the cache, or store the default value forever.
     *
     * @param string $key
     * @param Closure $callback
     * @return mixed
     */
    public function rememberForever($key, Closure $callback)
    {
        if (!is_null($value = $this->get($key))) {
            return $value;
        }

        $this->forever($key, $value = $callback());

        return $value;
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param string $key
     * @param mixed $value
     * @return int
     */
    public function increment($key, $value = 1)
    {
        $oldValue = (int)$this->get($key);
        $newValue = $oldValue + $value;

        $this->put($key, $newValue);

        return $newValue;
    }

    /**
     * Decrement the value of an item in the cache.
     *
     * @param string $key
     * @param mixed $value
     * @return int
     */
    public function decrement($key, $value = 1)
    {
        $oldValue = (int)$this->get($key);
        $newValue = $oldValue - $value;

        $this->put($key, $newValue);

        return $newValue;
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param string $key
     * @param mixed $value
     */
    public function forever($key, $value)
    {
        return $this->put($key, $value, 0);
    }

    /**
     * Remove an item from the cache by tags.
     *
     * @param string $string
     */
    public function forgetTags($string)
    {
        throw new \LogicException('Not supported by this driver.');
    }

    /**
     * Remove an item from the cache.
     *
     * @param string $key
     * @return bool
     */
    public function forget($key)
    {
        $this->files->cachedDataMemory = [];
        $findTagPath = $this->_findCachePathByKey($key);
        $findTagPath = $this->getPath() . $findTagPath;

        if (!empty($this->tags)) {
            $this->flushedTags = array_merge($this->flushedTags, $this->tags);
        }

        try {
            if (is_file($findTagPath)) {
                @unlink($findTagPath);
            }
        } catch (\Exception $e) {
            //
        }

        if ($this->emitEvents) {
            event(new KeyForgotten('file', $key, $this->tags));
        }

        return true;
    }

    public function delete($key)
    {
        return $this->forget($key);
    }

    public $deletedFilesCache = [];

    /**
     * Remove all items from the cache.
     *
     * @param bool $all
     * @return bool
     */
    public function flush($all = true)
    {
        $this->files->cachedDataMemory = [];
        if (!empty($this->tags)) {
            if (!$all and $this->isTagFlushed()) {
                return true;
            }

            foreach ($this->tags as $tag) {
                $tagDetails = $this->_getTagMapByName($tag);
                if (!empty($tagDetails)) {
                    foreach ($tagDetails as $tagDetail) {
                        $tagPath = $this->getPath() . $tagDetail;
                        if (in_array($tagPath, $this->deletedFilesCache)) {
                            continue;
                        }
                        try {
                            if ($this->files->isFile($tagPath)) {
                                @unlink($tagPath);
                                $this->deletedFilesCache[] = $tagPath;
                            }
                        } catch (\Exception $e) {
                            //
                        }
                    }
                }

                $tagMapPath = $this->_getTagMapPathByName($tag);

                try {
                    if (!in_array($tagMapPath, $this->deletedFilesCache)) {
                        if ($this->files->isFile($tagMapPath)) {
                            $this->files->delete($tagMapPath);
                        }
                        $this->deletedFilesCache[] = $tagMapPath;
                    }
                } catch (\Exception $e) {
                    //
                }

                $this->flushedTags = array_merge($this->flushedTags, $this->tags);

                if (isset($this->tags[$tag])) {
                    unset($this->tags[$tag]);
                }
            }

            if ($this->emitEvents) {
                if (class_exists(\MicroweberPackages\TaggableFileCache\Events\CacheFlushed::class)) {
                    event(new Events\CacheFlushed('global', $this->flushedTags));
                }
            }
        }

        // Delete all tags
        if (empty($this->tags) || $all) {
            $mainCacheDir = $this->directory . '/' . $this->prefix;
            $mainCacheDir = $this->normalizePath($mainCacheDir);

            if (!is_dir($mainCacheDir)) {
                return false;
            }

            try {
                $this->files->deleteDirectory($mainCacheDir);
            } catch (\Exception $e) {
                //
            }
        }

        return true;
    }

    private function isTagFlushed()
    {
        if ($this->flushedTags && $this->tags) {
            if (in_array($this->tags, $this->flushedTags)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the Filesystem instance.
     *
     * @return \MicroweberPackages\TaggableFileCache\TaggableFilesystemManager
     */
    public function getFilesystem()
    {
        return $this->files;
    }

    /**
     * Get the working directory of the cache.
     *
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Get the cache key prefix.
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Get the full path for the cache data directory.
     *
     * @return string
     */
    protected function getPath()
    {
        $dir = $this->directoryData;
        $dir = $this->normalizePath($dir);

        if (!is_dir($dir)) {
            $this->makeDirRecursive($dir);
        }

        return $dir;
    }

    protected function generatePathFilename($key)
    {
        return md5(serialize($this->tags) . trim($key)) . '.cache';
    }

    /**
     * Get the expiration time based on the given seconds.
     *
     * @param  int $seconds
     * @return int
     */
    protected function expiration($seconds)
    {
        $time = $this->availableAt($seconds);

        return $seconds === 0 || $time > 9999999999 ? 9999999999 : $time;
    }

    public function normalizePath($path, $slash_it = true)
    {
        $path_original = $path;
        $s = DIRECTORY_SEPARATOR;
        $path = preg_replace('/[\/\\\]/', $s, $path);
        $path = str_replace($s . $s, $s, $path);
        if (strval($path) == '') {
            $path = $path_original;
        }
        if ($slash_it == false) {
            $path = rtrim($path, DIRECTORY_SEPARATOR);
        } else {
            $path .= DIRECTORY_SEPARATOR;
            $path = rtrim($path, DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR);
        }
        if (strval(trim($path)) == '' or strval(trim($path)) == '/') {
            $path = $path_original;
        }
        if ($slash_it == false) {
        } else {
            $path = $path . DIRECTORY_SEPARATOR;
            $path = $this->reduceDoubleSlashes($path);
        }

        return $path;
    }

    public function reduceDoubleSlashes($str)
    {
        return preg_replace('#([^:])//+#', '\\1/', $str);
    }

    public function makeDirRecursive($pathname)
    {
        if ($pathname == '') {
            return false;
        }
        is_dir(dirname($pathname)) || $this->makeDirRecursive(dirname($pathname));

        return is_dir($pathname) || @mkdir($pathname);
    }

    /**
     * Get the expiration time of the key.
     *
     * @param  int  $seconds
     * @return float
     */
    protected function calculateExpiration($seconds)
    {
        return $this->toTimestamp($seconds);
    }

    /**
     * Get the UNIX timestamp, with milliseconds, for the given number of seconds in the future.
     *
     * @param  int  $seconds
     * @return float
     */
    protected function toTimestamp($seconds)
    {
        return $seconds > 0 ? (Carbon::now()->getPreciseTimestamp(3) / 1000) + $seconds : 0;
    }

    /**
     * Get a lock instance.
     *
     * @param  string  $name
     * @param  int  $seconds
     * @param  string|null  $owner
     * @return \Illuminate\Contracts\Cache\Lock
     */
    public function lock($name, $seconds = 0, $owner = null)
    {
        return new CacheLock($this, $name, $seconds, $owner);
    }

    /**
     * Restore a lock instance using the owner identifier.
     *
     * @param  string  $name
     * @param  string  $owner
     * @return \Illuminate\Contracts\Cache\Lock
     */
    public function restoreLock($name, $owner)
    {
        return $this->lock($name, 0, $owner);
    }

    /**
     * Update the expiration time of an item in the cache.
     *
     * @param  string  $key
     * @param  int  $seconds
     * @return bool
     */
    public function touch($key, $seconds)
    {
        $value = $this->get($key);

        if (is_null($value)) {
            return false;
        }

        return $this->put($key, $value, $seconds) !== false;
    }
}