<?php

declare(strict_types=1);

/*
 * JavaScript minifier based on JShrink (https://github.com/tedious/JShrink)
 * by Robert Hafner <tedivm@tedivm.com>, BSD-3-Clause.
 *
 * Fixes applied for Microweber package reuse:
 * - Correct end-of-input detection (index < len) — fixes infinite-loop bug
 * - No premature newline-to-space collapse
 * - Typed properties and return types for static analysis
 */

namespace MicroweberPackages\Minifier\Minifiers;

/**
 * Minifies JavaScript without altering functionality.
 *
 * Usage: JsMinifier::minify($js);
 * Usage: JsMinifier::minify($js, ['flaggedComments' => false]);
 */
class JsMinifier
{
    protected string $input = '';

    protected int $len = 0;

    protected int $index = 0;

    protected string|false $a = '';

    protected string|false $b = '';

    protected string|false|null $c = null;

    protected string $lastChar = '';

    protected string $output = '';

    /** @var array<string, mixed> */
    protected array $options = [];

    /** @var array<string, true> */
    protected array $stringDelimiters = ['\'' => true, '"' => true, '`' => true];

    /** @var array{flaggedComments: bool} */
    protected static array $defaultOptions = ['flaggedComments' => true];

    /** @var list<string> */
    protected static array $keywords = ['delete', 'do', 'for', 'in', 'instanceof', 'return', 'typeof', 'yield'];

    protected int $maxKeywordLen = 0;

    /** @var array<string, string> */
    protected array $locks = [];

    /** @var array<string, true> */
    protected array $noNewLineCharacters = [
        '(' => true,
        '-' => true,
        '+' => true,
        '[' => true,
        '#' => true,
        '@' => true,
    ];

    /**
     * @param  array<string, mixed>  $options
     *
     * @throws \Exception
     */
    public static function minify(string $js, array $options = []): string
    {
        $jshrink = null;

        try {
            $jshrink = new self();
            $js = $jshrink->lock($js);
            $js = ltrim($jshrink->minifyToString($js, $options));
            $js = $jshrink->unlock($js);

            return $js;
        } catch (\Exception $e) {
            if ($jshrink !== null) {
                $jshrink->clean();
            }

            throw $e;
        }
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function minifyToString(string $js, array $options): string
    {
        $this->initialize($js, $options);
        $this->loop();
        $this->clean();

        return $this->output;
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function initialize(string $js, array $options): void
    {
        $this->options = array_merge(static::$defaultOptions, $options);
        $this->input = $js;

        // Trailing newline prevents unclosed-comment errors at EOF
        $this->input .= PHP_EOL;
        $this->len = strlen($this->input);

        $this->a = "\n";
        $this->b = "\n";
        $this->lastChar = "\n";
        $this->output = '';
        $this->index = 0;
        $this->c = null;
        $this->locks = [];

        $lengths = array_map('strlen', static::$keywords);
        $this->maxKeywordLen = $lengths !== [] ? max($lengths) : 0;
    }

    protected function echoChar(string $char): void
    {
        $this->output .= $char;
        $this->lastChar = $char !== '' ? $char[strlen($char) - 1] : $this->lastChar;
    }

    protected function loop(): void
    {
        while ($this->a !== false && $this->a !== '') {
            $a = $this->a;

            switch ($a) {
                case "\r":
                case "\n":
                    if ($this->b !== false && isset($this->noNewLineCharacters[$this->b])) {
                        $this->echoChar($a);
                        $this->saveString();
                        break;
                    }

                    if ($this->b === ' ') {
                        break;
                    }
                    // treat newline like a space
                    // no break

                case ' ':
                    if (static::isAlphaNumeric($this->b)) {
                        $this->echoChar($a);
                    }

                    $this->saveString();
                    break;

                default:
                    switch ($this->b) {
                        case "\r":
                        case "\n":
                            if (strpos('}])+-"\'', $a) !== false) {
                                $this->echoChar($a);
                                $this->saveString();
                                break;
                            }

                            if (static::isAlphaNumeric($a)) {
                                $this->echoChar($a);
                                $this->saveString();
                            }
                            break;

                        case ' ':
                            if (!static::isAlphaNumeric($a)) {
                                break;
                            }
                            // no break

                        default:
                            if ($a === '/' && ($this->b === '\'' || $this->b === '"')) {
                                $this->saveRegex();
                                continue 3;
                            }

                            $this->echoChar($a);
                            $this->saveString();
                            break;
                    }
            }

            $this->b = $this->getReal();

            if ($this->b === '/') {
                $validTokens = "(,=:[!&|?\n";
                $lastToken = $this->a;
                if ($lastToken === ' ') {
                    $lastToken = $this->lastChar;
                }

                if (is_string($lastToken) && strpos($validTokens, $lastToken) !== false) {
                    $this->saveRegex();
                } elseif ($this->endsInKeyword()) {
                    $this->saveRegex();
                }
            }
        }
    }

    protected function clean(): void
    {
        $this->input = '';
        $this->len = 0;
        $this->index = 0;
        $this->a = '';
        $this->b = '';
        $this->c = null;
        $this->options = [];
    }

    /**
     * @return string|false
     */
    protected function getChar(): string|false
    {
        if ($this->c !== null) {
            $char = $this->c;
            $this->c = null;
            if ($char === false) {
                return false;
            }
        } else {
            if ($this->index >= $this->len) {
                return false;
            }
            $char = $this->input[$this->index];
            $this->index++;
        }

        // Convert all line endings to unix standard
        if ($char === "\r") {
            $char = "\n";
        }

        // Normalize control whitespace (except newline) to a single space
        if ($char !== "\n" && $char < "\x20") {
            return ' ';
        }

        return $char;
    }

    /**
     * @return string|false
     */
    protected function peek(): string|false
    {
        if ($this->index >= $this->len) {
            return false;
        }

        $char = $this->input[$this->index];

        if ($char === "\r") {
            $char = "\n";
        }

        if ($char !== "\n" && $char < "\x20") {
            return ' ';
        }

        return $char;
    }

    /**
     * @return string|false
     *
     * @throws \RuntimeException
     */
    protected function getReal(): string|false
    {
        $startIndex = $this->index;
        $char = $this->getChar();

        if ($char !== '/') {
            return $char;
        }

        $this->c = $this->getChar();

        if ($this->c === '/') {
            $this->processOneLineComments($startIndex);

            return $this->getReal();
        }

        if ($this->c === '*') {
            $this->processMultiLineComments($startIndex);

            return $this->getReal();
        }

        return $char;
    }

    protected function processOneLineComments(int $startIndex): void
    {
        $thirdCommentString = $this->index < $this->len ? $this->input[$this->index] : false;

        $this->getNext("\n");
        $this->c = null;

        if ($thirdCommentString === '@') {
            $endPoint = $this->index - $startIndex;
            $this->c = "\n" . substr($this->input, $startIndex, $endPoint);
        }
    }

    /**
     * @throws \RuntimeException
     */
    protected function processMultiLineComments(int $startIndex): void
    {
        $this->getChar(); // current C
        $thirdCommentString = $this->getChar();

        // Completely empty comment /**/
        if ($thirdCommentString === '*') {
            $peekChar = $this->peek();
            if ($peekChar === '/') {
                $this->index++;

                return;
            }
        }

        $char = false;

        if ($this->getNext('*/') !== false) {
            $this->getChar(); // *
            $this->getChar(); // /
            $char = $this->getChar();

            $flagged = !empty($this->options['flaggedComments']) && $thirdCommentString === '!';
            if ($flagged || $thirdCommentString === '@') {
                if ($startIndex > 0) {
                    if (is_string($this->a)) {
                        $this->echoChar($this->a);
                    }
                    $this->a = ' ';

                    if (($this->input[$startIndex - 1] ?? '') === "\n") {
                        $this->echoChar("\n");
                    }
                }

                $endPoint = ($this->index - 1) - $startIndex;
                $this->echoChar(substr($this->input, $startIndex, $endPoint));
                $this->c = $char;

                return;
            }
        }

        if ($char === false) {
            throw new \RuntimeException('Unclosed multiline comment at position: ' . ($this->index - 2));
        }

        $this->c = $char;
    }

    /**
     * @return string|false
     */
    protected function getNext(string $string): string|false
    {
        $pos = strpos($this->input, $string, $this->index);

        if ($pos === false) {
            return false;
        }

        $this->index = $pos;

        return $this->index < $this->len ? $this->input[$this->index] : false;
    }

    /**
     * @throws \RuntimeException
     */
    protected function saveString(): void
    {
        $startpos = $this->index;
        $this->a = $this->b;

        if (!is_string($this->a) || !isset($this->stringDelimiters[$this->a])) {
            return;
        }

        $stringType = $this->a;
        $this->echoChar($this->a);

        while (($this->a = $this->getChar()) !== false) {
            $char = $this->a;

            switch ($char) {
                case $stringType:
                    break 2;

                case "\n":
                    if ($stringType === '`') {
                        $this->echoChar($char);
                    } else {
                        throw new \RuntimeException('Unclosed string at position: ' . $startpos);
                    }
                    break;

                case '\\':
                    $escaped = $this->getChar();

                    // Discard escaped newlines; keep all other escapes.
                    // EOF (false) is also a stop condition for the escape sequence.
                    if ($escaped === "\n" || $escaped === false) { // @phpstan-ignore identical.alwaysFalse (getChar may return false at EOF)
                        break;
                    }

                    $this->b = $escaped;
                    $this->echoChar($char . $escaped);
                    break;

                default:
                    $this->echoChar($char);
            }
        }
    }

    /**
     * @throws \RuntimeException
     */
    protected function saveRegex(): void
    {
        if ($this->a !== ' ' && is_string($this->a)) {
            $this->echoChar($this->a);
        }

        if (is_string($this->b)) {
            $this->echoChar($this->b);
        }

        $characterClass = false;
        $characterClassIndex = null;

        while (($this->a = $this->getChar()) !== false) {
            if ($this->a === '/' && !$characterClass) {
                break;
            }

            if ($this->a === '[') {
                $characterClass = true;
                $characterClassIndex = $this->index;
            } elseif ($this->a === ']') {
                $characterClass = false;
            }

            if ($this->a === '\\') {
                $this->echoChar($this->a);
                $this->a = $this->getChar();
            }

            if ($this->a === "\n") {
                if ($characterClass) {
                    throw new \RuntimeException('Unclosed character class at position: ' . (string) $characterClassIndex);
                }

                throw new \RuntimeException('Unclosed regex pattern at position: ' . $this->index);
            }

            if (is_string($this->a)) {
                $this->echoChar($this->a);
            }
        }

        $this->b = $this->getReal();
    }

    /**
     * @param  string|false|null  $char
     */
    protected static function isAlphaNumeric(string|false|null $char): bool
    {
        if (!is_string($char) || $char === '') {
            return false;
        }

        return preg_match('/^[\w\$\pL]$/u', $char) === 1 || $char === '/';
    }

    protected function endsInKeyword(): bool
    {
        $testOutput = substr($this->output . (is_string($this->a) ? $this->a : ''), -1 * ($this->maxKeywordLen + 10));

        foreach (static::$keywords as $keyword) {
            if (preg_match('/[^\w]' . $keyword . '[ ]?$/i', $testOutput) === 1) {
                return true;
            }
        }

        return false;
    }

    protected function lock(string $js): string
    {
        $lock = '"LOCK---' . crc32((string) time()) . '"';

        $matches = [];
        preg_match('/([+-])(\s+)([+-])/S', $js, $matches);
        if ($matches === []) {
            return $js;
        }

        $this->locks[$lock] = $matches[2];

        $result = preg_replace('/([+-])\s+([+-])/S', '$1' . $lock . '$2', $js);

        return is_string($result) ? $result : $js;
    }

    protected function unlock(string $js): string
    {
        if ($this->locks === []) {
            return $js;
        }

        foreach ($this->locks as $lock => $replacement) {
            $js = str_replace($lock, $replacement, $js);
        }

        return $js;
    }
}
