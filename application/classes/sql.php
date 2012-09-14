<?php

// Create SQL database queries
class SQL {

    function delete($t, $w = 0) {
        $q = "DELETE FROM $t";
        list ( $w, $p ) = $this->where($w);
        if ($w)
            $q .= " WHERE $w";
        return ($s = $this->query($q, $p)) ? $s->rowCount() : 0;
    }

    function select($c = 0, $t, $w = 0, $l = 0, $o = 0, $s = 0) {

        if ($c != false) {

        } else {
            $c = '*';
        }



        $q = "SELECT $c FROM \"$t\"";
        list ( $w, $p ) = $this->where($w);
        if ($w)
            $q .= " WHERE $w";

        return array(
            $q . ($s ? " ORDER BY $s" : '') . ($l ? " LIMIT $o,$l" : ''),
            $p
        );
    }

    function count($t, $w = 0) {
        list ( $q, $p ) = $this->select('COUNT(*)', $t, $w);
        return $this->column($q, $p);
    }

    function insert($t, $d) {
        $q = "INSERT INTO $t (\"" . implode('","', array_keys($d)) . '")VALUES(' . rtrim(str_repeat('?,', count($d)), ',') . ')';
        return $this->query($q, array_values($d)) ? $this->pdo->lastInsertId() : 0;
    }

    function update($t, $d, $w = NULL) {
        $q = "UPDATE $t SET \"" . implode('"=?,"', array_keys($d)) . '"=? WHERE ';
        list ( $a, $b ) = $this->where($w);
        return (($s = $this->query($q . $a, array_merge(array_values($d), $b))) ? $s->rowCount() : NULL);
    }

    function where($w = 0) {
        $a = $s = array();
        if ($w) {
            foreach ($w as $c => $v) {
                if (is_int($c)) {
                    $s [] = $v;
                } else {
                    if (is_string($v)) {
                        $v = mysql_real_escape_string($v);
                        $s [] = "\"$c\"=" . $v;
                    } elseif (is_array($v)) {
                        $v0 = ($v [0]);
                        $v1 = mysql_real_escape_string($v [1]);
                        $s [] = "\"$c\"{$v0}" . $v1;
                    }

                    // $s [] = "\"$c\"=?";
                    // $a [] = $v;
                }
            }
        }
        return array(
            join(' AND ', $s),
            $a
        );
    }

}