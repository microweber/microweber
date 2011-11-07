<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/languages/delphi/class.delphicodeparser.php
 *   Author: Nigel McNie
 *   E-mail: nigel@geshi.org
 * </pre>
 * 
 * For information on how to use GeSHi, please consult the documentation
 * found in the docs/ directory, or online at http://geshi.org/docs/
 *
 *  This file is part of GeSHi.
 *
 *  GeSHi is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  GeSHi is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with GeSHi; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * You can view a copy of the GNU GPL in the COPYING file that comes
 * with GeSHi, in the docs/ directory.
 *
 * @package    geshi
 * @subpackage lang
 * @author     Benny Baumann <BenBE@benbe.omorphia.de>, Nigel McNie <nigel@geshi.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2004 - 2006 Nigel McNie
 * @version    1.1.2alpha3
 *
 */

/** Get the GeSHiCodeParser class */
require_once GESHI_CLASSES_ROOT . 'class.geshicodeparser.php';

/**
 * The GeSHiDelphiCodeParser class
 *
 * @package    geshi
 * @subpackage lang
 * @author     Benny Baumann <BenBE@benbe.omorphia.de>, Nigel McNie <nigel@geshi.org>
 * @since      1.1.1
 * @version    1.1.2alpha3
 */
class GeSHiDelphiCodeParser extends GeSHiCodeParser
{
    
    // {{{ properties

    /**
     * A flag that can be used for the "state" of parsing
     * @var string
     * @access private
     */
    var $_state = '';

    /**
     * Flag for default keyword fix
     * @var boolean
     * @access private
     */
    var $_defaultFlag = false;

    /**
     * Flag for register directive handling
     * @var int
     * @access private
     */
    var $_bracketCount = 0;

    /**
     * Flag for detection of directives used inside code blocks where they are not to be highlighted
     * @var int
     * @access private
     */
    var $_openBlockCount = 0;

    /**
     * Stores the opening tokens of the blocks detected
     * @var array
     * @access private
     */
    var $_openBlockType = array();

    /**
     * Flag for semicolon detection
     * @var boolean
     * @access private
     */
    var $_semicolonFlag = false;

    /**
     * Flag for ASM block detection
     * @var boolean
     * @access private
     */
    var $_inASMBlock = false;

    /**
     * Flag for instruction detection in ASM
     * @var boolean
     * @access private
     */
    var $_instrExpected = false;

    // }}}
    // {{{ parseToken()

    /**
     * This method can either return an array like
     * this one is doing, or nothing (false), or an
     * array of arrays. This way, it can hold onto
     * data it needs for parsing
     *
     * @todo [blocking 1.1.5] delphi fixes:
     *   - highlight default keyword if after ; in property context
     *   - don't highlight functions if not before "(" brackets (alpha)
     */
    function parseToken ($token, $context_name, $data)
    {
        geshi_dbg('GeSHiDelphiCodeParser::parseToken("' . substr(str_replace("\n", '\n', $token), 0, 15) . '"...,' . $context_name . ')');

        //Check for linebraks...
        if (false !== stripos($token, "\n")) {
            $this->_semicolonFlag = false;
            $this->_instrExpected = true;
        }

        //Check if we got a whitespace
        if (geshi_is_whitespace($token)) {
            //If there's anything in the storage, simply add the whitespace
            if ($this->_stack) {
                $this->push($token, $context_name, $data);
                return false;
            } else {
                //Return the token as is ...
                return $this->flush($token, $context_name, $data);
            }
        }

        $token_l = strtolower(trim($token));

        // @todo for ben: here is an example of how this could work. You can make it better and
        // experiment with how this functionality works. I tested this only on simple examples, and
        // I know that currently the _defaultFlag could be reset to 0 earlier than it is if there is
        // a mistake with parsing.
        if (2 == $this->_defaultFlag) {
            if ('default' == $token_l) {
                $context_name = $this->_language . '/keyword';
                $this->_defaultFlag = 0;
            } elseif ('' != trim($token)) {
                $this->_defaultFlag = 0;
            }
        }
        
        // @todo for ben: I don't think alias_name is set anymore, maybe you want to check
        // that this functionality works now?
        if (0 == $this->_defaultFlag && isset($data['alias_name']) && $data['alias_name'] == $this->_language . '/property') {
            $this->_defaultFlag = 1;
        }
        if (1 == $this->_defaultFlag && ';' == trim($token)) {
            $this->_defaultFlag = 2;
        }

        // @todo for ben: now symbols are handed in one at a time, maybe this can be optimised?
        if ($context_name == $this->_language . '/brksym') {
            geshi_dbg('Detected bracket symbol context ...');
            for ($t2 = 0; $t2 < strlen($token); $t2++) {
                $t2sub = substr($token, $t2, 1);
                // Count opening and closing brackets to avoid highlighting of parameters called register in procedure\function declarations
                if ('(' == $t2sub||'[' == $t2sub) {
                    geshi_dbg('Detected opening bracket "'.$t2sub.'" on level BC' . $this->_bracketCount . '\OBC' . $this->_openBlockCount . '...');
                    $this->_bracketCount++;
                }
                if (')' == $t2sub||']' == $t2sub) {
                    if (--$this->_bracketCount < 0) {
                        $this->_bracketCount = 0;
                    }
                    geshi_dbg('Detected closing bracket "'.$t2sub.'" on level BC' . $this->_bracketCount . '\OBC' . $this->_openBlockCount . '...');
                }
            }
        }

        if (!stripos($context_name, 'comment')) {
            if ('begin' == $token_l ||
                'case' == $token_l ||
                'class' == $token_l ||
                'object' == $token_l ||
                'record' == $token_l ||
                'try' == $token_l ||
                'asm' == $token_l) {
                geshi_dbg('Detected opening block "'.$token_l.'" on level BC' . $this->_bracketCount . '\OBC' . $this->_openBlockCount . '...' . stripos($context_name, 'comment'));

                $this->_openBlockCount++;
                $this->_openBlockType[] = $token_l;
                if (2 <= ($obc = $this->_openBlockCount)) {
                    //Check if we have a casxe statement inside a record definition.
                    if ('record' == $this->_openBlockType[$obc-2] && 'case' == $this->_openBlockType[$obc-1]) {
                        array_pop($this->_openBlockType);
                        $this->_openBlockCount--;
                    }
                }

                $this->_instrExpected = true;
                $this->_inASMBlock = true;
            }
            if ('end' == $token_l) {
                if (--$this->_openBlockCount < 0) {
                    $this->_openBlockCount = 0;
                }
                array_pop($this->_openBlockType);
                geshi_dbg('Detected closing block "'.$token_l.'" on level BC' . $this->_bracketCount . '\OBC' . $this->_openBlockCount . '...' . stripos($context_name, 'comment'));

                if ($this->_inASMBlock) {
                    $this->_inASMBlock = true;
                }
            }
        }

        if ($this->_inASMBlock && stripos($this->_language, 'delphi/asm')) {
            if ($this->_instrExpected) {
                $this->_instrExpected = false;
            } else {
                if ($token_l == 'and' ||
                    $token_l == 'not' ||
                    $token_l == 'or' ||
                    $token_l == 'shl' ||
                    $token_l == 'shr' ||
                    $token_l == 'xor') {
                    $context_name = $this->_language . '/asm/keyop';
                }
            }
            if (trim($token) == ';') {
                $this->_instrExpected = true;
            }
        }

        // If we detect a semicolon we require remembering it, thus we can highlight the register directive correctly.
        if ($context_name == $this->_language && $this->_semicolonFlag) {
        geshi_dbg('Detected token '. $token . ' after semi-colon on level BC' . $this->_bracketCount . '\OBC' . $this->_openBlockCount . '...');
            // Register is a directive here
            $this->_semicolonFlag = false;
            // Highlight as directive only if all previous opened brackets are closed again
            $isDirective = (0 == $this->_bracketCount);
            if ('register' == $token_l) {
                if (1 == $this->_openBlockCount) {
                    $isDirective &=
                        'class' == $this->_openBlockType[$this->_openBlockCount-1] ||
                        'object' == $this->_openBlockType[$this->_openBlockCount-1];
                    if ('record' == $this->_openBlockType[$this->_openBlockCount-1]) {
                         $isDirective = true;
                    }
                }

                $context_name .= ($isDirective ? '/keyword' : '');
            } elseif ('message' == $token_l) {
                if (1 == $this->_openBlockCount) {
                    $isDirective &= 'class' == $this->_openBlockType[$this->_openBlockCount-1];
                }
                $context_name .= ($isDirective ? '/keyword' : '');
            }
        }
        // There will be something else than a semicolon, so we finish semicolon detection here
        $this->_semicolonFlag = false;
        if (trim($token) == ';') {
            $this->_semicolonFlag = true;
        }

        if ($this->_stack) {
            // Check for various conditions ...

            // If we have a store we can check now to see if the current token is a bracket
            if ($context_name != $this->_language . '/brksym' || substr(trim($token), 0, 1) != '(') {
                // Modify context to say that the keyword is actually just a bareword
                $this->_stack[0][1] = $this->_language;
            }
            //return $this->_stackFlush($token, $context_name, $data);
            return $this->flush($token, $context_name, $data);
        }

        // If we detected a keyword, instead of passing it back we will make sure it has a bracket
        // after it, so we know for sure that it is a keyword. So we save it to "_store" and return false
        if (substr($context_name, 0, strlen($this->_language . '/stdproc')) == $this->_language . '/stdproc') {
            $this->push($token, $context_name, $data);
            return false;
        }

        // Default action: just return the token (including all stored)
        return $this->flush($token, $context_name, $data);
    }

    // }}}

}

?>