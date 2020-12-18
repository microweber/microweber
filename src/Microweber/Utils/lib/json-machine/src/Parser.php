<?php

namespace JsonMachine;

use JsonMachine\Exception\InvalidArgumentException;
use JsonMachine\Exception\PathNotFoundException;
use JsonMachine\Exception\SyntaxError;

class Parser implements \IteratorAggregate
{
    const SCALAR_CONST = 1;
    const SCALAR_STRING = 2;
    const OBJECT_START = 4;
    const OBJECT_END = 8;
    const ARRAY_START = 16;
    const ARRAY_END = 32;
    const COMMA = 64;
    const COLON = 128;

    const AFTER_ARRAY_START = self::ANY_VALUE | self::ARRAY_END;
    const AFTER_OBJECT_START = self::SCALAR_STRING | self::OBJECT_END;
    const AFTER_ARRAY_VALUE = self::COMMA | self::ARRAY_END;
    const AFTER_OBJECT_VALUE = self::COMMA | self::OBJECT_END;
    const ANY_VALUE = self::OBJECT_START | self::ARRAY_START | self::SCALAR_CONST | self::SCALAR_STRING;

    private $type = [
        'n' => self::SCALAR_CONST,
        't' => self::SCALAR_CONST,
        'f' => self::SCALAR_CONST,
        '-' => self::SCALAR_CONST,
        '0' => self::SCALAR_CONST,
        '1' => self::SCALAR_CONST,
        '2' => self::SCALAR_CONST,
        '3' => self::SCALAR_CONST,
        '4' => self::SCALAR_CONST,
        '5' => self::SCALAR_CONST,
        '6' => self::SCALAR_CONST,
        '7' => self::SCALAR_CONST,
        '8' => self::SCALAR_CONST,
        '9' => self::SCALAR_CONST,
        '"' => self::SCALAR_STRING,
        '{' => self::OBJECT_START,
        '}' => self::OBJECT_END,
        '[' => self::ARRAY_START,
        ']' => self::ARRAY_END,
        ',' => self::COMMA,
        ':' => self::COLON,
    ];

    /** @var Lexer */
    private $lexer;

    /** @var string */
    private $token;

    /** @var string */
    private $jsonPointerPath;

    /** @var string */
    private $jsonPointer;

    /**
     * @param \Traversable $lexer
     * @param string $jsonPointer Follows json pointer RFC https://tools.ietf.org/html/rfc6901
     */
    public function __construct(\Traversable $lexer, $jsonPointer = '')
    {
        if (0 === preg_match('_^(/(([^/~])|(~[01]))*)*$_', $jsonPointer, $matches)) {
            throw new InvalidArgumentException(
                "Given value '$jsonPointer' of \$jsonPointer is not valid JSON Pointer"
            );
        }

        $this->lexer = $lexer;
        $this->jsonPointer = $jsonPointer;
        $this->jsonPointerPath = array_slice(array_map(function ($jsonPointerPart){
            $jsonPointerPart = str_replace(
                '~0', '~', str_replace('~1', '/', $jsonPointerPart)
            );
            return is_numeric($jsonPointerPart) ? (int) $jsonPointerPart : $jsonPointerPart;
        }, explode('/', $jsonPointer)), 1);
    }

    /**
     * @param \Generator $lexer
     * @return \Generator
     */
    public function getIterator()
    {
        // todo Allow to call getIterator only once per instance
        $iteratorLevel = count($this->jsonPointerPath);
        $iteratorStruct = null;
        $currentPath = [];
        $pathFound = false;
        $currentLevel = -1;
        $stack = [$currentLevel => null];
        $jsonBuffer = '';
        $key = null;
        $previousToken = null;
        $inArray = false; // todo remove one of inArray, inObject
        $inObject = false;
        $expectedType = self::OBJECT_START | self::ARRAY_START;

        foreach ($this->lexer as $this->token) {
            $firstChar = $this->token[0];
            if ( ! isset($this->type[$firstChar]) || ! ($this->type[$firstChar] & $expectedType)) {
                $this->error("Unexpected symbol");
            }
            if ($currentPath == $this->jsonPointerPath && ($currentLevel > $iteratorLevel || ($currentLevel === $iteratorLevel && $expectedType & self::ANY_VALUE))) {
                $jsonBuffer .= $this->token;
            }
            if ($currentLevel < $iteratorLevel && $inArray && $expectedType & self::ANY_VALUE) {
                $currentPath[$currentLevel] = isset($currentPath[$currentLevel]) ? (1+$currentPath[$currentLevel]) : 0;
            }
            switch ($firstChar) {
                case '"':
                    if ($inObject && ($previousToken === ',' || $previousToken === '{')) {
                        $expectedType = self::COLON;
                        $previousToken = null;
                        if ($currentLevel === $iteratorLevel) {
                            $key = $this->token;
                            $jsonBuffer = '';
                        } elseif ($currentLevel < $iteratorLevel) {
                            $currentPath[$currentLevel] = json_decode($this->token);
                        }
                        break;
                    } else {
                        goto expectedTypeAfterValue;
                    }
                case ',':
                    if ($inObject) {
                        $expectedType = self::SCALAR_STRING;
                    } else {
                        $expectedType = self::ANY_VALUE;
                    }
                    $previousToken = ',';
                    break;
                case ':':
                    $expectedType = self::ANY_VALUE;
                    break;
                case '{':
                    ++$currentLevel;
                    if ($currentLevel === $iteratorLevel) {
                        $iteratorStruct = '{';
                    }
                    $stack[$currentLevel] = '{';
                    $inArray = !$inObject = true;
                    $expectedType = self::AFTER_OBJECT_START;
                    $previousToken = '{';
                    break;
                case '[':
                    ++$currentLevel;
                    if ($currentLevel === $iteratorLevel) {
                        $iteratorStruct = '[';
                    }
                    $stack[$currentLevel] = '[';
                    $inArray = !$inObject = false;
                    $expectedType = self::AFTER_ARRAY_START;
                    break;
                case '}':
                case ']':
                    --$currentLevel;
                    $inArray = !$inObject = $stack[$currentLevel] === '{';
                default:
                    expectedTypeAfterValue:
                    if ($inArray) {
                        $expectedType = self::AFTER_ARRAY_VALUE;
                    } else {
                        $expectedType = self::AFTER_OBJECT_VALUE;
                    }
            }
            if ( ! $pathFound && $currentPath == $this->jsonPointerPath) {
                $pathFound = true;
            }
            if ($currentLevel === $iteratorLevel && $jsonBuffer !== '') {
                if ($currentPath == $this->jsonPointerPath) {
                    $value = json_decode($jsonBuffer, true);
                    if ($value === null && $jsonBuffer !== 'null') {
                        $this->error(json_last_error_msg());
                    }
                    if ($iteratorStruct === '[') {
                        yield $value;
                    } else {
                        yield json_decode($key) => $value;
                    }
                }
                $jsonBuffer = '';
            }
        }

        if ($this->token === null) {
            $this->error('Cannot iterate empty JSON');
        }

        if ( ! $pathFound) {
            throw new PathNotFoundException("Path '{$this->jsonPointer}' was not found in json stream.");
        }
    }

    /**
     * @return array
     */
    public function getJsonPointerPath()
    {
        return $this->jsonPointerPath;
    }

    /**
     * @return string
     */
    public function getJsonPointer()
    {
        return $this->jsonPointer;
    }

    private function error($msg)
    {
        throw new SyntaxError($msg." '".$this->token."'", $this->lexer->getPosition());
    }
}
