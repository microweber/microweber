<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/classes/class.geshisinglecharcontext.php
 *   Author: Nigel McNie
 *   E-mail: nigel@geshi.org
 * </pre>
 *
 * For information on how to use GeSHi, please consult the documentation
 * found in the docs/ directory, or online at http://geshi.org/docs/
 *
 * This program is part of GeSHi.
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301 USA
 *
 * @package    geshi
 * @subpackage core
 * @author     Nigel McNie <nigel@geshi.org>;
 *             http://clc-wiki.net/wiki/User:Netocrat
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2004 - 2006 Nigel McNie
 * @version    1.1.2alpha3
 *
 */

/**
 * The GeSHiSingleCharContext class. This class extends GeSHiContext to handle
 * "single character" strings - strings that are only one character long, like
 * in java.
 *
 * Escape sequences need not be limited to one character and may be REGEX-
 * specified, to allow for situations such as C's octal and hexadecimal escapes,
 * e.g. '\xFF'.  Likewise for the start and end delimiter, and the escape
 * "character".  This is handy for situations such as C's widestring
 * characters, which are prefixed by an L.
 *
 * @package    geshi
 * @subpackage core
 * @author     Nigel McNie <nigel@geshi.org>; Netocrat
 * @since      1.1.1
 * @version    1.1.2alpha3
 * @see        GeSHiContext
 */
class GeSHiSingleCharContext extends GeSHiContext
{

    // {{{ properties

    /**#@-
     * @access private
     */
    /** The parsed data when getContextStartData() is successful. */
    var $_characterLen;
    var $_endDelimiterLen;
    var $_isEscapeSeq;

    var $_disallowEmpty;

    /** Characters that start an escape sequence... */
    var $_escapeCharacters;
    /** ...and the valid escape sequences that can follow. */
    var $_charsToEscape;

    /**#@-*/

    // }}}
    // {{{ setEscapeCharacters()
    /**
     * Specifies each "character" that should be interpreted as the start of an
     * escape sequence when it occurs immediately subsequent to a start
     * delimiter.  Each "character" may be greater than one actual character in
     * length, and may optionally be specified by a REGEX-string - look-behind
     * assertions on such regexes are not supported.
     * @param Mixed Array of strings or single string.
     */
    function setEscapeCharacters ($chars)
    {
        $this->_escapeCharacters = (array) $chars;
    }

    // }}}
    // {{{ setCharactersToEscape()
    /**
     * Specifies all escape sequences that are valid following any of the
     * escape characters.  Each escape sequence may be greater than one
     * character in length and may be specified by a REGEX-string - look-behind
     * assertions on such regexes are not supported.
     * @param Mixed Array of strings or single string.
     */
    function setCharactersToEscape ($chars)
    {
        static $re_starter_c = 'REGEX';
        static $re_starter_len_c = 5/*strlen($re_starter_c)*/;
        $this->_charsToEscape = array();
        /* Save a little time and processing by anchoring all regexes now,
         * rather than each time geshi_whichsubstr() is called.
         */
        foreach ((array)$chars as $escSeq) {
            if (strncmp($escSeq, $re_starter_c, $re_starter_len_c) == 0) {
                $re = substr($escSeq, $re_starter_len_c);
                $re = geshi_anchor_re($re);
                $this->_charsToEscape[] = $re_starter_c.$re;
            } else $this->_charsToEscape[] = $escSeq;
        }
    }

    // }}}
    // {{{ setDisallowEmpty()
    /**
     * Call this to specify whether to disallow empty characters - e.g. in C, ''
     * is invalid.  By default empty characters are allowed.  The default
     * parameter value of this function is true so e.g. in C's case this can be
     * called simply as $context->setDisallowEmptyChars().
     * @param boolean $value Defaults to true.
     */
    function setDisallowEmptyChars ($value = true)
    {
        $this->_disallowEmpty = $value;
    }

    // }}}
    // {{{ getContextStartData()
    /**
     * GetContextStartData
     *
     * Overrides the parent method to check whether this context should even
     * start.  Checks for a complete character including start and end
     * delimiters and valid contained character, which might be an escape
     * sequence.  Stores all data found so that it may be used by
     * _getContextEndData() and _addParseData(), to avoid reparsing.
     *
     * @param string $code
     * @param string $start_of_context
     */
    function getContextStartData ($code, $start_of_context)
    {
        geshi_dbg('GeSHiSingleCharContext::getContextStartData(' .
          $this->_contextName . ', ' . $start_of_context . ')');

        $offset = 0;
        $data = null;
        while (true) {
            /* For retries, strip to just past the last failed start. */
            if ($data != null) {
                $code = substr($code, $data['pos'] + 1);
                $offset += $data['pos'] + 1;
            }

            $data = parent::getContextStartData($code, $start_of_context);

            /* First, if no match then bail */
            if (-1 === $data['pos']) break;

            /* Check for empty character */
            $end_delim = geshi_whichsubstr($code, $this->_contextDelimiters[
              $data['key']][1], $data['pos'] + $data['len'],
              GESHI_WHICHSS_MAXIMAL|GESHI_WHICHSS_TRYREGEX);
            if ($end_delim !== null) {
                if (!$this->_disallowEmpty) {
                    $data['pos'] += $offset;
                    $this->_characterLen = 0;
                    $this->_endDelimiterLen = strlen($end_delim);
                    $this->_isEscapeSeq = false;
                    break;
                } else {
                    /* Support a (hypothetical) syntax where empty characters
                     * are not permitted but where the end delimiter doubles as
                     * an escape character. */
                    $empty = true;
                }
            } else $empty = false;

            /* Check for the start of an escape sequence */
            $esc_start = geshi_whichsubstr($code, $this->_escapeCharacters,
              $data['pos'] + $data['len'], GESHI_WHICHSS_MAXIMAL|
              GESHI_WHICHSS_TRYREGEX);
            $esc_len = strlen($esc_start);
            if ($esc_start !== null) {
                /* Check for a valid full escape sequence; allow regexes
                 * that match sequences of length > 1.  Match the most
                 * inclusive char/regex. */
                $start = $data['pos'] + $data['len'] + $esc_len;
                $esc_seq = geshi_whichsubstr($code, $this->_charsToEscape,
                  $start, GESHI_WHICHSS_MAXIMAL|GESHI_WHICHSS_TRYREGEX|
                  GESHI_WHICHSS_SKIPANCHORINSERT);
                if ($esc_seq === null) continue;
                else $char_len = $esc_len + strlen($esc_seq);
            } else if ($empty) continue;
            else $char_len = 1; /* Possible single unescaped character */

            $final_char_offset = $data['len'] + $char_len;

            /* Check for an end delimiter and if found, return successfully */
            $end_delim = geshi_whichsubstr($code, $this->_contextDelimiters[
              $data['key']][1], $data['pos'] + $final_char_offset,
              GESHI_WHICHSS_MAXIMAL|GESHI_WHICHSS_TRYREGEX);
            if ($end_delim !== null) {
                $data['pos'] += $offset;
                $this->_characterLen = $char_len;
                $this->_endDelimiterLen = strlen($end_delim);
                $this->_isEscapeSeq = ($esc_start !== null);
                break;
            }
        }
        return $data;
    }

    // }}}
    // {{{ _getContextEndData()

    /**
     * In this case we don't need to worry about much because we have made sure
     * in _getContextStartData that we are starting in the right place.
     */
    function _getContextEndData ($code, $context_open_key, $context_opener,
      $beginning_of_context)
    {
        return array('pos' => $this->_characterLen,
                     'len' => $this->_endDelimiterLen,
                     'dlm' => '');
    }

    // }}}
    // {{{ _addParseData()

    /**
     * Overrides _addParseData to add escape characters also
     */
    function _addParseData ($code, $first_char_of_next_context = '')
    {
        geshi_dbg('GeSHiSingleCharContext::_addParseData(' .
          substr($code, 0, 15) . '...)');
        if ($this->_isEscapeSeq) {
            $this->_styler->addParseData($code, $this->_contextName . '/esc',
                $this->_getExtraParseData(), $this->_complexFlag);
        } else {
            parent::_addParseData($code, $first_char_of_next_context);
        }
    }

    // }}}

}

?>
