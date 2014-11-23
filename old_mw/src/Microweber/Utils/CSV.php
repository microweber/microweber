<?php
/**
 * Coseva CSV.
 *
 * A friendly, object-oriented alternative for parsing and filtering CSV files
 * with PHP.
 *
 * @package Coseva
 * @subpackage CSV
 * @copyright 2013 Johnny Freeman
 */
namespace Microweber\Utils;

// original namespace
//namespace Coseva;

use \SplFileObject,
    \LimitIterator,
    \IteratorAggregate,
    \ArrayIterator,
    \Iterator,
    \InvalidArgumentException;



class CSV {
    // take a CSV line (utf-8 encoded) and returns an array
    // 'string1,string2,"string3","the ""string4"""' => array('string1', 'string2', 'string3', 'the "string4"')
    static public function parseString($string, $separator = ',') {
        $values = array();
        $string = str_replace("\r\n", '', $string); // eat the traling new line, if any
        if ($string == '') return $values;
        $tokens = explode($separator, $string);
        $count = count($tokens);
        for ($i = 0; $i < $count; $i++) {
            $token = $tokens[$i];
            $len = strlen($token);
            $newValue = '';
            if ($len > 0 and $token[0] == '"') { // if quoted
                $token = substr($token, 1); // remove leading quote
                do { // concatenate with next token while incomplete
                    $complete = Csv::_hasEndQuote($token);
                    $token = str_replace('""', '"', $token); // unescape escaped quotes
                    $len = strlen($token);
                    if ($complete) { // if complete
                        $newValue .= substr($token, 0, -1); // remove trailing quote
                    } else { // incomplete, get one more token
                        $newValue .= $token;
                        $newValue .= $separator;
                        if ($i == $count - 1) throw new Exception('Illegal unescaped quote.');
                        $token = $tokens[++$i];
                    }
                } while (!$complete);

            } else { // unescaped, use token as is
                $newValue .= $token;
            }

            $values[] = $newValue;
        }
        return $values;
    }

    static public function escapeString($string) {
        $string = str_replace('"', '""', $string);
        if (strpos($string, '"') !== false or strpos($string, ',') !== false or strpos($string, "\r") !== false or strpos($string, "\n") !== false) {
            $string = '"'.$string.'"';
        }

        return $string;
    }

    // checks if a string ends with an unescaped quote
    // 'string"' => true
    // 'string""' => false
    // 'string"""' => true
    static public function _hasEndQuote($token) {
        $len = strlen($token);
        if ($len == 0) return false;
        elseif ($len == 1 and $token == '"') return true;
        elseif ($len > 1) {
            while ($len > 1 and $token[$len-1] == '"' and $token[$len-2] == '"') { // there is an escaped quote at the end
                $len -= 2; // strip the escaped quote at the end
            }
            if ($len == 0) return false; // the string was only some escaped quotes
            elseif ($token[$len-1] == '"') return true; // the last quote was not escaped
            else return false; // was not ending with an unescaped quote
        }
    }

    // very basic separator detection function
    static public function detectSeparator($filename, $separators = array(',', ';')) {
        $file = fopen($filename, 'r');
        $string = fgets($file);
        fclose($file);
        $matched = array();
        foreach ($separators as $separator) if (preg_match("/$separator/", $string)) $matched[] = $separator;
        if (count($matched) == 1) return $matched[0];
        else return null;
    }
}

class CsvReader implements Iterator {

    protected $fileHandle = null;
    protected $position = null;
    protected $filename = null;
    protected $currentLine = null;
    protected $currentArray = null;
    protected $separator = ',';


    public function __construct($filename, $separator = ',') {
        $this->separator = $separator;
        $this->fileHandle = fopen($filename, 'r');
        if (!$this->fileHandle) return;
        $this->filename = $filename;
        $this->position = 0;
        $this->_readLine();
    }

    public function __destruct() {
        $this->close();
    }

    // You should not have to call it unless you need to
    // explicitly free the file descriptor
    public function close() {
        if ($this->fileHandle) {
            fclose($this->fileHandle);
            $this->fileHandle = null;
        }
    }

    public function rewind() {
        if ($this->fileHandle) {
            $this->position = 0;
            rewind($this->fileHandle);
        }

        $this->_readLine();
    }

    public function current() {
        return $this->currentArray;
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        $this->position++;
        $this->_readLine();
    }

    public function valid() {
        return $this->currentArray !== null;
    }

    protected function _readLine() {
        if (!feof($this->fileHandle)) $this->currentLine = trim(utf8_encode(fgets($this->fileHandle)));
        else $this->currentLine = null;
        if ($this->currentLine != '') $this->currentArray = Csv::parseString($this->currentLine, $this->separator);
        else $this->currentArray = null;
    }
}

class CsvWriter {

    protected $fileHandle = null;

    public function __construct($filename, $mode = 'w') {
        if ($mode != 'w' and $mode != 'a') throw new Exception('CsvWriter only accepts "w" and "a" mode.');
        $this->fileHandle = fopen($filename, $mode);
        if (!$this->fileHandle) throw new Exception("Impossible to open file $filename.");
    }

    public function __destruct() {
        $this->close();
    }

    public function addLine(array $values) {
        foreach ($values as $key => $value) {
            $values[$key] = utf8_decode(Csv::escapeString($value));
        }
        $string = implode(',', $values) . "\r\n";
        fwrite($this->fileHandle, $string);
    }

    // You should not have to call it unless you need to flush the
    // data from the buffer to your file explicitly before the
    // end of your script
    public function close() {
        if ($this->fileHandle) {
            fclose($this->fileHandle);
            $this->fileHandle = null;
        }
    }
}