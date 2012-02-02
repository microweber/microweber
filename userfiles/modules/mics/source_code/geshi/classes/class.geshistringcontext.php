<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/classes/class.geshistringcontext.php
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
 * @author     Nigel McNie <nigel@geshi.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2004 - 2006 Nigel McNie
 * @version    1.1.2alpha3
 * 
 */

/**
 * The GeSHiStringContext class. This class extends GeSHiContext to handle
 * the concept of escape characters that strings often use.
 *  
 * @package    geshi
 * @subpackage core
 * @author     Nigel McNie <nigel@geshi.org>
 * @since      1.1.0
 * @version    1.1.2alpha3
 * @see        GeSHiContext
 */
class GeSHiStringContext extends GeSHiContext
{
    
    // {{{ properties
    
    /**#@-
     * @access private
     */
    
    /**
     * Escape character groups.
     * 
     * @var array
     */
    var $_escapeGroups = array();
    
    /**#@-*/
    
    // }}}
    // {{{ addEscapeGroup()
    
    /**
     * Adds an escape group to this string context.
     * 
     * An escape group consists of a group of characters that are escape
     * characters, and another group of characters or regexes that are
     * the characters to escape. You can also specify a context name for
     * the escaped characters.
     *
     * The escape characters MUST be one character in length, and are
     * automatically assumed to escape themselves.
     * 
     * @param mixed  $escape_characters    The characters that escape others
     * @param mixed  $characters_to_escape The characters/regexes that are
     *                                     escaped
     * @param string $context_name         A name for the escaped characters
     */
    function addEscapeGroup ($escape_characters,
        $characters_to_escape = array(), $context_name = 'esc')
    {
        // Sanity checking
        $escape_characters = (array) $escape_characters;
        $characters_to_escape = (array) $characters_to_escape;
        foreach ($escape_characters as $char) {
            if (strlen($char) != 1) {
                trigger_error('GeSHiStringContext::addEscapeGroup(): malformed'
                    . ' language file: cannot have escape characters that are'
                    . ' longer than one character in length');
            }
            if (!in_array($char, $characters_to_escape)) {
                $characters_to_escape[] = $char;
            }
        }

        $this->_escapeGroups[] = array(
            $escape_characters,
            $characters_to_escape,
            $context_name
        );
    }
    
    // }}}
    // {{{ _getContextEndData()
    
    /**
     * Finds the end of a string context, taking the escape characters into
     * account.
     * 
     * @param string $code             The code to look for the end of the
     *                                 context in
     * @param int    $context_open_key The key in the array of delimiters
     *                                 which corresponds to the opener
     * @param string $context_opener   The actual opener for the string
     */
    function _getContextEndData ($code, $context_open_key, $context_opener)
    {
        geshi_dbg('GeSHiStringContext::_getContextEndData('
            . $this->_contextName . ')');
        $this->_lastOpener = $context_opener;
        $ender_data = array();
        
        foreach ($this->_contextDelimiters[$context_open_key][1] as $ender) {
            // Prepare ender regexes if needed
            $ender = $this->_substitutePlaceholders($ender);
            geshi_dbg('  Checking ender: ' . str_replace("\n", '\n', $ender));

            $tmp_str = $code;
            $current_pos = 0;

            while (true) {
                geshi_dbg("@btop of loop; current_pos = $current_pos; str="
                    . substr($tmp_str, 0, 10));
                $pos_data = geshi_get_position($tmp_str, $ender);
                if (false === $pos_data['pos']) {
                    geshi_dbg("could not find ender $ender in string "
                        . substr($tmp_str, 0, 10));
                    break;
                }
                geshi_dbg("found ender $ender at position " . $pos_data['pos']);

                // While we may have found an ender, it might be escaped.
                // Finding out for sure whether it is escaped is harder than
                // it may initially seem - we have to check each previous
                // character to see if it escapes the one after it, and flip
                // a flag which detects whether the initial character is
                // escaped, or whether the character before the initial
                // character is escaped (and thus the ender we found is the
                // real thing).
                $i = $pos_data['pos'] - 1;
                if ($i >= 0) {
                    $current_char = substr($tmp_str, $i, 1);
                    $after_char   = substr($tmp_str, $i + 1, 1);
                    geshi_dbg("checking char $current_char to see if it"
                       . " escapes the char $after_char");
                    if ($this->_charEscapesChar($current_char, $after_char)) {
                        geshi_dbg("  it does! Might not have found the ender");
                        $found_ender = true;
                        geshi_dbg('checking whether ' . substr($tmp_str, $i, 1)
                            . ' escapes ' . substr($tmp_str, $i + 1, 1));
                        while (($i == 0 && $this->_isEscapeChar(substr($tmp_str, $i, 1))) ||
                            $i > 0
                            && $this->_charEscapesChar(substr($tmp_str, $i, 1),
                                substr($tmp_str, $i + 1, 1))) {
                            $found_ender = !$found_ender;
                            if (0 == $i) {
                                geshi_dbg('reached start of string and char is escape');
                            } else {
                                geshi_dbg(substr($tmp_str, $i, 1) . ' escapes '
                                . substr($tmp_str, $i + 1, 1) . ': found_ender='
                                . $found_ender);
                            }
                            --$i;
                        }
                        geshi_dbg('finished: found_ender=' . $found_ender);
                        if (!$found_ender) {
                            geshi_dbg('we did NOT find ender, it was escaped');
                            $current_pos += $pos_data['pos'] + 1;
                            $tmp_str = substr($tmp_str, $pos_data['pos'] + 1);
                            continue;
                        }
                        geshi_dbg('Found ender since the last char is escaped');
                    }
                    else {
                        geshi_dbg(" does  not seem to escape the next char");
                    }
                }

                if ($pos_data['pos'] != strlen($tmp_str)
                    && $this->_charEscapesChar($ender,
                        substr($tmp_str, $pos_data['pos'] + 1, 1))) {
                    // We did not find the ender
                    geshi_dbg('ender is escaping the next char - '
                        . substr($tmp_str, $pos_data['pos'] + 1, 1));
                    $current_pos += $pos_data['pos'] + 1 + $pos_data['len'];
                    $tmp_str = substr($tmp_str, $pos_data['pos'] + 1
                        + $pos_data['len']);
                    continue;
                }
                else {
                    geshi_dbg("Not escaped or escaping: Found at position "
                        . $pos_data['pos']);
                    if (!$ender_data || $pos_data['pos'] < $ender_data['pos']) {
                        geshi_dbg('earliest');
                        $ender_data['pos'] = $pos_data['pos'] + $current_pos;
                        $ender_data['dlm'] = $ender;
                        $ender_data['len'] = $pos_data['len'];
                    }
                    
                    break;
                }
            }
        }
        geshi_dbg('Ender data: ' . print_r($ender_data, true));
        return ($ender_data) ? $ender_data : false;
    }
    
    // }}}
    // {{{ _charEscapesChar()

    /**
     * Returns true if $escape_char escapes $char_to_escape.
     *
     * @param string $escape_char    The escape character
     * @param string $char_to_escape The character being escaped
     * @return boolean
     */
    function _charEscapesChar ($escape_char, $char_to_escape)
    {
        static $result_table = array();
        if (isset($result_table[$escape_char][$char_to_escape])) {
            return $result_table[$escape_char][$char_to_escape];
        }

        foreach ($this->_escapeGroups as $group) {
            if (in_array($escape_char, $group[0])) {
                return $result_table[$escape_char][$char_to_escape]
                    = in_array($char_to_escape, $group[1]);
            }
        }

        return $result_table[$escape_char][$char_to_escape] = false;
    }

    // }}}
    // {{{ _isEscapeChar()

    /**
     * Returns true if $escape_char is an escape character in any group.
     *
     * @param string $escape_char The escape character
     * @return boolean
     */
    function _isEscapeChar ($escape_char)
    {
        static $result_table = array();
        if (isset($result_table[$escape_char])) {
            return $result_table[$escape_char];
        }

        foreach ($this->_escapeGroups as $group) {
            if (in_array($escape_char, $group[0])) {
                return $result_table[$escape_char] = true;
            }
        }
        return $result_table[$escape_char] = false;
    }

    // }}}
    // {{{ _addParseData()
    
    /**
     * Overrides addParseData to add escape characters also.
     * 
     * @param string $code
     * @param string $first_char_of_next_context
     */
     function _addParseData ($code, $first_char_of_next_context = '')
     {
        geshi_dbg('GeSHiStringContext::_addParseData(' . substr($code, 0, 15));
        
        $length = strlen($code);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $char = substr($code, $i, 1);
            geshi_dbg('Char: ' . $char);
            $skip = false;
            
            foreach ($this->_escapeGroups as $group) {
                foreach ($group[0] as $escape_char) {
                    $len = 1;
                    if ($char == $escape_char 
                        && (false !== ($len = $this->_shouldBeEscaped(
                            substr($code, $i + 1), $group[1])))) {
                        geshi_dbg('Match: len = ' . $len);
                        if ($string) {
                            $this->_styler->addParseData($string,
                                $this->_contextName,
                                $this->_getExtraParseData(),
                                $this->_complexFlag);
                            $string = '';
                        }

                        $this->_styler->addParseData($escape_char
                            . substr($code, $i + 1, $len),
                             "$this->_contextName/$group[2]",
                             $this->_getExtraParseData(),
                             $this->_complexFlag);
                        $i += $len;
                        $skip = true;
                        break;
                    }
                }
            }
            
            if (!$skip) {
                $string .= $char;
            }
        }
        if ($string) {
            $this->_styler->addParseData($string, $this->_contextName,
                $this->_getExtraParseData(),
                $this->_complexFlag);
        }
    }
    
    // }}}
    // {{{ _shouldBeEscaped()
     
    /**
     * Checks whether the character(s) at the start of the parameter string are
     * characters that should be escaped.
     * 
     * @param string The string to check the beginning of for escape characters
     * @return int|false The length of the escape character sequence, else false
     */
    function _shouldBeEscaped ($code, $chars_to_escape)
    {
        geshi_dbg('Checking: ' . substr($code, 0, 15));
        foreach ($chars_to_escape as $match) {
            if ('REGEX' != substr($match, 0, 5)) {
                geshi_dbg('Test: ' . $match);
                if (substr($code, 0, 1) == $match) {
                    return 1;
                }
            }
            else {
                geshi_dbg('  Testing via regex: ' . $match . '... ', false);
                $data = geshi_get_position($code, $match, 0);
                if (0 === $data['pos']) {
                    geshi_dbg('match, data = ' . print_r($data, true));
                    return $data['len'];
                }
                geshi_dbg('no match');
            }
        }
        // No matches...
        return false;
    }
    
    // }}}
    
}

?>
