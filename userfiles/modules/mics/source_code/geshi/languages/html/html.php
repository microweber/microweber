<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/languages/html/html.php
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

function geshi_html_html (&$context)
{
    $context->addChild('doctype');
    $context->addChild('tag', 'code');
    $context->addChild('comment');
    //@todo [blocking 1.1.9] The <![CDATA[ was added to stop CSS jumping into attribute
    // selector context the moment it was encountered, but this only really applies to XML
    $context->addChildLanguage('css/css', 'REGEX#<style[^>]+>\s*(<!\[CDATA\[)?#i', '</style>');
    $context->addChildLanguage('javascript/javascript', 'REGEX#<script[^>]+>#i', '</script>');

    $context->addRegexGroup('#(&(([a-z0-9]{2,5})|(\#[0-9]{2,4}));)#', '&', array(
            1 => array('entity', false)
        )
    );
}

function geshi_html_html_doctype (&$context)
{
    $context->addDelimiters('<!DOCTYPE ', '>');
    $context->addChild('html/html/string');
}

function geshi_html_html_tag (&$context)
{
    $context->addDelimiters('REGEX#<[/a-z_0-6]+#i', '>');
    $context->addChild('html/html/string');

    // Attributes
    $context->addKeywordGroup(array(
            'abbr', 'accept-charset', 'accept', 'accesskey', 'action', 'align',
            'alink', 'alt', 'archive', 'axis', 'background', 'bgcolor', 'border',
            'cellpadding', 'cellspacing', 'char', 'charoff', 'charset', 'checked',
            'cite', 'class', 'classid', 'clear', 'code', 'codebase', 'codetype',
            'color', 'cols', 'colspan', 'compact', 'content', 'coords', 'data',
            'datetime', 'declare', 'defer', 'dir', 'disabled', 'enctype', 'face',
            'for', 'frame', 'frameborder', 'headers', 'height', 'href', 'hreflang',
            'hspace', 'http-equiv', 'id', 'ismap', 'label', 'lang', 'language',
            'link', 'longdesc', 'marginheight', 'marginwidth', 'maxlength', 'media',
            'method', 'multiple', 'name', 'nohref', 'noresize', 'noshade', 'nowrap',
            'object', 'onblur', 'onchange', 'onclick', 'ondblclick', 'onfocus',
            'onkeydown', 'onkeypress', 'onkeyup', 'onload', 'onmousedown',
            'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onreset',
            'onselect', 'onsubmit', 'onunload', 'profile', 'prompt', 'readonly',
            'rel', 'rev', 'rows', 'rowspan', 'rules', 'scheme', 'scope', 'scrolling',
            'selected', 'shape', 'size', 'span', 'src', 'standby', 'start', 'style',
            'summary', 'tabindex', 'target', 'text', 'title', 'type', 'usemap',
            'valign', 'value', 'valuetype', 'version', 'vlink', 'vspace', 'width'
        ), 'attribute'
    );
    
    $context->addSymbolGroup(array('=', '/'), 'symbol');
}

function geshi_html_html_string (&$context)
{
    $context->addDelimiters("'", "'");
    $context->addDelimiters('"', '"');
    // NOTE: need to support _neverTrim
    $context->addChildLanguage('javascript/javascript', array('javascript:', 'return'),
        array('"', "'"), false, GESHI_CHILD_PARSE_LEFT);
    //$this->_contextStyleType = GESHI_STYLE_STRINGS;
}

function geshi_html_html_comment (&$context)
{
    $context->addDelimiters('<!--', '-->');
    //$this->_contextStyleType = GESHI_STYLE_COMMENTS;
}

/**#@-*/

?>
