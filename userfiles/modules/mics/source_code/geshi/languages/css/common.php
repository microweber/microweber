<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/languages/css/common.php
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

function geshi_css_common (&$context)
{
    $context->addChild('css/css/rule', 'code');
    $context->addChild('css/css/comment');
    $context->addChild('css/css/attribute_selector');
    $context->addChild('css/css/at_rule', 'code');
    
    // Psuedoclasses
    $context->addKeywordGroup(array(
        'hover', 'link', 'visited', 'active', 'focus', 'first-child', 'first-letter',
        'first-line', 'before', 'after'
    ), 'css/css/psuedoclass');
    
    // Symbols
    $context->addSymbolGroup(array(
            ',', '*', '>', '+', ':'
    ), 'css/css/symbol');
    
    // CSS classes
    $context->addRegexGroup('#(\.)([a-zA-Z][a-zA-Z0-9\-_]*)#', '.', array(
        1 => array('css/css/symbol', false),
        2 => array('css/css/class', false)
    ));
    // CSS IDs
    $context->addRegexGroup('/(#)([a-zA-Z][a-zA-Z0-9\-_]*)/', '#', array(
            1 => array('css/css/symbol', false),
            2 => array('css/css/id', false)
    ));
}

/**#@-*/

?>
