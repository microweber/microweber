<?php

namespace Goodby\CSV\Export\Standard;

use SplFileObject;

class CsvFileObject extends SplFileObject
{
    const FILE_MODE_WRITE  = 'w';
    const FILE_MODE_APPEND = 'a';

    /**
     * newline character
     * @var string
     */
    private $newline = "\n";

    /**
     * CSV filter
     * @var callable
     */
    private $csvFilter;

    /**
     * @param string $newline
     * @return void
     */
    public function setNewline($newline)
    {
        $this->newline = $newline;
    }

    /**
     * Set csv filter
     * @param callable $filter
     */
    public function setCsvFilter($filter)
    {
        $this->csvFilter = $filter;
    }

    /**
     * Write a field array as a CSV line
     * @param array   $fields
     * @param string  $delimiter
     * @param string  $enclosure
     * @return int|void
     */
    public function fputcsv($fields, $delimiter = null, $enclosure = null)
    {
        // Temporary output a line to memory to get line as string
        $fp = fopen('php://temp', 'w+');
        $arguments = func_get_args();
        array_unshift($arguments, $fp);
        call_user_func_array('fputcsv', $arguments);
        rewind($fp);

        $line = '';

        while ( feof($fp) === false ) {
            $line .= fgets($fp);
        }

        fclose($fp);

        /**
         * Because the php_fputcsv() implementation in PHP´s source code
         * has a hardcoded "\n", this method replaces the last LF code
         * with what the client code wishes.
         */
        $line = rtrim($line, "\n"). $this->newline;

        if ( is_callable($this->csvFilter) ) {
            $line = call_user_func($this->csvFilter, $line);
        }

        return $this->fwrite($line);
    }
}
