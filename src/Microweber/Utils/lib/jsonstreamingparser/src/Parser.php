<?php

declare(strict_types=1);

namespace JsonStreamingParser;

use JsonStreamingParser\Exception\ParsingException;
use JsonStreamingParser\Listener\ListenerInterface;
use JsonStreamingParser\Listener\PositionAwareInterface;

class Parser
{
    const STATE_START_DOCUMENT = 0;
    const STATE_END_DOCUMENT = 14;
    const STATE_DONE = -1;
    const STATE_IN_ARRAY = 1;
    const STATE_IN_OBJECT = 2;
    const STATE_END_KEY = 3;
    const STATE_AFTER_KEY = 4;
    const STATE_IN_STRING = 5;
    const STATE_START_ESCAPE = 6;
    const STATE_UNICODE = 7;
    const STATE_IN_NUMBER = 8;
    const STATE_IN_TRUE = 9;
    const STATE_IN_FALSE = 10;
    const STATE_IN_NULL = 11;
    const STATE_AFTER_VALUE = 12;
    const STATE_UNICODE_SURROGATE = 13;

    const STACK_OBJECT = 0;
    const STACK_ARRAY = 1;
    const STACK_KEY = 2;
    const STACK_STRING = 3;

    const UTF8_BOM = 1;
    const UTF16_BOM = 2;
    const UTF32_BOM = 3;

    /**
     * @var int
     */
    private $state;

    /**
     * @var int[]
     */
    private $stack = [];

    /**
     * @var resource
     */
    private $stream;

    /**
     * @var ListenerInterface
     */
    private $listener;

    /**
     * @var bool
     */
    private $emitWhitespace;

    /**
     * @var string
     */
    private $buffer = '';

    /**
     * @var int
     */
    private $bufferSize;

    /**
     * @var string[]
     */
    private $unicodeBuffer = [];

    /**
     * @var int
     */
    private $unicodeHighSurrogate = -1;

    /**
     * @var string
     */
    private $unicodeEscapeBuffer = '';

    /**
     * @var string
     */
    private $lineEnding;

    /**
     * @var int
     */
    private $lineNumber;

    /**
     * @var int
     */
    private $charNumber;

    /**
     * @var bool
     */
    private $stopParsing = false;

    /**
     * @var int
     */
    private $utfBom = 0;

    /**
     * @param resource $stream
     */
    public function __construct($stream, ListenerInterface $listener, string $lineEnding = "\n", bool $emitWhitespace = false, int $bufferSize = 8192)
    {
        if (!\is_resource($stream) || 'stream' !== get_resource_type($stream)) {
            throw new \InvalidArgumentException('Invalid stream provided');
        }

        $this->stream = $stream;
        $this->listener = $listener;
        $this->emitWhitespace = $emitWhitespace;
        $this->state = self::STATE_START_DOCUMENT;
        $this->bufferSize = $bufferSize;
        $this->lineEnding = $lineEnding;
    }

    public function parse(): void
    {
        $this->lineNumber = 1;
        $this->charNumber = 1;
        $eof = false;

        while (!feof($this->stream) && !$eof) {
            $pos = ftell($this->stream);
            $line = stream_get_line($this->stream, $this->bufferSize, $this->lineEnding);

            if (false === $line) {
                $line = '';
            }

            $ended = (bool) (ftell($this->stream) - \strlen($line) - $pos);
            // if we're still at the same place after stream_get_line, we're done
            $eof = ftell($this->stream) === $pos;

            $byteLen = \strlen($line);
            for ($i = 0; $i < $byteLen; ++$i) {
                if ($this->listener instanceof PositionAwareInterface) {
                    $this->listener->setFilePosition($this->lineNumber, $this->charNumber);
                }
                $this->consumeChar($line[$i]);
                ++$this->charNumber;

                if ($this->stopParsing) {
                    return;
                }
            }

            if ($ended) {
                ++$this->lineNumber;
                $this->charNumber = 1;
            }
        }
    }

    public function stop(): void
    {
        $this->stopParsing = true;
    }

    private function consumeChar(string $char): void
    {
        // see https://en.wikipedia.org/wiki/Byte_order_mark
        if ($this->charNumber < 5 && 1 === $this->lineNumber && $this->checkAndSkipUtfBom($char)) {
            return;
        }

        // valid whitespace characters in JSON (from RFC4627 for JSON) include:
        // space, horizontal tab, line feed or new line, and carriage return.
        // thanks: http://stackoverflow.com/questions/16042274/definition-of-whitespace-in-json
        if ((' ' === $char || "\t" === $char || "\n" === $char || "\r" === $char) &&
            !(self::STATE_IN_STRING === $this->state ||
                self::STATE_UNICODE === $this->state ||
                self::STATE_START_ESCAPE === $this->state ||
                self::STATE_IN_NUMBER === $this->state)
        ) {
            // we wrap this so that we don't make a ton of unnecessary function calls
            // unless someone really, really cares about whitespace.
            if ($this->emitWhitespace) {
                $this->listener->whitespace($char);
            }

            return;
        }

        switch ($this->state) {
            case self::STATE_IN_STRING:
                if ('"' === $char) {
                    $this->endString();
                } elseif ('\\' === $char) {
                    $this->state = self::STATE_START_ESCAPE;
                } elseif ($char < "\x1f") {
                    $this->throwParseError('Unescaped control character encountered: '.$char);
                } else {
                    $this->buffer .= $char;
                }
                break;

            case self::STATE_IN_ARRAY:
                if (']' === $char) {
                    $this->endArray();
                } else {
                    $this->startValue($char);
                }
                break;

            case self::STATE_IN_OBJECT:
                if ('}' === $char) {
                    $this->endObject();
                } elseif ('"' === $char) {
                    $this->startKey();
                } else {
                    $this->throwParseError('Start of string expected for object key. Instead got: '.$char);
                }
                break;

            case self::STATE_END_KEY:
                if (':' !== $char) {
                    $this->throwParseError("Expected ':' after key.");
                }
                $this->state = self::STATE_AFTER_KEY;
                break;

            case self::STATE_AFTER_KEY:
                $this->startValue($char);
                break;

            case self::STATE_START_ESCAPE:
                $this->processEscapeCharacter($char);
                break;

            case self::STATE_UNICODE:
                $this->processUnicodeCharacter($char);
                break;

            case self::STATE_UNICODE_SURROGATE:
                $this->unicodeEscapeBuffer .= $char;
                if (2 === mb_strlen($this->unicodeEscapeBuffer)) {
                    $this->endUnicodeSurrogateInterstitial();
                }
                break;

            case self::STATE_AFTER_VALUE:
                $within = end($this->stack);
                if (self::STACK_OBJECT === $within) {
                    if ('}' === $char) {
                        $this->endObject();
                    } elseif (',' === $char) {
                        $this->state = self::STATE_IN_OBJECT;
                    } else {
                        $this->throwParseError("Expected ',' or '}' while parsing object. Got: ".$char);
                    }
                } elseif (self::STACK_ARRAY === $within) {
                    if (']' === $char) {
                        $this->endArray();
                    } elseif (',' === $char) {
                        $this->state = self::STATE_IN_ARRAY;
                    } else {
                        $this->throwParseError("Expected ',' or ']' while parsing array. Got: ".$char);
                    }
                } else {
                    $this->throwParseError(
                        'Finished a literal, but unclear what state to move to. Last state: '.$within
                    );
                }
                break;

            case self::STATE_IN_NUMBER:
                if (ctype_digit($char)) {
                    $this->buffer .= $char;
                } elseif ('.' === $char) {
                    if (false !== strpos($this->buffer, '.')) {
                        $this->throwParseError('Cannot have multiple decimal points in a number.');
                    } elseif (false !== stripos($this->buffer, 'e')) {
                        $this->throwParseError('Cannot have a decimal point in an exponent.');
                    }
                    $this->buffer .= $char;
                } elseif ('e' === $char || 'E' === $char) {
                    if (false !== stripos($this->buffer, 'e')) {
                        $this->throwParseError('Cannot have multiple exponents in a number.');
                    }
                    $this->buffer .= $char;
                } elseif ('+' === $char || '-' === $char) {
                    $last = mb_substr($this->buffer, -1);
                    if (!('e' === $last || 'E' === $last)) {
                        $this->throwParseError("Can only have '+' or '-' after the 'e' or 'E' in a number.");
                    }
                    $this->buffer .= $char;
                } else {
                    $this->endNumber();
                    // we have consumed one beyond the end of the number
                    $this->consumeChar($char);
                }
                break;

            case self::STATE_IN_TRUE:
                $this->buffer .= $char;
                if (4 === \strlen($this->buffer)) {
                    $this->endTrue();
                }
                break;

            case self::STATE_IN_FALSE:
                $this->buffer .= $char;
                if (5 === \strlen($this->buffer)) {
                    $this->endFalse();
                }
                break;

            case self::STATE_IN_NULL:
                $this->buffer .= $char;
                if (4 === \strlen($this->buffer)) {
                    $this->endNull();
                }
                break;

            case self::STATE_START_DOCUMENT:
                $this->listener->startDocument();
                if ('[' === $char) {
                    $this->startArray();
                } elseif ('{' === $char) {
                    $this->startObject();
                } else {
                    $this->throwParseError('Document must start with object or array.');
                }
                break;

            case self::STATE_END_DOCUMENT:
                if ('[' !== $char && '{' !== $char) {
                    $this->throwParseError('Expected end of document.');
                }
                $this->state = self::STATE_START_DOCUMENT;
                $this->consumeChar($char);
                break;

            case self::STATE_DONE:
                $this->throwParseError('Expected end of document.');
                break;

            default:
                $this->throwParseError('Internal error. Reached an unknown state: '.$this->state);
                break;
        }
    }

    private function checkAndSkipUtfBom(string $c): bool
    {
        if (1 === $this->charNumber) {
            if ($c === \chr(239)) {
                $this->utfBom = self::UTF8_BOM;
            } elseif ($c === \chr(254) || $c === \chr(255)) {
                // NOTE: could also be UTF32_BOM
                // second character will tell
                $this->utfBom = self::UTF16_BOM;
            } elseif ($c === \chr(0)) {
                $this->utfBom = self::UTF32_BOM;
            }
        }

        if (self::UTF16_BOM === $this->utfBom && 2 === $this->charNumber &&
              $c === \chr(254)) {
            $this->utfBom = self::UTF32_BOM;
        }

        if (self::UTF8_BOM === $this->utfBom && $this->charNumber < 4) {
            // UTF-8 BOM starts with chr(239) . chr(187) . chr(191)
            return true;
        }
        if (self::UTF16_BOM === $this->utfBom && $this->charNumber < 3) {
            return true;
        }
        if (self::UTF32_BOM === $this->utfBom && $this->charNumber < 5) {
            return true;
        }

        return false;
    }

    /**
     * @throws ParsingException
     */
    private function startValue(string $c): void
    {
        if ('[' === $c) {
            $this->startArray();
        } elseif ('{' === $c) {
            $this->startObject();
        } elseif ('"' === $c) {
            $this->startString();
        } elseif (ParserHelper::isDigit($c)) {
            $this->startNumber($c);
        } elseif ('t' === $c) {
            $this->state = self::STATE_IN_TRUE;
            $this->buffer .= $c;
        } elseif ('f' === $c) {
            $this->state = self::STATE_IN_FALSE;
            $this->buffer .= $c;
        } elseif ('n' === $c) {
            $this->state = self::STATE_IN_NULL;
            $this->buffer .= $c;
        } else {
            $this->throwParseError('Unexpected character for value: '.$c);
        }
    }

    private function startArray(): void
    {
        $this->listener->startArray();
        $this->state = self::STATE_IN_ARRAY;
        $this->stack[] = self::STACK_ARRAY;
    }

    private function endArray(): void
    {
        $popped = array_pop($this->stack);
        if (self::STACK_ARRAY !== $popped) {
            $this->throwParseError('Unexpected end of array encountered.');
        }
        $this->listener->endArray();
        $this->state = self::STATE_AFTER_VALUE;

        if (empty($this->stack)) {
            $this->endDocument();
        }
    }

    private function startObject(): void
    {
        $this->listener->startObject();
        $this->state = self::STATE_IN_OBJECT;
        $this->stack[] = self::STACK_OBJECT;
    }

    private function endObject(): void
    {
        $popped = array_pop($this->stack);
        if (self::STACK_OBJECT !== $popped) {
            $this->throwParseError('Unexpected end of object encountered.');
        }
        $this->listener->endObject();
        $this->state = self::STATE_AFTER_VALUE;

        if (empty($this->stack)) {
            $this->endDocument();
        }
    }

    private function startString(): void
    {
        $this->stack[] = self::STACK_STRING;
        $this->state = self::STATE_IN_STRING;
    }

    private function startKey(): void
    {
        $this->stack[] = self::STACK_KEY;
        $this->state = self::STATE_IN_STRING;
    }

    private function endString(): void
    {
        $popped = array_pop($this->stack);
        if (self::STACK_KEY === $popped) {
            $this->listener->key($this->buffer);
            $this->state = self::STATE_END_KEY;
        } elseif (self::STACK_STRING === $popped) {
            $this->listener->value($this->buffer);
            $this->state = self::STATE_AFTER_VALUE;
        } else {
            $this->throwParseError('Unexpected end of string.');
        }
        $this->buffer = '';
    }

    /**
     * @throws ParsingException
     */
    private function processEscapeCharacter(string $c): void
    {
        if ('"' === $c) {
            $this->buffer .= '"';
        } elseif ('\\' === $c) {
            $this->buffer .= '\\';
        } elseif ('/' === $c) {
            $this->buffer .= '/';
        } elseif ('b' === $c) {
            $this->buffer .= "\x08";
        } elseif ('f' === $c) {
            $this->buffer .= "\f";
        } elseif ('n' === $c) {
            $this->buffer .= "\n";
        } elseif ('r' === $c) {
            $this->buffer .= "\r";
        } elseif ('t' === $c) {
            $this->buffer .= "\t";
        } elseif ('u' === $c) {
            $this->state = self::STATE_UNICODE;
        } else {
            $this->throwParseError('Expected escaped character after backslash. Got: '.$c);
        }

        if (self::STATE_UNICODE !== $this->state) {
            $this->state = self::STATE_IN_STRING;
        }
    }

    /**
     * @throws ParsingException
     */
    private function processUnicodeCharacter(string $char): void
    {
        if (!ParserHelper::isHexCharacter($char)) {
            $this->throwParseError(
                'Expected hex character for escaped Unicode character. '
                .'Unicode parsed: '.implode('', $this->unicodeBuffer).' and got: '.$char
            );
        }
        $this->unicodeBuffer[] = $char;
        if (4 === \count($this->unicodeBuffer)) {
            $codepoint = hexdec(implode('', $this->unicodeBuffer));

            if ($codepoint >= 0xD800 && $codepoint < 0xDC00) {
                $this->unicodeHighSurrogate = $codepoint;
                $this->unicodeBuffer = [];
                $this->state = self::STATE_UNICODE_SURROGATE;
            } elseif ($codepoint >= 0xDC00 && $codepoint <= 0xDFFF) {
                if (-1 === $this->unicodeHighSurrogate) {
                    $this->throwParseError('Missing high surrogate for Unicode low surrogate.');
                }
                $combinedCodepoint = (($this->unicodeHighSurrogate - 0xD800) * 0x400) + ($codepoint - 0xDC00) + 0x10000;

                $this->endUnicodeCharacter($combinedCodepoint);
            } else {
                if (-1 !== $this->unicodeHighSurrogate) {
                    $this->throwParseError('Invalid low surrogate following Unicode high surrogate.');
                } else {
                    $this->endUnicodeCharacter($codepoint);
                }
            }
        }
    }

    private function endUnicodeSurrogateInterstitial(): void
    {
        $unicodeEscape = $this->unicodeEscapeBuffer;
        if ('\\u' !== $unicodeEscape) {
            $this->throwParseError("Expected '\\u' following a Unicode high surrogate. Got: ".$unicodeEscape);
        }
        $this->unicodeEscapeBuffer = '';
        $this->state = self::STATE_UNICODE;
    }

    private function endUnicodeCharacter(int $codepoint): void
    {
        $this->buffer .= ParserHelper::convertCodepointToCharacter($codepoint);
        $this->unicodeBuffer = [];
        $this->unicodeHighSurrogate = -1;
        $this->state = self::STATE_IN_STRING;
    }

    private function startNumber(string $c): void
    {
        $this->state = self::STATE_IN_NUMBER;
        $this->buffer .= $c;
    }

    private function endNumber(): void
    {
        $this->listener->value(ParserHelper::convertToNumber($this->buffer));
        $this->buffer = '';
        $this->state = self::STATE_AFTER_VALUE;
    }

    private function endTrue(): void
    {
        $this->endSpecialValue(true, 'true');
    }

    private function endFalse(): void
    {
        $this->endSpecialValue(false, 'false');
    }

    private function endNull(): void
    {
        $this->endSpecialValue(null, 'null');
    }

    private function endSpecialValue($value, string $stringValue): void
    {
        if ($this->buffer === $stringValue) {
            $this->listener->value($value);
        } else {
            $this->throwParseError("Expected 'null'. Got: ".$this->buffer);
        }
        $this->buffer = '';
        $this->state = self::STATE_AFTER_VALUE;
    }

    private function endDocument(): void
    {
        $this->listener->endDocument();
        $this->state = self::STATE_END_DOCUMENT;
    }

    /**
     * @throws ParsingException
     */
    private function throwParseError(string $message): void
    {
        throw new ParsingException($this->lineNumber, $this->charNumber, $message);
    }
}
