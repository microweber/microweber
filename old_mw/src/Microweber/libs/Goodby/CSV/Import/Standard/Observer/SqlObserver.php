<?php

namespace Goodby\CSV\Import\Standard\Observer;

class SqlObserver
{
    private $table;
    private $columns;
    private $path;

    private $file = null;

    public function __construct($table, $columns, $path)
    {
        $this->table = $table;
        $this->columns = $columns;
        $this->path = $path;
    }

    public function notify($line)
    {
        $sql = $this->buildSql($line);

        if ($this->file === null) {
            $this->file = new \SplFileObject($this->path, 'a');
        }

        $this->file->fwrite($sql);
    }

    private function buildSql($line)
    {
        $line = array_map(function($value) {
            $number = filter_var($value, FILTER_VALIDATE_INT);

            if ($number !== false) {
                return $number;
            }

            if (is_string($value)) {
                if (strtolower($value) === 'null') {
                    return 'NULL';
                }

                if (strtolower($value) === 'true') {
                    return 'true';
                }

                if (strtolower($value) === 'false') {
                    return 'false';
                }

                return '"' . addslashes($value) . '"';
            }

            throw new \InvalidArgumentException('value is invalid: ' . var_export($value, 1));
        }, $line);

        return 'INSERT INTO ' . $this->table . '(' . join(', ', $this->columns) . ')' .
               ' VALUES(' . join(', ', $line) . ');';
    }
}
