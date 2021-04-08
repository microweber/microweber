<?php

namespace MicroweberPackages\Cache\CacheFileHandler;

class CacheFileHandler
{
    /** dependency */
    public const
        PRIORITY = 'priority',
        EXPIRATION = 'expire',
        EXPIRE = 'expire',
        SLIDING = 'sliding',
        TAGS = 'tags',
        FILES = 'files',
        ITEMS = 'items',
        CONSTS = 'consts',
        CALLBACKS = 'callbacks',
        NAMESPACES = 'namespaces',
        ALL = 'all';

    /** @public cache file structure: meta-struct size + serialized meta-struct + data */
    public const META_HEADER_LEN = 6;
    public const META_TIME = 'time'; // timestamp
    public const META_EXPIRE = 'expire'; // expiration timestamp
    public const META_DELTA = 'delta'; // relative (sliding) expiration
    public const META_CALLBACKS = 'callbacks'; // array of callbacks (function, args)
    public const META_ITEMS = 'di'; // array of dependent items (file => timestamp)
    public const META_SERIALIZED = 'serialized'; // is content serialized?

    /** @var array */
    public $locks;

    /** additional cache structure */
    public const
        FILE = 'file',
        HANDLE = 'handle';

    public function readFromCache(string $path)
    {
        $meta = $this->readMetaAndLock($path, LOCK_SH);

        return $meta ? $this->readData($meta) : null;
    }

    public function writeToCache(string $key, $data, array $dp = [])
    {
        $meta = [
            self::META_TIME => microtime(),
        ];

        if (isset($dp[self::EXPIRATION])) {
            if (empty($dp[self::SLIDING])) {
                $meta[self::META_EXPIRE] = $dp[self::EXPIRATION] + time(); // absolute time
            } else {
                $meta[self::META_DELTA] = (int)$dp[self::EXPIRATION]; // sliding time
            }
        }

        if (isset($dp[self::ITEMS])) {
            foreach ($dp[self::ITEMS] as $item) {
                $depFile = $item;
                $m = $this->readMetaAndLock($depFile, LOCK_SH);
                $meta[self::META_ITEMS][$depFile] = $m[self::META_TIME] ?? null;
                unset($m);
            }
        }

        if (isset($dp[self::CALLBACKS])) {
            $meta[self::META_CALLBACKS] = $dp[self::CALLBACKS];
        }

        if (!isset($this->locks[$key])) {
            $this->lock($key);
            if (!isset($this->locks[$key])) {
                return;
            }
        }
        $handle = $this->locks[$key];
        unset($this->locks[$key]);

        $cacheFile = $key;

//        if (isset($dp[Cache::TAGS]) || isset($dp[Cache::PRIORITY])) {
//            if (!$this->journal) {
//                throw new Nette\InvalidStateException('CacheJournal has not been provided.');
//            }
//            $this->journal->write($cacheFile, $dp);
//        }

        ftruncate($handle, 0);

        if (!is_string($data)) {
            $data = serialize($data);
            $meta[self::META_SERIALIZED] = true;
        }

        $head = serialize($meta);
        $head = str_pad((string)strlen($head), 6, '0', STR_PAD_LEFT) . $head;
        $headLen = strlen($head);

        do {
            if (fwrite($handle, str_repeat("\x00", $headLen)) !== $headLen) {
                break;
            }

            if (fwrite($handle, $data) !== strlen($data)) {
                break;
            }

            fseek($handle, 0);
            if (fwrite($handle, $head) !== $headLen) {
                break;
            }

            flock($handle, LOCK_UN);
            fclose($handle);
            return;
        } while (false);

        $this->delete($cacheFile, $handle);


        return true;
    }

    // LOCK_SH to acquire a shared lock (reader).
    public function readMetaAndLock($file, int $lock = LOCK_SH)
    {
        $handle = @fopen($file, 'r+b'); // @ - file may not exist
        if (!$handle) {
            return null;
        }

        flock($handle, $lock);

        $size = (int)stream_get_contents($handle, self::META_HEADER_LEN);
        if ($size) {
            $meta = stream_get_contents($handle, $size, self::META_HEADER_LEN);
            $meta = unserialize($meta);
            $meta[self::FILE] = $file;
            $meta[self::HANDLE] = $handle;
            return $meta;
        }

        flock($handle, LOCK_UN);
        fclose($handle);
        return null;
    }

    /**
     * Deletes and closes file.
     * @param  resource $handle
     */
    private static function delete(string $file, $handle = null): void
    {
        if (is_file($file)) {
            if (@unlink($file)) { // @ - file may not already exist
                if ($handle) {
                    flock($handle, LOCK_UN);
                    fclose($handle);
                }
                return;
            }
        }


        if (!$handle) {
            $handle = @fopen($file, 'r+'); // @ - file may not exist
        }
        if (!$handle) {
            return;
        }

        flock($handle, LOCK_EX);
        ftruncate($handle, 0);
        flock($handle, LOCK_UN);
        fclose($handle);
        if (is_file($file)) {
            @unlink($file); // @ - file may not already exist
        }
    }

    protected function readData(array $meta)
    {
        $data = null;
        if (is_resource($meta[self::HANDLE])) {
            $data = stream_get_contents($meta[self::HANDLE]);
            flock($meta[self::HANDLE], LOCK_UN);
            fclose($meta[self::HANDLE]);
            return empty($meta[self::META_SERIALIZED]) ? $data : unserialize($data);
        }

    }

    public function lock(string $key): void
    {
        $cacheFile = $key;
        if (!is_dir($dir = dirname($cacheFile))) {
            @mkdir($dir); // @ - directory may already exist
        }
        $handle = @fopen($cacheFile, 'c+b');
        if (!$handle) {
            return;
        }

        $this->locks[$key] = $handle;
        flock($handle, LOCK_EX);
    }
}