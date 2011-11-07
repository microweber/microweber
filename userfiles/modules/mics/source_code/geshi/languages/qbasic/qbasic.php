<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/languages/qbasic/qbasic.php
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

function geshi_qbasic_qbasic (&$context)
{
    $context->addChild('string');
    $context->addChild('comment');
    
    $context->addKeywordGroup(array(
        'case', 'case else', 'do', 'else', 'elseif', 'exit for', 'exit do', 'for', 'if',
        'loop', 'next', 'step', 'select case', 'then', 'to', 'until', 'wend', 'while',
        
        'dim', 'data'
    ), 'keyword', false, 'geshi_qbasic_url()');

    $context->addKeywordGroup(array(
        'function', 'end function', 'sub', 'end sub', 'call', 'exit function', 'exit sub',
        'declare', 'common', 'shared', 'static', 'def fn', 'end def', 'exit def', 'gosub',
        'chain', 'return',
        // Hmm?
        'calls', 'call absolute', 'call interrupt', 'run'
    ), 'procedure', false, 'geshi_qbasic_url()');
    
    $context->addKeywordGroup(array(
        'print', 'print using', 'width', 'inkey$', 'input$', 'input', 'line input',
        'locate', 'spc', 'tab', 'csrlin', 'pos', 'view print',
        // file IO
        'open', 'close', 'write', 'put', 'get', 'files', 'freefile', 'kill', 'name',
        'eof', 'fileattr', 'loc', 'lof', 'seek', 'read'
        // file IO dupes
        // 'print', 'print using', 'width', 'input', 'input$', 'line input' 
    ), 'io', false, 'geshi_qbasic_url()');
    
    $context->addKeywordGroup(array(
        'left$', 'right$', 'ltrim$', 'rtrim$', 'mid$', 'instr', 'lcase$', 'ucase$', 'lset',
        'rset', 'str$', 'val', 'cvi', 'cvs', 'cvl', 'cvd', 'cvsmbf', 'cvdmbf', 'mki$', 'mks$',
        'mkl$', 'mkd$', 'mksmbf$', 'mkdmbf$', 'space$', 'string$', 'len', 'asc', 'chr$'
    ), 'string', false, 'geshi_qbasic_url()');
    
    $context->addKeywordGroup(array(
        'screen', 'pset', 'preset', 'line', 'circle', 'draw', 'view', 'window', 'pmap',
        'point', 'color', 'palette', 'paint', 'pcopy', 'cls'
        // get, put also here but in IO as well...
    ), 'draw', false, 'geshi_qbasic_url()');
    
    $context->addKeywordGroup(array(
        'on error goto', 'resume', 'err', 'erl', 'erdev', 'erdev$', 'error', 'on', 'off',
        'stop'
    ), 'error', false, 'geshi_qbasic_url()');
    
    $context->addSymbolGroup(array(
        '(', ')', ',', ':', ';', '=', '<', '>', '+', '*', '/', '-'
    ), 'symbol');
    
    $context->useStandardIntegers();
    $context->useStandardDoubles();
}

function geshi_qbasic_qbasic_string (&$context)
{
    $context->addDelimiters('"', '"');
    //$this->_contextStyleType = GESHI_STYLE_STRINGS;
}

function geshi_qbasic_qbasic_comment (&$context)
{
    $context->addDelimiters(array("'", 'REM'), "\n");
    //$this->_contextStyleType = GESHI_STYLE_COMMENTS;
    $context->parseDelimiters(GESHI_CHILD_PARSE_LEFT);
}

function geshi_qbasic_url ($keyword)
{
    return 'http://qboho.qbasicnews.com/qboho/qck' . strtolower($keyword) . '.html';
}

/**#@-*/

?>