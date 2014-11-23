<?php

namespace Goodby\CSV\Import\Standard\StreamFilter;

use php_user_filter;
use RuntimeException;

class ConvertMbstringEncoding extends php_user_filter
{
    /**
     * @var string
     */
    const FILTER_NAMESPACE = 'convert.mbstring.encoding.';

    /**
     * @var bool
     */
    private static $hasBeenRegistered = false;

    /**
     * @var string
     */
    private $fromCharset;

    /**
     * @var string
     */
    private $toCharset;

    /**
     * Return filter name
     * @return string
     */
    public static function getFilterName()
    {
        return self::FILTER_NAMESPACE.'*';
    }

    /**
     * Register this class as a stream filter
     * @throws \RuntimeException
     */
    public static function register()
    {
        if ( self::$hasBeenRegistered === true ) {
            return;
        }

        if ( stream_filter_register(self::getFilterName(), __CLASS__) === false ) {
            throw new RuntimeException('Failed to register stream filter: '.self::getFilterName());
        }

        self::$hasBeenRegistered = true;
    }

    /**
     * Return filter URL
     * @param string $filename
     * @param string $fromCharset
     * @param string $toCharset
     * @return string
     */
    public static function getFilterURL($filename, $fromCharset, $toCharset = null)
    {
        if ( $toCharset === null ) {
            return sprintf('php://filter/convert.mbstring.encoding.%s/resource=%s', $fromCharset, $filename);
        } else {
            return sprintf('php://filter/convert.mbstring.encoding.%s:%s/resource=%s', $fromCharset, $toCharset, $filename);
        }
    }

    /**
     * @return bool
     */
    public function onCreate()
    {
        if ( strpos($this->filtername, self::FILTER_NAMESPACE) !== 0 ) {
            return false;
        }

        $parameterString = substr($this->filtername, strlen(self::FILTER_NAMESPACE));

        if ( ! preg_match('/^(?P<from>[-\w]+)(:(?P<to>[-\w]+))?$/', $parameterString, $matches) ) {
            return false;
        }

        $this->fromCharset = isset($matches['from']) ? $matches['from'] : 'auto';
        $this->toCharset   = isset($matches['to'])   ? $matches['to']   : mb_internal_encoding();

        return true;
    }

    /**
     * @param string $in
     * @param string $out
     * @param string $consumed
     * @param $closing
     * @return int
     */
    public function filter($in, $out, &$consumed, $closing)
    {
        while ( $bucket = stream_bucket_make_writeable($in) ) {
            $bucket->data = mb_convert_encoding($bucket->data, $this->toCharset, $this->fromCharset);
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }

        return PSFS_PASS_ON;
    }
}
