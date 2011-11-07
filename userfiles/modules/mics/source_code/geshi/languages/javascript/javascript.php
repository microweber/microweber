<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/classes/class.geshicodecontext.php
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

function geshi_javascript_javascript (&$context)
{
    $context->addChild('single_comment');
    $context->addChild('multi_comment');
    $context->addChild('single_string', 'string');
    $context->addChild('double_string', 'string');

    // Keywords
    $context->addKeywordGroup(array(
        'break', 'case', 'catch', 'const', 'continue', 'default', 'delete', 'do',
        'else', 'false', 'finally', 'for', 'function', 'if', 'in', 'new', 'null',
        'return', 'switch', 'throw', 'true', 'try', 'typeof', 'var', 'void',
        'while', 'with'
    ), 'keyword', true);
    
    // Functions
    $context->addKeywordGroup(array(
        'escape', 'isFinite', 'isNaN', 'Number', 'parseFloat', 'parseInt',
        'reload', 'taint', 'unescape', 'untaint', 'write'
    ), 'function', true);
    
    // Objects
    $context->addKeywordGroup(array(
        'Anchor', 'Applet', 'Area', 'Array', 'Boolean', 'Button', 'Checkbox',
        'Date', 'document', 'window', 'Image', 'FileUpload', 'Form', 'Frame',
        'Function', 'Hidden', 'Link', 'MimeType', 'Math', 'Max', 'Min', 'Layer',
        'navigator', 'Object', 'Password', 'Plugin', 'Radio', 'RegExp', 'Reset',
        'Screen', 'Select', 'String', 'Text', 'Textarea', 'this', 'Window'
    ), 'object', true);
    
    // Math constants/methods
    $context->addKeywordGroup(array(
        'abs', 'acos', 'asin', 'atan', 'atan2', 'ceil', 'cos', 'ctg', 'E', 'exp',
        'floor', 'LN2', 'LN10', 'log', 'LOG2E', 'LOG10E', 'PI', 'pow', 'round',
        'sin', 'sqrt', 'SQRT1_2', 'SQRT2', 'tan'
    ), 'math', true);
    
    // Events
    $context->addKeywordGroup(array(
        'onAbort', 'onBlur', 'onChange', 'onClick', 'onError', 'onFocus', 'onLoad',
        'onMouseOut', 'onMouseOver', 'onReset', 'onSelect', 'onSubmit', 'onUnload'
    ), 'event', true);
    
    // Methods
    $context->addKeywordGroup(array(
        'MAX_VALUE', 'MIN_VALUE', 'NEGATIVE_INFINITY', 'NaN', 'POSITIVE_INFINITY',
        'URL', 'UTC', 'above', 'action', 'alert', 'alinkColor', 'anchor',
        'anchors', 'appCodeNam', 'appName', 'appVersion', 'applets', 'apply',
        'argument', 'arguments', 'arity', 'availHeight', 'availWidth', 'back',
        'background', 'below', 'bgColor', 'big', 'blink', 'blur', 'bold',
        'border', 'call', 'caller', 'charAt', 'charCodeAt', 'checked',
        'clearInterval', 'clearTimeout', 'click', 'clip', 'close', 'closed',
        'colorDepth', 'compile', 'complete', 'confirm', 'constructor', 'cookie',
        'current', 'cursor', 'data', 'defaultChecked', 'defaultSelected',
        'defaultStatus', 'defaultValue', 'description', 'disableExternalCapture',
        'domain', 'elements', 'embeds', 'enableExternalCapture', 'enabledPlugin',
        'encoding', 'eval', 'exec', 'fgColor', 'filename', 'find', 'fixed',
        'focus', 'fontcolor', 'fontsize', 'form', 'formName', 'forms', 'forward',
        'frames', 'fromCharCode', 'getDate', 'getDay', 'getElementById',
        'getHours', 'getMiliseconds', 'getMinutes', 'getMonth', 'getSeconds',
        'getSelection', 'getTime', 'getTimezoneOffset', 'getUTCDate', 'getUTCDay',
        'getUTCFullYear', 'getUTCHours', 'getUTCMilliseconds', 'getUTCMinutes',
        'getUTCMonth', 'getUTCSeconds', 'getYear', 'global', 'go', 'hash',
        'height', 'history', 'home', 'host', 'hostname', 'href', 'hspace',
        'ignoreCase', 'images', 'index', 'indexOf', 'innerHeight', 'innerWidth',
        'input', 'italics', 'javaEnabled', 'join', 'language', 'lastIndex',
        'lastIndexOf', 'lastModified', 'lastParen', 'layerX', 'layerY', 'layers',
        'left', 'leftContext', 'length', 'link', 'linkColor', 'links', 'load',
        'location', 'locationbar', 'lowsrc', 'match', 'menubar', 'method',
        'mimeTypes', 'modifiers', 'moveAbove', 'moveBelow', 'moveBy', 'moveTo',
        'moveToAbsolute', 'multiline', 'name', 'negative_infinity', 'next',
        'open', 'opener', 'options', 'outerHeight', 'outerWidth', 'pageX',
        'pageXoffset', 'pageY', 'pageYoffset', 'parent', 'parse', 'pathname',
        'personalbar', 'pixelDepth', 'platform', 'plugins', 'pop', 'port',
        'positive_infinity', 'preference', 'previous', 'print', 'prompt',
        'protocol', 'prototype', 'push', 'referrer', 'refresh', 'releaseEvents',
        'reload', 'replace', 'reset', 'resizeBy', 'resizeTo', 'reverse',
        'rightContext', 'screenX', 'screenY', 'scroll', 'scrollBy', 'scrollTo',
        'scrollbar', 'search', 'select', 'selected', 'selectedIndex', 'self',
        'setDate', 'setHours', 'setMinutes', 'setMonth', 'setSeconds', 'setTime',
        'setTimeout', 'setUTCDate', 'setUTCDay', 'setUTCFullYear', 'setUTCHours',
        'setUTCMilliseconds', 'setUTCMinutes', 'setUTCMonth', 'setUTCSeconds',
        'setYear', 'shift', 'siblingAbove', 'siblingBelow', 'small', 'sort',
        'source', 'splice', 'split', 'src', 'status', 'statusbar', 'strike',
        'sub', 'submit', 'substr', 'substring', 'suffixes', 'sup', 'taintEnabled',
        'target', 'test', 'text', 'title', 'toGMTString', 'toLocaleString',
        'toLowerCase', 'toSource', 'toString', 'toUTCString', 'toUpperCase',
        'toolbar', 'top', 'type', 'unshift', 'unwatch', 'userAgent', 'value',
        'valueOf', 'visibility', 'vlinkColor', 'vspace', 'watch', 'which',
        'width', 'write', 'writeln', 'x', 'y', 'zIndex'
        //@todo [blocking 1.1.5] Some important and recent DOM additions for js seem to be ommited...
    ), 'method', true);

    $context->setCharactersDisallowedBeforeKeywords('$');

    // Symbols
    $context->addSymbolGroup(array(
        '(', ')', ',', ';', ':', '[', ']',
        '+', '-', '*', '/', '&', '|', '!', '<', '>',
        '{', '}', '='
    ), 'symbol');

    $context->useStandardIntegers();
    $context->useStandardDoubles();

    $context->addObjectSplitter('.', 'oodynamic', 'symbol', true);
}

function geshi_javascript_javascript_single_comment (&$context)
{
    $context->addDelimiters('//', "\n");
    //$this->_contextStyleType = GESHI_STYLE_COMMENTS;
}

function geshi_javascript_javascript_multi_comment (&$context)
{
    $context->addDelimiters('/*', '*/');
    //$this->_contextStyleType = GESHI_STYLE_COMMENTS;
}

function geshi_javascript_javascript_single_string (&$context)
{
    $context->addDelimiters("'", array("'", "\n"));
    $context->addEscapeGroup('\\', array('\\', "'", "\n"));
    //$context->setEscapeCharacters('\\');
    //$context->setCharactersToEscape(array('\\', "'", "\n"));
    //$this->_contextStyleType = GESHI_STYLE_STRINGS;
}

function geshi_javascript_javascript_double_string (&$context)
{
    $context->addDelimiters('"', '"');
    $context->addEscapeGroup('\\', array('n', 'r', 't', '\\', '"'));
    //$context->setEscapeCharacters('\\');
    //$context->setCharactersToEscape(array('n', 'r', 't', '\\', '"'));
    //$this->_contextStyleType = GESHI_STYLE_STRINGS;
}

/**#@-*/

?>