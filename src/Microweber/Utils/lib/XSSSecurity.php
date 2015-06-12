<?php

/*
 * This file is part of Laravel Security.
 *
 * (c) Graham Campbell <graham@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Downloaded from here https://raw.githubusercontent.com/GrahamCampbell/Laravel-Security/master/src/Security.php
 */

//namespace GrahamCampbell\Security;
namespace Microweber\Utils\lib;
/**
 * This is the security class.
 *
 * Some code in this class it taken from CodeIgniter 3.
 * See the original here: http://bit.ly/1oQnpjn.
 *
 * @author Andrey Andreev <narf@bofh.bg>
 * @author Derek Jones <derek.jones@ellislab.com>
 * @author Graham Campbell <graham@cachethq.io>
 */
class XSSSecurity
{
    /**
     * A random hash for protecting urls.
     *
     * @var string
     */
    protected $xssHash;

    /**
     * The evil attributes.
     *
     * @var string[]
     */
    protected $evil;

    /**
     * Create a new security instance.
     *
     * @param string[]|null $evil
     *
     * @return void
     */
    public function __construct(array $evil = null)
    {
        $this->evil = $evil ?: ['(?<!\w)on\w*', 'style', 'xmlns', 'formaction', 'form', 'xlink:href', 'FSCommand', 'seekSegmentTime'];
    }

    /**
     * XSS clean.
     *
     * @param string|string[] $str
     *
     * @return string
     */
    public function clean($str)
    {
        if (is_array($str)) {
            while (list($key) = each($str)) {
                $str[$key] = $this->clean($str[$key]);
            }

            return $str;
        }

        $i = 0;
        do {
            $i++;
            $processed = $this->process($str);
        } while ($i < 3 && $processed !== $str);

        return $processed;
    }

    /**
     * Process a string for cleaning.
     *
     * @param string $str
     *
     * @return string
     */
    protected function process($str)
    {
        $str = $this->removeInvisibleCharacters($str);

        do {
            $str = rawurldecode($str);
        } while (preg_match('/%[0-9a-f]{2,}/i', $str));

        $str = preg_replace_callback("/[^a-z0-9>]+[a-z0-9]+=([\'\"]).*?\\1/si", [$this, 'convertAttribute'], $str);
        $str = preg_replace_callback('/<\w+.*?(?=>|<|$)/si', [$this, 'decodeEntity'], $str);

        $str = $this->removeInvisibleCharacters($str);

        $str = str_replace("\t", ' ', $str);

        $str = $this->doNeverAllowed($str);

        $str = str_replace(['<?', '?'.'>'], ['&lt;?', '?&gt;'], $str);

        $words = [
            'javascript', 'expression', 'vbscript', 'jscript', 'wscript',
            'vbs', 'script', 'base64', 'applet', 'alert', 'document',
            'write', 'cookie', 'window', 'confirm', 'prompt',
        ];

        foreach ($words as $word) {
            $word = implode('\s*', str_split($word)).'\s*';
            $str = preg_replace_callback(
                '#('.substr($word, 0, -3).')(\W)#is',
                [$this, 'compactExplodedWords'],
                $str
            );
        }

        do {
            $original = $str;

            if (preg_match('/<a/i', $str)) {
                $str = preg_replace_callback(
                    '#<a[^a-z0-9>]+([^>]*?)(?:>|$)#si',
                    [$this, 'jsLinkRemoval'],
                    $str
                );
            }

            if (preg_match('/<img/i', $str)) {
                $str = preg_replace_callback(
                    '#<img[^a-z0-9]+([^>]*?)(?:\s?/?>|$)#si',
                    [$this, 'jsImgRemoval'],
                    $str
                );
            }

            if (preg_match('/script|xss/i', $str)) {
                $str = preg_replace('#</*(?:script|xss).*?>#si', '[removed]', $str);
            }
        } while ($original !== $str);

        unset($original);

        $str = $this->removeEvilAttributes($str);

        $naughty = 'alert|prompt|confirm|applet|audio|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|button|select|isindex|layer|link|meta|keygen|object|plaintext|style|script|textarea|title|math|video|svg|xml|xss';
        $str = preg_replace_callback(
            '#<(/*\s*)('.$naughty.')([^><]*)([><]*)#is',
            [$this, 'sanitizeNaughtyHtml'],
            $str
        );

        $str = preg_replace(
            '#(alert|prompt|confirm|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si',
            '\\1\\2&#40;\\3&#41;',
            $str
        );

        return $this->doNeverAllowed($str);
    }

    /**
     * Generates the XSS hash if needed and returns it.
     *
     * @return string
     */
    protected function xssHash()
    {
        if (!$this->xssHash) {
            $this->xssHash = str_random(40);
        }

        return $this->xssHash;
    }

    /**
     * Removes invisible characters.
     *
     * @param string $str
     * @param bool   $urlEncoded
     *
     * @return string
     */
    protected function removeInvisibleCharacters($str, $urlEncoded = true)
    {
        $nonDisplayables = [];

        if ($urlEncoded) {
            $nonDisplayables[] = '/%0[0-8bcef]/';
            $nonDisplayables[] = '/%1[0-9a-f]/';
        }

        $nonDisplayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';

        do {
            $str = preg_replace($nonDisplayables, '', $str, -1, $count);
        } while ($count);

        return $str;
    }

    /**
     * HTML entities decode.
     *
     * @param string $str
     *
     * @return string
     */
    protected function entityDecode($str)
    {
        static $entities;

        if (strpos($str, '&') === false) {
            return $str;
        }

        $flags = ENT_COMPAT | ENT_HTML5;

        do {
            $str_compare = $str;

            if (preg_match_all('/&[a-z]{2,}(?![a-z;])/i', $str, $matches)) {
                if (!isset($entities)) {
                    $entities = array_map('strtolower', get_html_translation_table(HTML_ENTITIES, $flags));
                }

                $replace = [];
                $matches = array_unique(array_map('strtolower', $matches[0]));
                for ($i = 0, $c = count($matches); $i < $c; $i++) {
                    if (($char = array_search(array_get($matches, $i).';', $entities, true)) !== false) {
                        $replace[array_get($matches, $i)] = $char;
                    }
                }

                $str = str_ireplace(array_keys($replace), array_values($replace), $str);
            }

            $str = html_entity_decode(preg_replace('/(&#(?:x0*[0-9a-f]{2,5}(?![0-9a-f;])|(?:0*\d{2,4}(?![0-9;]))))/iS', '$1;', $str), $flags);
        } while ($str_compare !== $str);

        return $str;
    }

    /**
     * Compact exploded words.
     *
     * @param array $matches
     *
     * @return string
     */
    protected function compactExplodedWords($matches)
    {
        return preg_replace('/\s+/s', '', $matches[1]).$matches[2];
    }

    /**
     * Remove evil html attributes.
     *
     * @param string $str
     *
     * @return string
     */
    protected function removeEvilAttributes($str)
    {
        do {
            $count = $tempCount = 0;

            // replace occurrences of illegal attribute strings with quotes (042 and 047 are octal quotes)
            $str = preg_replace('/(<[^>]+)(?<!\w)('.implode('|', $this->evil).')\s*=\s*(\042|\047)([^\\2]*?)(\\2)/is', '$1[removed]', $str, -1, $tempCount);
            $count += $tempCount;

            // find occurrences of illegal attribute strings without quotes
            $str = preg_replace('/(<[^>]+)(?<!\w)('.implode('|', $this->evil).')\s*=\s*([^\s>]*)/is', '$1[removed]', $str, -1, $tempCount);
            $count += $tempCount;
        } while ($count);

        return $str;
    }

    /**
     * Sanitize naughty html.
     *
     * @param array $matches
     *
     * @return string
     */
    protected function sanitizeNaughtyHtml($matches)
    {
        return '&lt;'.$matches[1].$matches[2].$matches[3]
            .str_replace(['>', '<'], ['&gt;', '&lt;'], $matches[4]);
    }

    /**
     * JS link removal.
     *
     * @param array $match
     *
     * @return string
     */
    protected function jsLinkRemoval($match)
    {
        return str_replace(
            $match[1],
            preg_replace(
                '#href=.*?(?:(?:alert|prompt|confirm)(?:\(|&\#40;)|javascript:|livescript:|mocha:|charset=|window\.|document\.|\.cookie|<script|<xss|data\s*:)#si',
                '',
                $this->filterAttributes(str_replace(['<', '>'], '', $match[1]))
            ),
            $match[0]
        );
    }

    /**
     * JS image removal.
     *
     * @param array $match
     *
     * @return string
     */
    protected function jsImgRemoval($match)
    {
        return str_replace(
            $match[1],
            preg_replace(
                '#src=.*?(?:(?:alert|prompt|confirm)(?:\(|&\#40;)|javascript:|livescript:|mocha:|charset=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si',
                '',
                $this->filterAttributes(str_replace(['<', '>'], '', $match[1]))
            ),
            $match[0]
        );
    }

    /**
     * Attribute conversion.
     *
     * @param array $match
     *
     * @return string
     */
    protected function convertAttribute($match)
    {
        return str_replace(['>', '<', '\\'], ['&gt;', '&lt;', '\\\\'], $match[0]);
    }

    /**
     * Attribute filtering.
     *
     * @param string $str
     *
     * @return string
     */
    protected function filterAttributes($str)
    {
        $out = '';

        if (preg_match_all('#\s*[a-z\-]+\s*=\s*(\042|\047)([^\\1]*?)\\1#is', $str, $matches)) {
            foreach ($matches[0] as $match) {
                $out .= preg_replace('#/\*.*?\*/#s', '', $match);
            }
        }

        return $out;
    }

    /**
     * HTML entity decode callback.
     *
     * @param array $match
     *
     * @return string
     */
    protected function decodeEntity($match)
    {
        $hash = $this->xssHash();

        $match = preg_replace('|\&([a-z\_0-9\-]+)\=([a-z\_0-9\-/]+)|i', $hash.'\\1=\\2', $match[0]);

        return str_replace($hash, '&', $this->entityDecode($match));
    }

    /**
     * Do never allowed.
     *
     * @param string $str
     *
     * @return string
     */
    protected function doNeverAllowed($str)
    {
        $never = [
            'document.cookie'   => '[removed]',
            'document.write'    => '[removed]',
            '.parentNode'       => '[removed]',
            '.innerHTML'        => '[removed]',
            '-moz-binding'      => '[removed]',
            '<!--'              => '&lt;!--',
            '-->'               => '--&gt;',
            '<![CDATA['         => '&lt;![CDATA[',
            '<comment>'         => '&lt;comment&gt;',
        ];

        $str = str_replace(array_keys($never), $never, $str);

        $regex = [
            'javascript\s*:',
            '(document|(document\.)?window)\.(location|on\w*)',
            'expression\s*(\(|&\#40;)',
            'vbscript\s*:',
            'wscript\s*:',
            'jscript\s*:',
            'vbs\s*:',
            'Redirect\s+30\d',
            "([\"'])?data\s*:[^\\1]*?base64[^\\1]*?,[^\\1]*?\\1?",
        ];

        foreach ($regex as $val) {
            $str = preg_replace('#'.$val.'#is', '[removed]', $str);
        }

        return $str;
    }
}
