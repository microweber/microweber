<?php

namespace Microweber\Utils\Adapters\Cache\Storage;

use Illuminate\Contracts\Cache\Store;
use Illuminate\Filesystem\Filesystem;
use Closure;

class FileStorage
{
    protected $files;
    protected $prefix;
    protected $tags;
    protected $directory;
    protected $directoryTags;

    public $memory = array();
    public $deleted_tags = array();

    public function __construct($prefix = '')
    {
        $this->files = new Filesystem();
        $this->prefix = $prefix;

        $this->directory = \Config::get('cache.stores.file.path').'/'.app()->environment();
        $this->tags = array();
        $this->directoryTags = $this->directory.(!empty($prefix) ? '/'.$prefix : '').'/tags';
    }

    public function appendLocale($key)
    {
        //        static $locale_suffix;
//        if ($locale_suffix){
//            $locale_suffix = '_' . app()->getLocale();
//        }
        return $key;
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        $key = $this->appendLocale($key);

        if (!empty($this->tags)) {
            foreach ($this->tags as $tag) {
                if (in_array($tag, $this->deleted_tags)) {
                    // return;
                }
            }
        }

        if (!isset($this->memory[ $key ])) {
            $path = $this->path($key);
            if (!$this->files->exists($path)) {
                return;
            }
            try {
                $expire = substr($contents = @file_get_contents($path), 0, 10);
            } catch (\Exception $e) {
                return;
            }

            // If the current time is greater than expiration timestamps we will delete
            // the file and return null. This helps clean up the old files and keeps
            // this directory much cleaner for us as old files aren't hanging out.
            if (time() >= $expire) {
                return $this->forget($key);
            }
            if ($contents) {
                $this->memory[ $key ] = @unserialize(substr($contents, 10));
            }
        }

        return $this->memory[ $key ];
    }

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $minutes
     */
    public function put($key, $value, $minutes)
    {
        $key = $this->appendLocale($key);
        $this->memory[ $key ] = $value;
        $value = $this->expiration($minutes).serialize($value);
        $path = $this->path($key);
        $path = $this->normalize_path($path, false);

        $this->createCacheDirectory($path);

        $skip = false;
        if (!empty($this->tags)) {
            foreach ($this->tags as $tag) {
                if (in_array($tag, $this->deleted_tags)) {
                    $skip = true;
                }
            }

            $this->_setTags($path);
        }
        if (!$skip) {
            @file_put_contents($path, $value);
        }
        // $this->files->put($path, $value);
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
        $string_array = array();
        if (is_string($tags)) {
            $string_array = explode(',', $tags);
        } elseif (is_array($tags)) {
            $string_array = $tags;
            array_walk($string_array, 'trim');
        }

        $this->tags = $string_array;

        return $this;
    }

    /**
     * Save Tags for cache.
     *
     * @param string $path
     */
    private function _setTags($path)
    {
        foreach ($this->tags as $tg) {
            $file = $this->directoryTags.'/'.$tg;
            $file = $this->normalize_path($file, false);

            if (!is_dir(dirname($path))) {
                $this->createCacheDirectory($file);
                //$this->files->put($file, $path);
                @file_put_contents($file, "$path");
            } else {
                if (is_file($file)) {
                    $farr = file($file);
                    if (!in_array($path, $farr)) {
                        @file_put_contents($file, "\n$path", FILE_APPEND);
                    }
                }
            }
        }
        // $this->tags = array();
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
        $key = $this->appendLocale($key);

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
        $key = $this->appendLocale($key);

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
     * Create the file cache directory if necessary.
     *
     * @param string $path
     */
    protected function createCacheDirectory($path)
    {
        return $this->mkdir_recursive(dirname($path));
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
        $key = $this->appendLocale($key);

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
        $key = $this->appendLocale($key);

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
        $key = $this->appendLocale($key);

        return $this->put($key, $value, 0);
    }

    /**
     * Remove an item from the cache by tags.
     *
     * @param string $string
     */
    public function forgetTags($string)
    {
        $string_array = explode(',', $string);
        if (is_array($string_array)) {
            foreach ($string_array as $k => $v) {
                $string_array[ $k ] = trim($v);
            }
        }
        foreach ($string_array as $sa) {
            $this->deleted_tags[] = $sa;
            $file = $this->directoryTags.'/'.$sa;
            if ($this->files->exists($file)) {
                $farr = file($file);
                foreach ($farr as $f) {
                    if ($f != false) {
                        $f = $this->normalize_path($f, false);
                        if (is_file($f)) {
                            @unlink($f);
                        }
                    }
                }
                @unlink($file);
            }
        }
    }

    /**
     * Remove an item from the cache.
     *
     * @param string $key
     */
    public function forget($key)
    {
        $key = $this->appendLocale($key);

        $file = $this->path($key);

        if ($this->files->exists($file)) {
            @$this->files->delete($file);
        } else {
            $folder = substr($file, 0, -6);
            if ($this->files->exists($folder)) {
                @$this->files->deleteDirectory($folder);
            }
        }

        $this->memory = array();
    }

    /**
     * Remove all items from the cache.
     *
     * @param string $tag
     */
    public function flush($all = false)
    {
        $this->memory = array();
        if (empty($this->tags) or $all == true) {
            if (is_dir($this->directory)) {
                $this->rmdir($this->directory);
            }
        } else {
            foreach ($this->tags as $tag) {
                if (in_array($tag, $this->deleted_tags)) {
                    //   break;
                }

                $items = $this->forgetTags($tag);
                $del = $this->directory.'/'.$tag;
                $del = $this->normalize_path($del);
                $this->rmdir($del);
            }
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
    protected function path($key)
    {
        $key = $this->appendLocale($key);

        $prefix = !empty($this->prefix) ? $this->prefix.'/' : '';
        $subdir = 'global';
        if (!empty($this->tags)) {
            $subdir = reset($this->tags);
        }

        return $this->directory.'/'.$subdir.'/'.$prefix.trim($key, '/').'.cache';
    }

    /**
     * Get the expiration time based on the given minutes.
     *
     * @param int $minutes
     *
     * @return int
     */
    protected function expiration($minutes)
    {
        if ($minutes === 0) {
            return 9999999999;
        }

        return time() + ($minutes * 60);
    }

    public function normalize_path($path, $slash_it = true)
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
            $path = $this->reduce_double_slashes($path);
        }

        return $path;
    }

    public function reduce_double_slashes($str)
    {
        return preg_replace('#([^:])//+#', '\\1/', $str);
    }

    public function mkdir_recursive($pathname)
    {
        if ($pathname == '') {
            return false;
        }
        is_dir(dirname($pathname)) || $this->mkdir_recursive(dirname($pathname));

        return is_dir($pathname) || @mkdir($pathname);
    }

    public function rmdir($directory, $empty = true)
    {
        // if the path has a slash at the end we remove it here
        if (substr($directory, -1) == DIRECTORY_SEPARATOR) {
            $directory = substr($directory, 0, -1);
        }

        // if the path is not valid or is not a directory ...
        if (!is_dir($directory)) {
            // ... we return false and exit the function
            return false;

            // ... if the path is not readable
        } elseif (!is_readable($directory)) {
            // ... we return false and exit the function
            return false;

            // ... else if the path is readable
        } else {
            // we open the directory
            $handle = opendir($directory);

            // and scan through the items inside
            while (false !== ($item = readdir($handle))) {
                // if the filepointer is not the current directory
                // or the parent directory
                if ($item != '.' && $item != '..') {
                    // we build the new path to delete
                    $path = $directory.DIRECTORY_SEPARATOR.$item;

                    // if the new path is a directory
                    if (is_dir($path)) {
                        // we call this function with the new path
                        $this->rmdir($path, $empty);
                        // if the new path is a file
                    } else {
                        //   $path = normalize_path($path, false);
                        try {
                            @unlink($path);
                        } catch (Exception $e) {
                        }
                    }
                }
            }

            // close the directory
            closedir($handle);

            // if the option to empty is not set to true
            if ($empty == false) {
                @rmdir($directory);
                // try to delete the now empty directory
                //            if (!rmdir($directory)) {
                //
                //                // return false if not possible
                //                return FALSE;
                //            }
            }

            // return success
            return true;
        }
    }
}
