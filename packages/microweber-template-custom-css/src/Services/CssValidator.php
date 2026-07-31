<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Services;

use MicroweberPackages\TemplateCustomCss\Exceptions\InvalidCssException;
use Sabberworm\CSS\Parsing\SourceException;
use Sabberworm\CSS\Parsing\UnexpectedEOFException;
use Sabberworm\CSS\Parsing\UnexpectedTokenException;
use Sabberworm\CSS\Settings;
use Sabberworm\CSS\Parser as SabberwormParser;

/**
 * Validates CSS using sabberworm/php-css-parser so broken CSS cannot be saved.
 */
class CssValidator
{
    public function __construct(
        protected bool $allowEmpty = true,
    ) {
    }

    /**
     * @return array{valid: bool, errors: list<string>}
     */
    public function validate(string $css): array
    {
        $trimmed = trim($css);

        if ($trimmed === '') {
            return [
                'valid' => $this->allowEmpty,
                'errors' => $this->allowEmpty ? [] : ['CSS content is empty.'],
            ];
        }

        try {
            // Strict mode rejects truncated declarations / unbalanced input that
            // lenient parsing would silently "repair".
            $settings = Settings::create()
                ->withMultibyteSupport(true)
                ->beStrict();
            $parser = new SabberwormParser($css, $settings);
            $parser->parse();

            // Extra structural checks (brace balance, unclosed comments/strings)
            $structural = $this->structuralChecks($css);
            if ($structural !== []) {
                return ['valid' => false, 'errors' => $structural];
            }

            return ['valid' => true, 'errors' => []];
        } catch (UnexpectedTokenException|UnexpectedEOFException|SourceException|\Exception $e) {
            return [
                'valid' => false,
                'errors' => [$this->formatException($e)],
            ];
        }
    }

    /**
     * @throws InvalidCssException
     */
    public function assertValid(string $css): void
    {
        $result = $this->validate($css);
        if (!$result['valid']) {
            throw new InvalidCssException(
                'Invalid CSS: ' . implode('; ', $result['errors']),
                $result['errors'],
            );
        }
    }

    public function isValid(string $css): bool
    {
        return $this->validate($css)['valid'];
    }

    protected function formatException(\Throwable $e): string
    {
        $message = $e->getMessage();
        if ($message === '') {
            return 'CSS parse error';
        }

        return $message;
    }

    /**
     * Lightweight structural guards that complement the parser.
     *
     * @return list<string>
     */
    protected function structuralChecks(string $css): array
    {
        $errors = [];

        if (substr_count($css, '/*') > substr_count($css, '*/')) {
            $errors[] = 'Unclosed CSS comment.';
        }

        $braceDepth = 0;
        $inString = null;
        $len = strlen($css);
        for ($i = 0; $i < $len; $i++) {
            $ch = $css[$i];
            $prev = $i > 0 ? $css[$i - 1] : '';

            if ($inString !== null) {
                if ($ch === $inString && $prev !== '\\') {
                    $inString = null;
                }
                continue;
            }

            if ($ch === '"' || $ch === "'") {
                $inString = $ch;
                continue;
            }

            // Skip // line comments are not standard CSS but ignore braces in block comments
            if ($ch === '/' && ($i + 1) < $len && $css[$i + 1] === '*') {
                $end = strpos($css, '*/', $i + 2);
                if ($end === false) {
                    break;
                }
                $i = $end + 1;
                continue;
            }

            if ($ch === '{') {
                $braceDepth++;
            } elseif ($ch === '}') {
                $braceDepth--;
                if ($braceDepth < 0) {
                    $errors[] = 'Unexpected closing brace.';
                    break;
                }
            }
        }

        if ($inString !== null) {
            $errors[] = 'Unclosed string literal in CSS.';
        }
        if ($braceDepth > 0) {
            $errors[] = 'Unclosed CSS block (missing closing brace).';
        }

        return $errors;
    }
}
