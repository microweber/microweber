<?php

namespace Goodby\CSV\Import\Standard;

use Goodby\CSV\Import\Protocol\LexerInterface;
use Goodby\CSV\Import\Protocol\InterpreterInterface;
use Goodby\CSV\Import\Protocol\Exception\CsvFileNotFoundException;
use Goodby\CSV\Import\Standard\LexerConfig;
use Goodby\CSV\Import\Standard\StreamFilter\ConvertMbstringEncoding;
use SplFileObject;

class Lexer implements LexerInterface
{
    /**
     * @var LexerConfig
     */
    private $config;

    /**
     * Return new Lexer object
     * @param LexerConfig $config
     */
    public function __construct(LexerConfig $config)
    {
        $this->config = $config;
        ConvertMbstringEncoding::register();
    }

    /**
     * {@inherit}
     */
    public function parse($filename, InterpreterInterface $interpreter)
    {
        ini_set('auto_detect_line_endings', true); // For mac's office excel csv

        $delimiter      = $this->config->getDelimiter();
        $enclosure      = $this->config->getEnclosure();
        $escape         = $this->config->getEscape();
        $fromCharset    = $this->config->getFromCharset();
        $toCharset      = $this->config->getToCharset();
        $flags          = $this->config->getFlags();
        $ignoreHeader   = $this->config->getIgnoreHeaderLine();

        if ( $fromCharset === null ) {
            $url = $filename;
        } else {
            $url = ConvertMbstringEncoding::getFilterURL($filename, $fromCharset, $toCharset);
        }

        $csv = new SplFileObject($url);
        $csv->setCsvControl($delimiter, $enclosure, $escape);
        $csv->setFlags($flags);

        $originalLocale = setlocale(LC_ALL, '0'); // Backup current locale
        setlocale(LC_ALL, 'en_US.UTF-8');

        foreach ( $csv as $lineNumber => $line ) {
            if ($ignoreHeader && $lineNumber == 0 || (count($line) === 1 && empty($line[0]))) {
                continue;
            }
            $interpreter->interpret($line);
        }

        parse_str(str_replace(';', '&', $originalLocale), $locale_array);
        setlocale(LC_ALL, $locale_array); // Reset locale
    }
}
