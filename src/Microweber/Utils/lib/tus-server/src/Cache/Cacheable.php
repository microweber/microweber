<?php

namespace TusPhp\Cache;

interface Cacheable
{
    /** @see https://tools.ietf.org/html/rfc7231#section-7.1.1.1 */
    const RFC_7231 = 'D, d M Y H:i:s \\G\\M\\T';
    /**
     * Get data associated with the key.
     *
     * @param string $key
     * @param bool   $withExpired
     *
     * @return mixed
     */
    public function get($key, $withExpired = false);
    /**
     * Set data to the given key.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    public function set($key, $value);
    /**
     * Delete data associated with the key.
     *
     * @param string $key
     *
     * @return bool
     */
    public function delete($key);
    /**
     * Delete all data associated with the keys.
     *
     * @param array $keys
     *
     * @return bool
     */
    public function deleteAll(array $keys);
    /**
     * Get time to live.
     *
     * @return int
     */
    public function getTtl();
    /**
     * Get cache keys.
     *
     * @return array
     */
    public function keys();
    /**
     * Set cache prefix.
     *
     * @param string $prefix
     *
     * @return self
     */
    public function setPrefix($prefix);
    /**
     * Get cache prefix.
     *
     * @return string
     */
    public function getPrefix();
}