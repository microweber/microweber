<?php

namespace Goodby\CSV\Import\Standard\Observer;

class PdoObserver
{
    private $table;
    private $columns;

    private $dsn;
    private $options = null;

    private $pdo = null;

    public function __construct($table, $columns, $dsn, $options)
    {
        $this->table = $table;
        $this->columns = $columns;

        $this->dsn = $dsn;
        $this->options = $options;
    }

    public function notify($line)
    {
        if ($this->pdo === null) {
            $this->pdo = new \PDO($this->dsn, $this->options['user'], $this->options['password']);
        }

        $this->execute($line);
    }

    private function execute($line)
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
                    return 1;
                }

                if (strtolower($value) === 'false') {
                    return 0;
                }

                return $value;
            }

            throw new \InvalidArgumentException('value is invalid: ' . var_export($value, 1));
        }, $line);

        $prepare = array_map(function() {
            return '?';
        }, $line);

        $sql = 'INSERT INTO ' . $this->table . '(' . join(', ', $this->columns) . ')' .
               ' VALUES(' . join(',', $prepare) . ')';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($line);
    }
}
