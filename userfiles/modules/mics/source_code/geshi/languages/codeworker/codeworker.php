<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/languages/codeworker/codeworker.php
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
 * @author     Nigel McNie <nigel@geshi.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2004 - 2006 Nigel McNie
 * @version    1.1.2alpha3
 *
 */

/**#@+
 * @access private
 */

// @todo [blocking 1.1.2] noticed wierd error when no children: script<".cws">
// the < wasn't being highlighted as a symbol
function geshi_codeworker_codeworker (&$context)
{
    $context->addChild('single_comment');
    $context->addChild('multi_comment');
    $context->addChild('single_string', 'string');
    $context->addChild('double_string', 'string');
    $context->addChildLanguage('codeworker/roughtext', array(
        'REGEX#generate\s*\(\s*\{#', 'REGEX#generateString\s*\(\s*\{#', 'REGEX#expand\s*\(\s*\{#'
    ), '}'/*
        false,
        array('{', '}')   // number 3 is balancing.
        // an array opener=>closer
        // what about cases where you have multiple entries for opener/closer??? (just specify as limitation)
    ),*/
    );
    
    $context->addKeywordGroup(array(
        'break', 'do', 'else', 'foreach', 'forfile', 'function', 'if', 'in',
        'insert', 'local', 'localref', 'node', 'pushItem', 'ref', 'return',
        'value', 'while'
    ), 'keyword');
    
    $context->addKeywordGroup(array(
        'clearVariable', 'composeCLikeString', 'composeHTMLLikeString',
        'decrementIndentLevel', 'empty', 'findLastString', 'first',
        'getInputFilename', 'getOutputFilename', 'getShortFilename',
        'incrementIndentLevel', 'key', 'last', 'leftString', 'loadFile',
        'midString', 'readChars', 'removeElement', 'replaceString', 'rsubString',
        'setInputLocation', 'size', 'startString', 'subString', 'toLowerString',
        'toUpperString', 'traceLine'
    ), 'function');
    
    $context->addKeywordGroup(array(
        'false', 'project', 'this', 'true', '_ARGS', '_REQUEST'
    ), 'constant');
    
    $context->addKeywordGroup(array(
        'parseAsBNF', 'parseStringAsBNF', 'translate', 'translateString'
    ), 'sfunction');
    
    $context->addKeywordGroup(array(
        '|', '=', '!', ':', '(', ')', ',', '<', '>', '&', '$', '+', '-', '*', '/',
        '{', '}', ';', '[', ']', '~', '?'
    ), 'symbol');
    
    $context->addRegexGroup('/(#[a-zA-Z][a-zA-Z0-9\-_]*)/', '#', array(
            1 => array('preprocessor', false)
    ));
    $context->useStandardIntegers();
    $context->useStandardDoubles();
    
    $context->addObjectSplitter('.', 'oodynamic', 'symbol');  
}

function geshi_codeworker_codeworker_single_comment (&$context)
{
    $context->addDelimiters('//', "\n");
    //$this->_contextStyleType = GESHI_STYLE_COMMENTS;
}

function geshi_codeworker_codeworker_multi_comment (&$context)
{
    $context->addDelimiters('/*', '*/');
    //$this->_contextStyleType = GESHI_STYLE_COMMENTS;
}

function geshi_codeworker_codeworker_single_string (&$context)
{
    $context->addDelimiters("'", "'");
    //$this->_contextStyleType = GESHI_STYLE_STRINGS;
    $context->addEscapeGroup('\\');
}

function geshi_codeworker_codeworker_double_string (&$context)
{
    $context->addDelimiters('"', '"');
    //$this->_contextStyleType = GESHI_STYLE_STRINGS;
    $context->addEscapeGroup('\\', array('n', 'r', 't'));
}

/**#@-*/

?>
