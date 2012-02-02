<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/languages/c/c.php
 *   Author: Netocrat
 *   E-mail: N/A
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
 * @author     http://clc-wiki.net/wiki/User:Netocrat
 * @link       http://clc-wiki.net/wiki/Development:GeSHi_C Bug reports
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2006 Netocrat
 * @version    1.1.2alpha3
 *
 */

/**#@+
 * @access private
 */

/**
 * A single include file for all data including keyword listings; also
 * accessed by class.geshiccodeparser.php.
 */
require_once GESHI_LANGUAGES_ROOT.'c'.GESHI_DIR_SEP.'common.php';

function geshi_c_c (&$context)
{
    $context->addChild('multi_comment');
    $context->addChild('single_comment');
    $context->addChild('string_literal', 'string');
    $context->addChild('widestring_literal', 'string');
    $context->addChild('character_constant', 'singlechar');
    $context->addChild('widecharacter_constant', 'singlechar');
    $context->addChild('preprocessor', 'code');

    $context->addKeywordGroup(geshi_c_get_ctlflow_keywords(),
      'ctlflow-keyword', true, geshi_c_get_ctlflow_keywords_url());

    $context->addKeywordGroup(geshi_c_get_declarator_keywords(),
      'declarator-keyword', true, geshi_c_get_declarator_keywords_url());

    $context->addKeywordGroup(geshi_c_get_types_and_qualifiers(),
      'typeorqualifier', true, geshi_c_get_types_and_qualifiers_url());

    $context->addKeywordGroup(geshi_c_get_standard_functions(),
      'stdfunction', true, geshi_c_get_standard_functions_url());

    $context->addKeywordGroup(geshi_c_get_standard_macros_and_objects(),
      'stdmacroorobject', true, geshi_c_get_standard_macros_and_objects_url());

    $context->addSymbolGroup(geshi_c_get_standard_symbols(), 'symbol');

    $context->useStandardIntegers();
    $context->useStandardDoubles(array('chars_after_number' => array('f','l')));

    $context->addObjectSplitter(geshi_c_get_structure_access_symbols(),
      'member', 'symbol');
    $context->setComplexFlag(GESHI_COMPLEX_TOKENISE);
}

function geshi_c_c_multi_comment (&$context)
{
    $context->addDelimiters('/*', '*/');
    $context->setComplexFlag(GESHI_COMPLEX_PASSALL);
}

function geshi_c_c_single_comment (&$context)
{
    $context->addDelimiters('//', "\n");
    $context->setComplexFlag(GESHI_COMPLEX_PASSALL);
    /* Without this, and when the comment occurs at the end of a preprocessor
     * directive, any immediately subsequent preprocessor directive is treated
     * as a continuation of the first one. */
    $context->parseDelimiters(GESHI_CHILD_PARSE_LEFT);
}

/* A (wide)string literal may be continued to the next line through the use of a
 * trailing \ but otherwise multiline strings are illegal.  This code doesn't
 * attempt to mark erroroneous multiline strings, and slash-continuation is
 * handled generically in GeSHiCCodeParser::parseToken().  This code does
 * terminate strings on newlines though due to the legality of the appearance
 * of unmatched double quote marks in #error and #pragma directives.
 * GeSHiCCodeParser::parseToken() later unhighlights such unterminated strings
 * but they can't be allowed to continue over the line otherwise the
 * #error/#pragma directive will be incorrectly continued over multiple lines.
 * prior to GeSHiCCodeParser receiving it.
 */
function geshi_c_c_string_literal (&$context)
{
    geshi_c_base_string($context, '"', array('"', 'REGEX#(?=\n)#'), false);
}
function geshi_c_c_widestring_literal (&$context)
{
    geshi_c_base_string($context, 'L"', array('"', 'REGEX#(?=\n)#'), true);
}
function geshi_c_c_character_constant (&$context)
{
    geshi_c_base_singlechar($context, "'", "'", false);
    $context->setDisallowEmptyChars();
}
function geshi_c_c_widecharacter_constant (&$context)
{
    geshi_c_base_singlechar($context, "L'", "'", true);
    $context->setDisallowEmptyChars();
}

function geshi_c_base_string (&$context, $delim_start, $delim_end, $delim_cs) {
    $context->addDelimiters($delim_start, $delim_end, $delim_cs);
    $context->addEscapeGroup('\\', array("'", '"', '?', '\\', 'a', 'b', 'f',
        'n', 'r', 't', 'v', 'REGEX#([0-7]{1,3}|x[0-9a-f]{1,})#i'));
    $context->setComplexFlag(GESHI_COMPLEX_PASSALL);
}

function geshi_c_base_singlechar (&$context, $delim_start, $delim_end, $delim_cs) {
    $context->addDelimiters($delim_start, $delim_end, $delim_cs);
    $context->setEscapeCharacters('\\');
    $context->setCharactersToEscape(array("'", '"', '?', '\\', 'a', 'b', 'f',
        'n', 'r', 't', 'v', 'REGEX#([0-7]{1,3}|x[0-9a-f]{1,})#i'));
    $context->setComplexFlag(GESHI_COMPLEX_PASSALL);
}

/*
 * Duplicate these functions for the preprocessor simply so that they can have
 * a different highlighting context.
 */
function geshi_c_c_preprocessor_multi_comment (&$context)
{
    geshi_c_c_multi_comment ($context);
}
function geshi_c_c_preprocessor_single_comment (&$context)
{
    geshi_c_c_single_comment ($context);
}
function geshi_c_c_preprocessor_string_literal (&$context)
{
    geshi_c_c_string_literal ($context);
}
function geshi_c_c_preprocessor_widestring_literal (&$context)
{
    geshi_c_c_widestring_literal ($context);
}
function geshi_c_c_preprocessor_character_constant (&$context)
{
    geshi_c_c_character_constant ($context);
}
function geshi_c_c_preprocessor_widecharacter_constant (&$context)
{
    geshi_c_c_widecharacter_constant ($context);
}

function geshi_c_c_preprocessor (&$context)
{
    /* A preprocessing directive beginning with a # must occur at the start
     * of a line, but may optionally be preceded by whitespace.  The hash may
     * optionally be followed by whitespace, after which the actual directive
     * keyword is specified.  Finally though, a hash without a following
     * directive is allowed as a 'null directive'.
     *
     * There is also a single preprocessing directive (_Pragma) that follows
     * the same rules but that is not preceded by a hash
     *
     * The list of non-newline whitespace characters recognised by C and
     * used in the r.e. below is: [ \t\f\v] */
    $context->addDelimiters('REGEX#((^|\n)([ \t\f\v]*)(?=(\#|_Pragma(\b))))#',
      "\n", true);

    $context->addChild('c/c/preprocessor/multi_comment');
    $context->addChild('c/c/preprocessor/single_comment');
    /* String literal escaping is disabled by GeSHiCCodeParser::parseToken()
     * for double-quote quoted text that is interpreted directly as a filename,
     * namely <code>#include "filename.h"</code>.  Escape sequences and comment-
     * like sequences within those double quotes cause behaviour undefined by
     * standard C so perhaps they should even be highlighted as warnings.
     * It's tolerable that as a minor glitch escape-highlighting of such text
     * occurs when the parser is disabled. */
    $context->addChild('c/c/preprocessor/string_literal', 'string');
    $context->addChild('c/c/preprocessor/widestring_literal', 'string');
    $context->addChild('c/c/preprocessor/character_constant', 'singlechar');
    $context->addChild('c/c/preprocessor/widecharacter_constant', 'singlechar');

    /* GeSHiCCodeParser::parseToken() ensures that this highlighting doesn't
     * occur within #line and #include directives when macros are used to
     * specify either directive's "arguments". */
    $context->addKeywordGroup(geshi_c_get_ctlflow_keywords(),
      'c/c/preprocessor/ctlflow-keyword', true,
      geshi_c_get_ctlflow_keywords_url());

    /* The NOTES file describes when/why various keywords are invalid, and when/
     * why these calls are overridden by GeSHiCCodeParser::parseToken() code. */
    $context->addKeywordGroup(geshi_c_get_declarator_keywords(),
      'c/c/preprocessor/declarator-keyword', true,
      geshi_c_get_declarator_keywords_url());
    $context->addKeywordGroup(geshi_c_get_types_and_qualifiers(),
      'c/c/preprocessor/typeorqualifier', true,
      geshi_c_get_types_and_qualifiers_url());
    $context->addKeywordGroup(geshi_c_get_standard_functions_url(),
      'c/c/preprocessor/stdfunction', true,
      geshi_c_get_standard_functions_url());
    $context->addKeywordGroup(geshi_c_get_standard_macros_and_objects(),
      'c/c/preprocessor/stdmacroorobject', true,
      geshi_c_get_standard_macros_and_objects_url());

    /* The NOTES file describes when/why highlighting of various symbols is
     * in)appropriate, and when/why this call is overridden by code in
     * GeSHiCCodeParser::parseToken(). */
    $context->addSymbolGroup(geshi_c_get_standard_symbols(),
      'c/c/preprocessor/symbol');

    $context->addSymbolGroup(geshi_c_get_preprocessor_symbols(),
      'c/c/preprocessor/directive');

    /* This can't be enabled here because it causes filenames with dots in them
     * in #include statements to be highlighted as though they were structure
     * member accesses: when the parser is disabled that can't be undone.
     */
//    $context->addObjectSplitter(geshi_c_get_structure_access_symbols(),
//      'c/c/preprocessor/member', 'symbol');

    $context->useStandardIntegers();
    $context->useStandardDoubles(array('chars_after_number' => array('f','l')));

    $context->setComplexFlag(GESHI_COMPLEX_TOKENISE);
}

/**#@-*/

?>
