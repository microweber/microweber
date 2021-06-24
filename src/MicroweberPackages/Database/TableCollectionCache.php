<?php

namespace MicroweberPackages\Database;


class TableCollectionCache
{
    public static $__data = [];

    /**
     * @param string $table
     * @param array $params
     * @param $data
     */
    public static function setCache(string $table, $params, $data)
    {
        self::$__data[$table][self::__generateKey($table, $params)] = $data;
    }

    /**
     * @param string $table
     * @param array $params
     * @return mixed
     */
    public static function getCache(string $table, $params)
    {
        $key = self::__generateKey($table, $params);

        if (isset(self::$__data[$table][$key])) {
            return self::$__data[$table][$key];
        }
    }

    /**
     * @param $table
     */
    public static function flushCache($table)
    {
        if (isset(self::$__data[$table])) {
            unset(self::$__data[$table]);
        }
    }

    /**
     * @param $table
     * @param $params
     * @return string
     */
    private static function __generateKey($table, $params)
    {
        $hashParamsTable = $table . crc32(serialize($params));

        return $hashParamsTable;
    }
}
