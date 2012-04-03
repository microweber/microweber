<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/languages/php/class.geshiphpcodeparser.php
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

/**
 * The GeSHiPHPCodeParser class.
 * 
 * This class utilises the known information from basic parsing to provide highlighting of:
 * 
 * <ul>
 *  <li>Constants defined using <kbd>defined</kbd></li>
 *  <li>User-defined classes, even when only referred to and not defined in the source</li>
 *  <li>Function and method names, including function names where they are called</li>
 *  <li>PHPDoc to a higher degree than the basic parsing</li>
 * </ul>
 * 
 * @package    geshi
 * @subpackage lang
 * @author     Nigel McNie <nigel@geshi.org>
 * @since      1.1.1
 * @version    1.1.2alpha3
 */
class GeSHiPHPCodeParser extends GeSHiCodeParser
{
    
    // {{{ properties
    
    /**#@+
     * @access private
     */
    
    /**
     * A flag that can be used for the "state" of parsing
     * 
     * @var string
     */
    var $_state = '';
    
    /**
     * A list of class names detected in the source
     * 
     * @var array
     */
    var $_classNames = array();
    
    /**
     * A list of constant names detected in the source
     * 
     * @var array
     */
    var $_constants = array();

    /**
     * A list of phpdoc tags that have content that the parser should highlight
     * 
     * @var array
     * {@internal Note that this list does not include valid tags that have _no_
     * content - e.g. static and some others}}
     */
    var $_validPHPDocTags = array(
        'access', 'author', 'copyright', 'license', 'link', 'package', 'param',
        'return', 'revision', 'since', 'subpackage', 'var', 'version'
    );
    
    /**
     * Whether the current token is whitespace or not
     * 
     * @var boolean
     */
    var $_tokenIsWhitespace = false;
    
    /**#@-*/
    
    // }}}
    // {{{ parseToken()
    
    /**
     * @todo [blocking 1.1.2] possibly highlight methods differently? may not be worth effort
     * @todo [blocking 1.1.2] What about php5 public/private methods? Do they work?
     * @todo [blocking 1.1.2] Highlight phpdoc internal rule, and check for other rules
     */
    function parseToken ($token, $context_name, $data)
    {
        // Store this result for use by called methods
        $this->_tokenIsWhitespace = geshi_is_whitespace($token);
        
        // We don't care about whitespace for these methods
        if (!$this->_tokenIsWhitespace) {
            $this->_detectClassNames($token, $context_name, $data);
            $this->_detectConstants($token, $context_name, $data);
            $this->_detectFunctionNames($token, $context_name, $data);
            $this->_fixHeredoc($token, $context_name, $data);
        }

        // This method needs to know about whitespace tokens so it can find the end of
        // each line after a phpdoc tag
        $this->_handlePHPDoc($token, $context_name, $data);
        
        // Some detections require special handling because they use the stack
        $result = $this->_handleStackParsing($token, $context_name, $data);
        
        return $result;
    }
    
    // }}}
    
    /**#@+
     * @access private
     */
    
    // {{{ _detectClassNames()
    
    /**
     * Detects class names in the source.
     * 
     * The only class names not detected are static class names that are not referred to by
     * a class definition beforehand. These are detected using the stack (by looking for
     * tokens before the :: operator).
     * 
     * @param string $token        The source token 
     * @param string $context_name The context of the source token
     * @param array  $data         Additional data
     */
    function _detectClassNames (&$token, &$context_name, &$data)
    {
        if ('class' == $this->_state && !$this->_tokenIsWhitespace) {
            // We just read the keyword "class", so this token is a keyword, unless it
            // is a variable (PHP allows new $class_name)
            if ('var' != substr($context_name, -3)) {
                $context_name = $this->_language . '/classname';
                if (!in_array($token, $this->_classNames)) {
                    $this->_classNames[] = $token;
                }
            }
            $this->_state = '';
        } elseif (('class' == $token || 'extends' == $token || 'new' == $token)
            && $this->_language . '/keyword' == $context_name) {
            // We are about to read a class name
            $this->_state = 'class';
        } elseif (in_array($token, $this->_classNames) && $this->_language == $context_name) {
            // Detected use of class name we have already detected
            $context_name .= '/classname';
        }
    }
    
    // }}}
    // {{{ _detectConstants()
    
    /**
     * Detects user-defined constants in the source.
     * 
     * This will be fooled by:
     * <code> define('FO' . 'O', 'bar');
     * // or even...
     * define($foo, 'bar');</code>
     * Not much that can be done about this...
     * 
     * @param string $token        The source token 
     * @param string $context_name The context of the source token
     * @param array  $data         Additional data
     */
    function _detectConstants (&$token, &$context_name, &$data)
    {
        if ('define' == $this->_state && false !== strpos($context_name, 'string')
            && false === strpos($context_name, 'start')) {
            // We are in the define state and have found the constant name
            if (!in_array($token, $this->_constants)) {
                $this->_constants[] = $token;
            }
            $this->_state = '';
        } elseif ('define' == $this->_state && '(' != $token
            && $this->_language . '/symbol' == $context_name) {
            // Make sure we reset the state if the constant could not be found
            $this->_state = '';
        } elseif ('define' == $token && $this->_language . '/function' == $context_name) {
            // We found a "define" function call, so be ready to get the constant name
            $this->_state = 'define';
        } elseif (in_array($token, $this->_constants) && $this->_language == $context_name) {
            // Found a constant that we have already found
            $context_name .= '/definedconstant';
        }
    }
    
    // }}}
    // {{{ _handlePHPDoc()
    
    /**
     * Improves the standard phpdoc highlighting to highlight stuff on the line after tags.
     * 
     * @param string $token        The source token 
     * @param string $context_name The context of the source token
     * @param array  $data         Additional data
     */
    function _handlePHPDoc (&$token, &$context_name, &$data)
    {
        static $phpdoc_state = '';
        static $param_flag = false;
        
        if ($this->_state == 'phpdoc') {
            if ($context_name == $this->_language . '/phpdoc_comment/end') {
                $this->_state = '';
                return;
            }
            
            if ($context_name == $this->_language . '/phpdoc_comment/tag') {
                $phpdoc_state = $token;
                return;
            }
            
            if ($phpdoc_state) {
                if (!$this->_tokenIsWhitespace) {
                    if (in_array($phpdoc_state, $this->_validPHPDocTags)
                        && $context_name == $this->_language . '/phpdoc_comment') {
                        $context_name .= '/tagvalue';
                    }
                    switch ($phpdoc_state) {
                        case 'access': // one of public/private/protected, could check it
                        case 'package':
                        case 'return': // note: return requires a type after it (can't detect
                        // because types could be int|false for example. Just note this
                        case 'since':
                        case 'subpackage':
                        case 'var': // requires a type (can't be detected for same reason)
                            // For all of those cases, only one token necessary afterwards
                            $phpdoc_state = '';
                            break;
                        case 'author':
                        case 'copyright':
                        case 'revision':
                        case 'version':
                            // The whole line is all good
                            break;
                        case 'param': // should be a type
                            if (!$param_flag) {
                                $param_flag = true;
                            } elseif ($param_flag) {
                                if ('$' == substr($token, 0, 1)) {
                                    // Found a variable name after the type
                                    $context_name = $this->_language . '/phpdoc_comment/var';
                                } else {
                                    // Reset context as it has been converted incorrectly
                                    $context_name = $this->_language . '/phpdoc_comment';
                                }   
                                $param_flag = false;
                                $phpdoc_state = '';
                            }
                            break;
                        case 'license':
                        case 'link':
                            if ('http://' == substr($token, 0, 7)) {
                                $data['url'] = $token;
                            }
                            break;
                        default:
                            // Unknown token
                            $phpdoc_state = '';
                    }
                } else {
                    if (false !== strpos($token, "\n")) {
                        $phpdoc_state = '';
                    }
                }
            }
            
            return;
        }
        if ($context_name == $this->_language . '/phpdoc_comment/start') {
            $this->_state = 'phpdoc';
        }
    }
    
    // }}}
    // {{{ _detectFunctionNames()
    
    /**
     * Detects functions and methods in the source.
     * 
     * @param string $token        The source token 
     * @param string $context_name The context of the source token
     * @param array  $data         Additional data
     */
    function _detectFunctionNames (&$token, &$context_name, &$data)
    {
        if ('function' == $this->_state) {
            // We just read the keyword "function", so this token is a function
            // name, unless it is a "&" followed by a function name.
            if ($context_name == $this->_language . '/symbol') {
                // Ignore the symbol
                return;
            }
            $context_name = $this->_language . '/functionname';
            $this->_state = '';
        } elseif (('function' == $token)
            && $this->_language . '/keyword' == $context_name) {
            // We are about to read a function name
            $this->_state = 'function';
        }
    }
    
    // }}}
    // {{{ _fixHeredoc()

    /**
     * Makes symbols in heredoc tokens appear as symbols.
     *
     * @param  string $token        The source token 
     * @param  string $context_name The context of the source token
     * @param  array  $data         Additional data
     */
    function _fixHeredoc (&$token, &$context_name, &$data)
    {
        if ($this->_language . '/heredoc/start' == $context_name) {
            if ('<<<' == substr($token, 0, 3)) {
                // This works because the '<<<' token will be pushed onto the
                // stack before the return result of this function.
                $token = substr($token, 3);
                $this->push('<<<', $this->_language . '/symbol', $data);
            }
        }
        if ($this->_language . '/heredoc/end' == $context_name) {
            // As per the regex for heredoc enders, the last character will
            // be a newline, which makes the second to last character the
            // optional semi-colon.
            if (substr($token, -2, 1) == ';') {
                $this->push(substr($token, 0, -2), $this->_language . '/heredoc/end', $data);
                $token = ";\n";
                $context_name = $this->_language . '/symbol';
            }
        }
    }

    // }}}
    // {{{ _handleStackParsing()
    
    /**
     * Handles any parsing that uses the stack.
     * 
     * The stack is used to find function calls (note: different to function definitions)
     * and also to find static classes that haven't otherwise been detected.
     * 
     * @param  string $token        The source token 
     * @param  string $context_name The context of the source token
     * @param  array  $data         Additional data
     * @return mixed                As for the return value of {@link parseToken()}
     * @todo [blocking 1.1.5] this method could take a fourth parameter to say whether to
     * detect just function calls, classnames or both, when it comes to configuring what
     * the code parser actually highlights.
     */
    function _handleStackParsing (&$token, &$context_name, &$data)
    {
        if (!$this->_stack) {
            if ($this->_language == $context_name && !$this->_tokenIsWhitespace) {
                $this->push($token, $context_name, $data);
                return false;
            }
            
            // Some other random token that we don't care about
            return array($token, $context_name, $data);
        } else {
            if ($this->_language . '/symbol' == $context_name) {
                // Worth pointing out: the object splitter is :: and is forced to be
                // in the symbol context so that's why we check for :: instead of :.
                // Incidentally, that's actually quite a nice side effect of OO support.
                if ('(' == $token || '::' == $token) {
                    // Change the last non-whitespace token to be a user function or static
                    // class as depending on $token
                    for ($i = count($this->_stack) - 1; $i >= 0; $i--) {
                        // If the token is not whitespace, we have found the one place where
                        // a function call could be
                        if (!geshi_is_whitespace($this->_stack[$i][0])) {
                            // If the token is a bareword then we convert it to a function call
                            if ($this->_language == $this->_stack[$i][1]) {
                                $this->_stack[$i][1] = $this->_stack[$i][1]
                                    . (('(' == $token) ? '/functioncall' : '/classname');
                                // Add the token to the list of class names if it is one
                                if ('::' == $token
                                    && !in_array($this->_stack[$i][1], $this->_classNames)) {
                                    $this->_classNames[] = $this->_stack[$i][1];
                                } 
                            }
                            break;
                        }
                    }
                }
                
                // Add the symbol onto the stack and return
                return $this->flush($token, $context_name, $data);
            } else {
                // Store this token (either whitespace or a & symbol) on our mini-stack
                $this->push($token, $context_name, $data);
                return false;
            }
        }
    }
    
    // }}}
    
    /**#@-*/
    
}

?>
