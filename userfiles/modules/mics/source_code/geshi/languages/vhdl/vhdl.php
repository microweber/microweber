<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/languages/vhdl/vhdl.php
 *   Author: Lingzi Xue
 *   E-mail: chocolatemurderer@gmail.com
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
 * @author     Lingzi Xue <chocolatemurderer@gmail.com>, Nigel McNie <nigel@geshi.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2004 -2006 Nigel McNie
 * @version    1.1.2alpha3
 *
 */

/**#@+
 * @access private
 */

/** Get the GeSHiSingleCharContext class */
require_once GESHI_CLASSES_ROOT . 'class.geshisinglecharcontext.php';

function geshi_vhdl_vhdl (&$context)
{
    $context->addChild('single_string', 'singlechar');
    $context->addChild('double_string', 'string');
    $context->addChild('comment');

    // Basic keywords
    $context->addKeywordGroup(array(
        'abs','access','after','alias','all','and','architecture','array','assert','attribute',
        'begin','block','body','buffer','bus','case','component','configuration','constant',
        'disconnent','downto','else','elsif','end','end block','end case','end component',
        'end for','end generate','end if','end loop','end process','end record','end units',
        'entity','exit','file','for','function','generate','generic','generic map','group',
        'guarded','if','impure','in','inertial','inout','is','label','library','linkage',
        'literal','loop','map','mod','nand','new','next','nor','null','of','on','open','or',
        'others','out','package','package body','port','port map','postponed','procedure',
        'process','pure','range','record','register','reject','rem','report','return','rol',
        'ror','select','severity','signal','sla','sll','sra','srl','subtype','then','to',
        'transport','type','unaffected','units','until','use','variable','wait','when','while',
        'with','xnor','xor'
    ), 'keyword');

    $context->setCharactersDisallowedBeforeKeywords("'");
    $context->setCharactersDisallowedAfterKeywords("'");

    $context->addSymbolGroup(array(
        '(', ')', ',', ';', ':', '[', ']',
        '+', '-', '*', '/', '&', '|', '!', '<', '>',
        '{', '}', '=', '@', '.'
    ), 'symbol');

    $context->useStandardIntegers();
    $context->useStandardDoubles();  
}

function geshi_vhdl_vhdl_single_string (&$context)
{
    $context->addDelimiters("'", "'");
    //$this->_contextStyleType = GESHI_STYLE_STRINGS;
    $context->setEscapeCharacters('\\');
    $context->setCharactersToEscape('\\', "'");
}

function geshi_vhdl_vhdl_double_string (&$context)
{
    $context->addDelimiters('"', '"');
    //$this->_contextStyleType = GESHI_STYLE_STRINGS;
    $context->addEscapeGroup('\\', array('n', 'r', 't', '\\', '"'));
}
    
function geshi_vhdl_vhdl_comment (&$context)
{
    $context->addDelimiters('--', "\n");
    //$this->_contextStyleType = GESHI_STYLE_COMMENTS;
}   

/**#@-*/

?>
