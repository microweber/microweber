<?php

namespace MicroweberPackages\Cache;

use Closure;
use Illuminate\Cache\RetrievesMultipleKeys;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\InteractsWithTime;
use Illuminate\Support\Str;

class TaggableFileStore implements Store
{
    use InteractsWithTime, RetrievesMultipleKeys;

    /**
     * The Illuminate Filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The file cache directory.
     *
     * @var string
     */
    protected $directory;

    /**
     *  The directory data of cache files
     * @var array[]
     */
    protected $directoryData = false;

    /*
     * The directory data of tag map files
     * @var array[]
     */
    protected $directoryTags = false;

    /**
     * The prefix for the cache folder
     * @var string
     */
    protected $prefix = 'tfile';

    /**
     * The map off all tags
     * @var array
     */
    protected $tags = array();

    /**
     * Create a new file cache store instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem $files
     * @param  string $directory
     * @param  array $options
     */
    public function __construct(Filesystem $files, $directory, $options = [])
    {
        $this->files = $files;
        $this->directory = $directory;

        $this->directory = \Config::get('cache.stores.tfile.path').'/'.app()->environment();

        $this->directoryTags = $this->directory.(!empty($this->prefix) ? '/'.$this->prefix : '').'/tags';
        $this->directoryData = $this->directory.(!empty($this->prefix) ? '/'.$this->prefix : '').'/data';

        $this->directoryTags = $this->normalizePath($this->directoryTags);
        $this->directoryData = $this->normalizePath($this->directoryData);
    }

    /**
     * Retrieve an item from the cache by key.
     *
     *
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        $findTagPath = $this->_findCachePathByKey($key);

        // Clear instance of tags
        $this->tags = array(); // TODO DONT REMOVE THIS

        if (!$findTagPath) {
            return;
        }

        $findTagPath = $this->getPath() . $findTagPath;
        if (!$this->files->exists($findTagPath)) {
            return;
        }

        try {
            $expire = substr(
                $contents = file_get_contents($findTagPath, true), 0, 10
            );
        } catch (\Exception $e) {
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
            $data = unserialize(substr($contents, 10));
        } catch (Exception $e) {
            $this->forget($key);
            return;
        }

        return $data;
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
     * Store an item in the cache for a given number of minutes.
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $seconds
     */
    public function put($key, $value, $seconds)
    {
        $value = $this->expiration($seconds) . serialize($value);

        $filename = $this->generatePathFilename($key);
        $cachePath = $this->getPath();
        $subPath = substr($filename, 0, 3) . DIRECTORY_SEPARATOR;

        if (!$this->files->isDirectory($cachePath . $subPath)) {
            $this->makeDirRecursive($cachePath . $subPath);
        }

        $path = $cachePath .DIRECTORY_SEPARATOR . $subPath . $filename;
        $path = $this->normalizePath($path, false);

        // Generate tag map files
        $this->_makeTagMapFiles();

        // Add key path to tag map
        $this->_addKeyPathToTagMap($key, $subPath . $filename);

        // Save key value in file
        file_put_contents($path, $value);

        // Clear instance of tags
        $this->tags = array();
    }

    public function putMany(array $values, $seconds) {
        throw new \LogicException('This method is not supported.');
    }

    public function many(array $keys) {
        throw new \LogicException('This method is not supported.');
    }


    /**
     * Set the event dispatcher instance.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function setEventDispatcher(Dispatcher $events)
    {
        $this->events = $events;
    }

    /**
     * Set the default cache time in seconds.
     *
     * @param  int|null  $seconds
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
     * @param string $string
     *
     * @return object
     */
    public function tags($tags)
    {
        // Clear cached tags
        $this->tags = array();

        $prepareTags = array();
        if (is_string($tags)) {
            $prepareTags = explode(',', $tags);
        } elseif (is_array($tags)) {
            $prepareTags = $tags;
            array_walk($prepareTags, 'trim');
        }

        $this->tags = $prepareTags;

        return $this;
    }

    /**
     * Save Tags for cache.
     *
     * @param string $path
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
                file_put_contents($cacheFile, json_encode([]));
            }
        }
    }

    private function _getTagMapByName($tagName)
    {
        $cacheFile = $this->_getTagMapPathByName($tagName);

        if (!$this->files->isFile($cacheFile)) {
            return;
        }

        $cacheMapContent = file_get_contents($cacheFile);
        $cacheMapContent = json_decode($cacheMapContent, true);

        return $cacheMapContent;
    }

    private function _addKeyPathToTagMap($key, $filename)
    {
        foreach ($this->tags as $tag) {

            $cacheFile = $this->_getTagMapPathByName($tag);

            $cacheMapContent = file_get_contents($cacheFile);
            $cacheMapContent = json_decode($cacheMapContent, true);

            $cacheMapContent[$key] = $filename;

            file_put_contents($cacheFile, json_encode($cacheMapContent, JSON_PRETTY_PRINT));
        }

    }

    private function _getTagMapPathByName($tagName) {

        $cacheFile = $this->directoryTags . '\\'. $tagName .'.json';
        $cacheFile = $this->normalizePath($cacheFile, false);

        return $cacheFile;
    }

    /**
     * Get an item from the cache, or store the default value.
     *
     * @param string        $key
     * @param \DateTime|int $minutes
     * @param Closure       $callback
     *
     * @return mixed
     */
    public function remember($key, $minutes, Closure $callback)
    {

        // If the item exists in the cache we will just return this immediately
        // otherwise we will execute the given Closure and cache the result
        // of that execution for the given number of minutes in storage.
        if (!is_null($value = $this->get($key))) {
            return $value;
        }

        $this->put($key, $value = $callback(), $minutes);

        return $value;
    }

    /**
     * Get an item from the cache, or store the default value forever.
     *
     * @param string  $key
     * @param Closure $callback
     *
     * @return mixed
     */
    public function rememberForever($key, Closure $callback)
    {
        // If the item exists in the cache we will just return this immediately
        // otherwise we will execute the given Closure and cache the result
        // of that execution for the given number of minutes. It's easy.
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
     * @param mixed  $value
     *
     * @throws \LogicException
     */
    public function increment($key, $value = 1)
    {
        throw new \LogicException('Not supported by this driver.');
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @throws \LogicException
     */
    public function decrement($key, $value = 1)
    {
        throw new \LogicException('Not supported by this driver.');
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param string $key
     * @param mixed  $value
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
     */
    public function forget($key)
    {
        $findTagPath = $this->_findCachePathByKey($key);
        $findTagPath = $this->getPath() . $findTagPath;

        if ($this->files->exists($findTagPath)) {
            @$this->files->delete($findTagPath);
        }
    }
    
    public function delete($key)
    {
      return $this->forget($key);   
    }

    /**
     * Remove all items from the cache.
     *
     * @param string $tag
     */
    public function flush($all = false)
    {
        if (!empty($this->tags)) {
            foreach ($this->tags as $tag) {
                $tagDetails = $this->_getTagMapByName($tag);
                if (!empty($tagDetails)) {
                    foreach ($tagDetails as $tagDetail) {
                        $tagPath = $this->getPath() . $tagDetail;
                        if ($this->files->isFile($tagPath)) {
                            $this->files->delete($tagPath);
                        }
                    }
                }

                $tagMapPath = $this->_getTagMapPathByName($tag);
                if ($this->files->isFile($tagMapPath)) {
                    $this->files->delete($tagMapPath);
                }
                if (isset($this->tags[$tag])) {
                    unset($this->tags[$tag]);
                }
            }
        }

        // Delete all tags
        if (empty($this->tags) || $all) {

            $mainCacheDir = $this->directory . '/' . $this->prefix;
            $mainCacheDir = $this->normalizePath($mainCacheDir);

            $this->files->deleteDirectory($mainCacheDir);
        }
    }

    /**
     * Get the Filesystem instance.
     *
     * @return \Illuminate\Filesystem\Filesystem
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
     * Get the full path for the given cache key.
     *
     * @param string $key
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

    protected function generatePathFilename($key) {

        $key = trim($key);

        $tagsHash = md5(serialize($this->tags) . $key);

        return $tagsHash  .'.cache';
    }


    /**
     * Get the expiration time based on the given seconds.
     *
     * @param  int  $seconds
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
        $path = str_replace($s.$s, $s, $path);
        if (strval($path) == '') {
            $path = $path_original;
        }
        if ($slash_it == false) {
            $path = rtrim($path, DIRECTORY_SEPARATOR);
        } else {
            $path .= DIRECTORY_SEPARATOR;
            $path = rtrim($path, DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR);
        }
        if (strval(trim($path)) == '' or strval(trim($path)) == '/') {
            $path = $path_original;
        }
        if ($slash_it == false) {
        } else {
            $path = $path.DIRECTORY_SEPARATOR;
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

}
