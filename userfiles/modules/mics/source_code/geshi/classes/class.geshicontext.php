<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/classes/class.geshicontext.php
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
 * The GeSHiContext class
 * 
 * @package    geshi
 * @subpackage core
 * @author     Nigel McNie <nigel@geshi.org>
 * @since      1.1.0
 * @version    1.1.2alpha3
 * @todo       [blocking 1.1.9] comment better
 */
class GeSHiContext
{
    
    // {{{ properties
    
    /**#@-
     * @access private
     */
    
    /**
     * The context name.
     *
     * @var string
     */
    var $_contextName;

    /**
     * The language that this context is in
     * 
     * @var string
     */
    var $_languageName = '';

    /**
     * The styler helper object
     * 
     * @var GeSHiStyler
     */
    var $_styler;
    
    /**
     * The context delimiters
     * 
     * @var array
     */
    var $_contextDelimiters = array();
    
    /**
     * The child contexts
     * 
     * @var array
     */
    var $_childContexts = array();
    
    /**
     * The style type of this context, used for backward compatibility
     * with GeSHi 1.0.X
     * 
     * @var int
     */
    var $_contextStyleType = GESHI_STYLE_NONE;
    
    /**
     * Delimiter parse data. Controls which context - the parent or child -
     * should parse the delimiters for a context
     * 
     * @var int
     */
    var $_delimiterParseData = GESHI_CHILD_PARSE_BOTH;
    
    /**
     * The matching regex table for regex starters
     * 
     * @var array
     */
     var $_startRegexTable = array();
        
    /**
     * The name for stuff detected in the start of a context
     * 
     * @var string 
     */
    var $_startName = 'start';
    
    /**
     * The name for stuff detected in the end of a context
     * 
     * @var string 
     */
    var $_endName = 'end';

    /**
     * Whether this context is an alias context
     * 
     * @var boolean
     */
    var $_isAlias = false;
    
    /**
     * The name of the context if not aliased
     * @var string
     */
    //var $_aliasForContext = '';
    
    var $_aliasName = '';
    
    /**
     * Whether this context should never be trimmed
     * @var boolean
     */
    //var $_neverTrim = false;
    
    /**
     * Whether this context should be broken up by whitespace
     * for the code parser (GESHI_COMPLEX_* constants)
     * @var int
     */
    var $_complexFlag = GESHI_COMPLEX_NO;

    /**
     * Whether this context is a child context
     * 
     * @var boolean
     */
    var $_isChildLanguage = false;

    /**#@-*/

    // }}}
    // {{{ GeSHiContext()
    
    /**
     * Creates a new GeSHiContext.
     * 
     * @param string The name of the language this context represents
     * @param string An initialisation function
     * @todo [blocking 1.1.9] Better comment
     */
    function GeSHiContext ($context_name, $init_function = '')
    {
        $this->_contextName = $context_name;
        $this->_languageName = substr($context_name, 0,
            strpos($context_name, '/'));
        $this->_styler =& geshi_styler();
       
        // Try a list of functions that should be used to populate this context.
        $tried_functions = array();
        // First function to try is the user-defined one
        if ('' != $init_function) {
            $function =  'geshi_' . $this->_languageName . '_' . $init_function;
            if (function_exists($function)) {
                $function($this);
                $this->_initPostProcess();
                return;
            }
            $tried_functions[] = $function;
        }
        
        // Next choice is the full context name function
        $function = 'geshi_' . str_replace('/', '_', $context_name);
        if (function_exists($function)) {
            $function($this);
            $this->_initPostProcess();
            return;
        }
        $tried_functions[] = $function;

        // Next is the dialect shortcut function
        $function = 'geshi'  . str_replace('/', '_', substr($context_name,
            strpos($context_name, '/')));
        if (function_exists($function)) {
            $function($this);
            $this->_initPostProcess();
            return;
        }
        $tried_functions[] = $function;

        // Final chance is the language shortcut function
        $root_language_name = "$this->_languageName/$this->_languageName";
        if ($context_name != $root_language_name && "$root_language_name/" !=
            substr($context_name, 0, strlen($root_language_name) + 1)) {
            $function = 'geshi_' . str_replace('/', '_', $this->_languageName
                . substr($context_name, strpos($context_name, '/',
                strpos($context_name, '/') + 1)));
            if (function_exists($function)) {
                $function($this);
                $this->_initPostProcess();
                return;
            }
            $tried_functions[] = $function;
        }
        
        // If we are still inside this constructor then none of the functions
        // we have tried have been available to call. Time to raise an error.
        // This will in general only ever happen to developers building new
        // language files, so we can afford to take our time and build a nice
        // error message to help them debug it.
        //
        // If PHP version is greater that 4.3.0 then debug_backtrace
        // can give us a nice output of the error that occurs. This
        // code shamelessly ripped from libheart, which got it from
        // a comment on the php.net manual.
        if (function_exists('debug_backtrace')) {
            $backtrace = debug_backtrace();
            $calls = array();
            $backtrace_output = "<pre><strong>Call stack (most recent first):</strong>\n<ul>";

            foreach ($backtrace as $bt) {
                // Set some defaults for debug values
                $bt['file']  = (isset($bt['file']) ? $bt['file'] : 'Unknown');
                $bt['line']  = (isset($bt['line']) ? $bt['line'] : 0);
                $bt['class'] = (isset($bt['class']) ? $bt['class'] : '');
                $bt['type']  = (isset($bt['type']) ? $bt['type'] : '');
                $bt['args']  = (isset($bt['args']) ? $bt['args'] : array());
    
                $args = '';
                foreach ($bt['args'] as $arg) {
                    if (!empty($args)) {
                        $args .= ', ';
                    }
                    switch (gettype($arg)) {
                        case 'integer':
                        case 'double':
                            $args .= $arg;
                        break;
                        case 'string':
                            $arg = substr($arg, 0, 64) . ((strlen($arg) > 64) ? '...' : '');
                            $args .= '"' . $arg . '"';
                            break;
                        case 'array':
                            $args .= 'array(' . count($arg) . ')';
                            break;
                        case 'object':
                            $args .= 'object(' . get_class($arg) . ')';
                            break;
                        case 'resource':
                            $args .= 'resource(' . strstr($arg, '#') . ')';
                            break;
                        case 'boolean':
                            $args .= $arg ? 'true' : 'false';
                            break;
                        case 'NULL':
                            $args .= 'null';
                            break;
                        default:
                            $args .= 'unknown';
                    }
                }

                // Build a new entry for the output.
                $backtrace_output .= '<li>' . htmlspecialchars($bt['class'])
                    . '' . htmlspecialchars($bt['type']) . ''
                    . '' . htmlspecialchars($bt['function']) . ''
                    . '(' . htmlspecialchars($args)
                    . ') at ' . htmlspecialchars($bt['file'])
                    . ':' . $bt['line'] . "</li>";
            }
            $backtrace_output .= '</ul></pre>';
        } else {
            $backtrace_output = '[No backtrace available - debug_backtrace() '
                . 'not available]';
        }
        trigger_error("Could not find function for context $context_name\n"
            . 'looked for ' . implode(', ', $tried_functions) . "\n"
            . $backtrace_output, E_USER_ERROR);
    }
    
    // }}}
    // {{{ name()
    /**
     * Returns the name of this context
     * 
     * @return string The full name of this context (language, dialect and context)
     */
    function name ()
    {
        return $this->_contextName;
    }
    
    // }}}
    // {{{ getStartName()
    
    /**
     * Returns the name given to the part of this context that is
     * matched by the starting context delimiter. Typically this is
     * used by aliased contexts to lie about what they really start
     * with.
     * 
     * @return string
     */
    function getStartName ()
    {
        return $this->_startName;
    }
    
    // }}}
    // {{{ getEndName()
    
    /**
     * Returns the name given to the part of this context that is
     * matched by the ending context delimiter. Typically this is
     * used by aliased contexts to lie about what they really end
     * with.
     * 
     * @return string
     */
    function getEndName ()
    {
        return $this->_endName;
    }
    
    // }}}
    // {{{ isAlias()
    
    /**
     * Returns whether this context is an aliased context
     * 
     * @return boolean
     */
    
    function isAlias ()
    {
        return $this->_isAlias;
    }
    
    // }}}
    // {{{ embedInside()
    
    /**
     * Embeds this context inside the named context.
     * 
     * This is used to embed "sublanguages" - for example, PHP inside HTML
     * 
     * @param  string $name The name of the context to embed this context inside
     * @return GeSHiContext The context that this context was embedded inside
     */
    function embedInside ($name)
    {
        // Perform any post processing now because we are about to override
        // ourselves.
        $this->_initPostProcess();
        
        // @todo note this is also GeSHi::_getLanguageDataFile
        if ('/' == GESHI_DIR_SEP) {
            $language_file = $name . '.php';
        } else {
            $language_file = explode('/', $name);
            $language_file = implode(GESHI_DIR_SEP, $language_file) . '.php';
        }
        
        /** Get the appropriate functions for the language we are embedding inside */
        require_once GESHI_LANGUAGES_ROOT . $language_file;
        
        $context =& new GeSHiCodeContext($name);
        
        // @todo I don't like this... it assumes we are not passing an object
        // by reference (if we do things break horribly), and so therefore
        // may not be PHP5 compliant
        $context->addEmbeddedChild($this);
        
        return $context;
    }

    // }}}
    // {{{ addEmbeddedChild()
    
    /**
     * @todo this isn't really "public" - language file developers should not care about
     * this method
     */
    function addEmbeddedChild ($context)
    {
        foreach (array_keys($this->_childContexts) as $key) {
            $this->_childContexts[$key]->addEmbeddedChild($context);
        }
        $this->_childContexts[] = $context;
    }
    
    // }}}
    // {{{ addChild()
    
    /**
     * Adds a child context to this context.
     * 
     * @param string $name The name of the child context to add. The function geshi_[lang]_[dialect]_$name must
     *                     be defined to populate the context with information. Alternatively, if $name starts
     *                     with the language name the function geshi_$name will be used instead.
     * @param string $type An optional type of context for the child. The class <kbd>GeSHi[$type]Context</kbd>
     *                     will be used for the context. A commonn value would be <kbd>'string'</kbd>.
     * @param string $init_function
     * @since 1.1.1
     */
    function addChild ($name, $type = '', $init_function = '')
    {
        $classname = 'geshi' . $type . 'context';
        $this->_childContexts[] =& new $classname($this->_makeContextName($name), $init_function);
    }
    
    // }}}
    // {{{ addChildLanguage()
    
    /**
     * Adds a child to this context that is actually a language
     * 
     * e.g. doxygen within PHP
     */
    function addChildLanguage ($name, $start_delimiters, $end_delimiters, $case_sensitive = false,
        $parse_delimiter_flag = GESHI_CHILD_PARSE_NONE)
    {
        /** Get function info for the child language */
        require_once GESHI_LANGUAGES_ROOT . $name . '.php';
        $context =& new GeSHiCodeContext($name);
        $context->addDelimiters($start_delimiters, $end_delimiters, $case_sensitive);
        $context->parseDelimiters($parse_delimiter_flag);
        // @todo setter
        $context->_isChildLanguage = true;
        $this->_childContexts[] =& $context;
    }

    // }}}
    // {{{ addDelimiters()
    
    /**
     * Sets the delimiters for this context
     */
    function addDelimiters ($start, $end, $case_sensitive = false)
    {
        $this->_contextDelimiters[] = array((array) $start, (array) $end, $case_sensitive);
    } 
    
    // }}}
    // {{{ setComplexFlag()
    
    function setComplexFlag ($flag)
    {
        $this->_complexFlag = $flag;
    }

    // }}}
    // {{{ parseDelimiters()
        
    function parseDelimiters ($flag)
    {
        $this->_delimiterParseData = $flag;
    }
    
    // }}}
    // {{{ alias()
    
    function alias ($name)
    {
        $this->_contextName = ('' == $name) ? "$this->_languageName/$name" : $name;
        $this->_isAlias = true;
    }

    // }}}    
    // {{{ trimUselessChildren()
    
    /**
     * Checks each child to see if it's useful. If not, then remove it
     * 
     * @param string The code that can be used to check if a context
     *               is needed.
     */
    function trimUselessChildren ($source)
    {
        //geshi_dbg('GeSHiContext::trimUselessChildren()', GESHI_DBG_INFO + GESHI_DBG_LOADING);
        $new_children = array();
        foreach (array_keys($this->_childContexts) as $key) {
            //geshi_dbg('  Checking child: ' . $this->_childContexts[$key]->getName() . ': ',
            //    GESHI_DBG_DEBUG + GESHI_DBG_LOADING, false);
            if (!$this->_childContexts[$key]->contextCanStart($source)) {
                // This context will _never_ be useful - and nor will its children
                //geshi_dbg('@buseless, removed', GESHI_DBG_DEBUG + GESHI_DBG_LOADING);
                // RAM saving technique
                // But we shouldn't remove highlight data if the child is an
                // "alias" context, since the real context might need the data
                if (!$this->_childContexts[$key]->isAlias()) {
                    $this->_styler->removeStyleData($this->_childContexts[$key]->/*getName*/name(),
                        $this->_childContexts[$key]->getStartName(),
                        $this->_childContexts[$key]->getEndName());
                }
                unset($this->_childContexts[$key]);
            }
        }
        
        // Recurse into the remaining children, checking them
        foreach (array_keys($this->_childContexts) as $key) {
            $this->_childContexts[$key]->trimUselessChildren($source);
        }
    }
    
    // }}}
    // {{{ parseCode()        
    
    /**
     * Parses the given code
     */
    function parseCode (&$code, $context_start_key = -1, $context_start_delimiter = '', $ignore_context = '',
        $first_char_of_next_context = '')
    {
        geshi_dbg('*** GeSHiContext::parseCode(' . $this->_contextName . ') ***');
        geshi_dbg('CODE: ' . str_replace("\n", "\r", substr($code, 0, 100)) . "<<<<<\n");
        if ($context_start_delimiter) geshi_dbg('Delimiter: ' . $context_start_delimiter);
        // Skip empty/almost empty contexts
        if ('' == $code || geshi_is_whitespace($code)) {
            $this->_addParseData($code);
            return;
        }
                
        // Add the start of this context to the parse data if it is already known
        // NOTE: related to bug 75: if remove childLanguage check, then the
        // start delimiter is marked as lang/dialect/start instead of whatever the
        // language would have marked it as.
        // This means that, for example with doxygen, beginning
        // doxygen within java means that the doxygen starter
        // is parsed as doxygen code. I guess that is reasonable
        // and the intended thing for GESHI_CHILD_PARSE_LEFT/BOTH
        //
        // NOTE: say we use GESHI_CHILD_PARSE_RIGHT for doxygen delimiter.
        // Then the left delimiter will be parsed as java/java/multi_comment_start
        // then the doxygen, then the ender for doxygen. But the multi_comment
        // will end immediately. I don't think this is a bug, it's more of a caveat.
        // I think this happens for embedded languages also.
        if ($context_start_delimiter && !$this->_isChildLanguage) {
            $this->_addParseDataStart($context_start_delimiter);
            $code = substr($code, strlen($context_start_delimiter));
        }
        
        $original_length = strlen($code);
        
        while ('' != $code) {
            if (strlen($code) != $original_length) {
                geshi_dbg('CODE: ' . str_replace("\n", "\r", substr($code, 0, 100)) . "<<<<<\n");
            }
            // Second parameter: if we are at the start of the context or not
            // Pass the ignored context so it can be properly ignored
            $earliest_context_data = $this->_getEarliestContextData($code, strlen($code) == $original_length,
                $ignore_context);
            $finish_data = $this->_getContextEndData($code, $context_start_key, $context_start_delimiter,
                strlen($code) == $original_length);
            geshi_dbg('@bEarliest context data: pos=' . $earliest_context_data['pos'] . ', len=' .
                $earliest_context_data['len']);
            geshi_dbg('@bFinish data: pos=' . $finish_data['pos'] . ', len=' . $finish_data['len']);
            
            // If there is earliest context data we parse up to it then hand control to that context
            if ($earliest_context_data) {
                if ($finish_data) {
                    // Merge to work out who wins
                    if ($finish_data['pos'] <= $earliest_context_data['pos']) {
                        geshi_dbg('Earliest context and Finish data: finish is closer');
                        
                        if ($this->shouldParseEnder() && $this->_isChildLanguage) {
                            $finish_data['pos'] += $finish_data['len'];
                        }
                        
                        // Add the parse data
                        $this->_addParseData(substr($code, 0, $finish_data['pos']), substr($code, $finish_data['pos'], 1));
                        
                        // If we should pass the ender, add the parse data
                        if ($this->shouldParseEnder() && !$this->_isChildLanguage) {
                        	$this->_addParseDataEnd(substr($code, $finish_data['pos'], $finish_data['len']));
                        	$finish_data['pos'] += $finish_data['len'];
                        }
                        // Trim the code and return the unparsed delimiter
                        $code = substr($code, $finish_data['pos']);
                        return $finish_data['dlm'];
                    } else {
                        geshi_dbg('Earliest and finish data, but earliest gets priority');
                        $foo = true;
                    }
                } else { $foo = true; /** no finish data */}

                if (isset($foo)) geshi_dbg('Earliest data but not finish data');
                // Highlight up to delimiter
                ///The "+ len" can be manipulated to do starter and ender data
                if (!$earliest_context_data['con']->shouldParseStarter()) {
                     $earliest_context_data['pos'] += $earliest_context_data['len'];
                     //BUGFIX: null out dlm so it doesn't squash the actual rest of context
                     $earliest_context_data['dlm'] = '';
                }
                                
                // We should parseCode() the substring.
                // BUT we have to remember that we should ignore the child context we've matched,
                // else we'll have a wee recursion problem on our hands...
                $tmp = substr($code, 0, $earliest_context_data['pos']);
                $this->parseCode($tmp, -1, '', $earliest_context_data['con']->/*getName*/name(),
                    substr($code, $earliest_context_data['pos'], 1)); // parse with no starter
                $code = substr($code, $earliest_context_data['pos']);
                $ender = $earliest_context_data['con']->parseCode($code, $earliest_context_data['key'], $earliest_context_data['dlm']);
                // check that the earliest context actually wants the ender
                if (!$earliest_context_data['con']->shouldParseEnder() && $earliest_context_data['dlm'] == $ender) {
                	geshi_dbg('earliest_context_data[dlm]=' . $earliest_context_data['dlm'] . ', ender=' . $ender);
                    // second param = first char of next context
                    $this->_addParseData(substr($code, 0, strlen($ender)), substr($code, strlen($ender), 1));
                    $code = substr($code, strlen($ender));
                }
            } else {
                if ($finish_data) {
                    // finish early...
                    geshi_dbg('No earliest data but finish data');

                    if ($this->shouldParseEnder() && $this->_isChildLanguage) {
                        $finish_data['pos'] += $finish_data['len'];
                    }
                    // second param = first char of next context
                    $this->_addParseData(substr($code, 0, $finish_data['pos']), substr($code, $finish_data['pos'], 1));
                    
                    if ($this->shouldParseEnder() && !$this->_isChildLanguage) {
                       	$this->_addParseDataEnd(substr($code, $finish_data['pos'], $finish_data['len']));
                       	$finish_data['pos'] += $finish_data['len'];
                    }
                    $code = substr($code, $finish_data['pos']);
                    // return the length for use above
                    return $finish_data['dlm'];
                } else {
                    geshi_dbg('No earliest or finish data');
                    // All remaining code is in this context
                    $this->_addParseData($code, $first_char_of_next_context);
                    $code = '';
                    return; // not really needed (?)
                }
            }
        }
    }
    
    // }}}
    // {{{ shouldParseStarter()

    /**
     * @return true if this context wants to parse its start delimiters
     */    
    function shouldParseStarter()
    {
        return $this->_delimiterParseData & GESHI_CHILD_PARSE_LEFT;
    }
    
    // }}}
    // {{{ shouldParseEnder()
    
    /**
     * @return true if this context wants to parse its end delimiters
     */
    function shouldParseEnder ()
    {
        return $this->_delimiterParseData & GESHI_CHILD_PARSE_RIGHT;
    }
    
    // }}}
    // {{{ contextCanStart()

    /**
     * Return true if it is possible for this context to parse this code at all
     */    
    function contextCanStart ($code)
    {
        //if ($this->_neverTrim) {
        //    return true;
        //}
        foreach ($this->_contextDelimiters as $key => $delim_array) {
            foreach ($delim_array[0] as $delimiter) {
                //geshi_dbg('    Checking delimiter ' . $delimiter . '... ', GESHI_DBG_INFO + GESHI_DBG_LOADING, false);
                $data     = geshi_get_position($code, $delimiter, 0, $delim_array[2]);
                
                if (false !== $data['pos']) {
                    return true;
                }
            }
        }
        return false;
    }
    
    // }}}
    // {{{ _getEarliestContextData()

    /**
     * Works out the closest child context
     * 
     * @param $ignore_context The context to ignore (if there is one)
     */
    function _getEarliestContextData ($code, $start_of_context, $ignore_context)
    {
        geshi_dbg('  GeSHiContext::_getEarliestContextData(' . $this->_contextName . ', '. $start_of_context . ')');
        $earliest_pos = false;
        $earliest_len = false;
        $earliest_con = null;
        $earliest_key = -1;
        $earliest_dlm = '';
        
        foreach ($this->_childContexts as $context) {
            if ($ignore_context == $context->/*getName*/name()) {
                // whups, ignore you...
                continue;
            }
            $data = $context->getContextStartData($code, $start_of_context);
            geshi_dbg('  ' . $context->_contextName . ' says it can start from ' . $data['pos'], false);
            
            if (-1 != $data['pos']) {
                if ((false === $earliest_pos) || $earliest_pos > $data['pos'] ||
                   ($earliest_pos == $data['pos'] && $earliest_len < $data['len'])) {
                    geshi_dbg(' which is the earliest position');
                    $earliest_pos = $data['pos'];
                    $earliest_len = $data['len'];
                    $earliest_con = $context;
                    $earliest_key = $data['key'];
                    $earliest_dlm = $data['dlm'];
                } else {
                    geshi_dbg('');
                }
            } else {
                geshi_dbg('');
            }
        }
        // What do we need to know?
        //   Well, assume that one of the child contexts can parse
        //   Then, parseCode() is going to call parseCode() recursively on that object
        //   
        if (false !== $earliest_pos) {
            return array('pos' => $earliest_pos, 'len' => $earliest_len, 'con' => $earliest_con, 'key' => $earliest_key, 'dlm' => $earliest_dlm);
        } else {
            return false;
        }
    }
    
    // }}}
    // {{{ getContextStartData()
    
    /**
     * Checks the context delimiters for this context against the passed
     * code to see if this context can help parse the code
     */
    function getContextStartData ($code, $start_of_context)
    {
        //geshi_dbg('    GeSHi::getContextStartInformation(' . $this->_contextName . ')',
        //    GESHI_DBG_INFO + GESHI_DBG_PARSING);
        geshi_dbg('  ' . $this->_contextName);

        $first_position = -1;
        $first_length   = -1;
        $first_key      = -1;
        $first_dlm      = '';
        
        foreach ($this->_contextDelimiters as $key => $delim_array) {
            foreach ($delim_array[0] as $delimiter) {
                geshi_dbg('    Checking delimiter ' . $delimiter . '... ', false);
                $data     = geshi_get_position($code, $delimiter, 0, $delim_array[2], true);
                geshi_dbg(print_r($data, true), false);
                $position = $data['pos'];
                $length   = $data['len'];
                if (isset($data['tab'])) {
                    geshi_dbg('Table: ' . print_r($data['tab'], true));
                }
                
                if (false !== $position) {
                    geshi_dbg('found at position ' . $position . ', checking... ', false);
                    if ((-1 == $first_position) || ($first_position > $position) ||
                       (($first_position == $position) && ($first_length < $length))) {
                        geshi_dbg('@bearliest! (length ' . $length . ')');
                        $first_position = $position;
                        $first_length   = $length;
                        $first_key      = $key;
                        if (isset($data['tab'])) {
                            $this->_startRegexTable = $data['tab'];
                            $delimiter = $data['tab'][0];
                        }
                        $first_dlm      = $delimiter;
                    }
                } else {
                    geshi_dbg('');
                }
            }
        }
        
        return array('pos' => $first_position, 'len' => $first_length,
                     'key' => $first_key, 'dlm' => $first_dlm);
    }
    
    // }}}
    // {{{ _getContextEndData()
    
    /**
     * GetContextEndData
     */
    function _getContextEndData ($code, $context_open_key, $context_opener, $beginning_of_context)
    {
        geshi_dbg('GeSHiContext::_getContextEndData(' . $this->_contextName . ', ' . $context_open_key . ', '
        	. $context_opener . ', ' . $beginning_of_context . ')');
        $context_end_pos = false;
        $context_end_len = -1;
        $context_end_dlm = '';
        $offset = 0;
        
        // Bail out if context open key tells us that there is no ender for this context
        if (-1 == $context_open_key) {
        	geshi_dbg('  no opener so no ender');
        	return false;
        }

        // Balanced endings is handled here
        if (isset($this->_contextDelimiters[$context_open_key][3])) {
            $balance_opener = $this->_contextDelimiters[$context_open_key][3][0];
            $balance_closer = $this->_contextDelimiters[$context_open_key][3][1];

            // We get the first push for free
            // @todo [blocking 1.1.2] if what we are balancing against is not related
            // to the starter of the context then we have a problem... check $context_opener
            // for starter stuff instead of assuming
            $balance_count = 1;
            geshi_dbg('@w  Begun balancing');
            
            while ($balance_count > 0) {
                // Look for opener/closers.
                $opener_pos = geshi_get_position($code, $balance_opener, $offset);
                $closer_pos = geshi_get_position($code, $balance_closer, $offset);
                geshi_dbg('  opener pos = ' . print_r($opener_pos,true) . ', closer pos = ' . print_r($closer_pos,true));
                
                // Check what we found
                if (false !== $opener_pos['pos']) {
                    if (false !== $closer_pos['pos']) {
                        // Opener and closer available
                        if ($opener_pos['pos'] < $closer_pos['pos']) {
                            // Opener is closer so inc. counter
                            ++$balance_count;
                            geshi_dbg('  opener is closer so inc. to ' . $balance_count);
                            // Start searching from new pos just past where we found the opener
                            $offset = $opener_pos['pos'] + 1;
                            // @todo [blocking 1.1.2] could cache closer pos at this point?
                        } else {
                            // closer is closer (bad english heh)
                            --$balance_count;
                            $offset = $closer_pos['pos'] + 1;
                            geshi_dbg('  closer is closer so dec. to ' . $balance_count);
                        }
                    } else {
                        // No closer will ever be available yet we are still in this context...
                        // use end of code as end pos
                        // I've yet to test this case
                        geshi_dbg('@w  No closer but still in this context!');
                        return array('pos' => strlen($code), 'len' => 0, 'dlm' => '');
                    }
                } elseif (false !== $closer_pos['pos']) {
                    // No opener but closer. Nothing wrong with this
                    --$balance_count;
                    $offset = $closer_pos['pos'] + 1;
                    geshi_dbg('  only closer left, dec. to ' . $balance_count);
                } else {
                    // No opener or closer
                    // Assume that we end this context at the end of the code, with
                    // no delimiter
                    geshi_dbg('@w  No opener or closer but still in this context!');
                    return array('pos' => strlen($code), 'len' => 0, 'dlm' => '');
                }
            }
            // start looking for real end from the position where balancing ends
            // because we've found where balancing ends, but the end of the balancing
            // is likely to be the same as the end of the context
            --$offset;
        }        
        
        foreach ($this->_contextDelimiters[$context_open_key][1] as $ender) {
            geshi_dbg('  Checking ender: ' . str_replace("\n", '\n', $ender), false);
            $ender = $this->_substitutePlaceholders($ender);
            geshi_dbg(' converted to ' . $ender);
            
            // Use the offset we may have found when handling balancing of contexts (will
            // be zero if balancing not done).
            $position = geshi_get_position($code, $ender, $offset);
            geshi_dbg('    Ender ' . $ender . ': ' . print_r($position, true));
            $length   = $position['len'];
            $position = $position['pos'];
            
            // BUGFIX:skip around crap starters
            if (false === $position) {
                continue;
            }
            
            if ((false === $context_end_pos) || ($position < $context_end_pos)
                || ($position == $context_end_pos && strlen($ender) > $context_end_len)) {
                $context_end_pos = $position;
                $context_end_len = $length;
                $context_end_dlm = $ender;
            }
        }
        geshi_dbg('Context ' . $this->_contextName . ' can finish at position ' . $context_end_pos);
        
        if (false !== $context_end_pos) {
            return array('pos' => $context_end_pos, 'len' => $context_end_len, 'dlm' => $context_end_dlm);
        } else {
            return false;
        }
    }
    
    // }}}
    // {{{ _addParseData()
    
    /**
     * Adds parse data to the overall result
     * 
     * This method is mainly designed so that subclasses of GeSHiContext can
     * override it to break the context up further - for example, GeSHiStringContext
     * uses it to add escape characters
     * 
     * @param string The code to add
     * @param string The first character of the next context (used by GeSHiCodeContext)
     */
    function _addParseData ($code, $first_char_of_next_context = '')
    {
       $this->_styler->addParseData($code, $this->_contextName, $this->_getExtraParseData(), $this->_complexFlag);
    }
    
    // }}}
    // {{{ _addParseDataStart()
    
    /**
     * Adds parse data for the start of a context to the overallresult
     */
    function _addParseDataStart ($code)
    {
        $this->_styler->addParseDataStart($code, $this->_contextName, $this->_startName, $this->_complexFlag);
    }
    
    // }}}
    // {{{ _addParseDataEnd()

    /**
     * Adds parse data for the end of a context to the overallresult
     */
    function _addParseDataEnd ($code)
    {
        $this->_styler->addParseDataEnd($code, $this->_contextName, $this->_endName, $this->_complexFlag);
    }
    
    // }}}
    // {{{ _getExtraParseData()
    
    // This may not be needed any more, since the context name can be changed immediately
    // because of one stage loading. This is used by the delphi code parser though, so be careful
    // that we don't lose required functionality this way.
    //
    // If it works we can remove this method and the many calls made to it
    function _getExtraParseData ($data = array())
    {
        $return = ($this->_aliasName ? array('alias_name' => $this->_aliasName) : array());
        return array_merge($return, $data);
    }
    
    // {{{ _substitutePlaceholders()
    
    /**
     * Substitutes placeholders for values matched in opening regular expressions
     * for contexts with their actual values
     * 
     * 
     */
    function _substitutePlaceholders ($ender)
    {
        if ($this->_startRegexTable) {
            foreach ($this->_startRegexTable as $key => $match) {
                $ender = str_replace('!!!' . $key, quotemeta($match), $ender);
            }
        }
        return $ender;
    }
    
    // }}}
    // {{{ _makeContextName()
    
    function _makeContextName ($name)
    {
        return (substr($name, 0, strlen($this->_languageName) + 1) == "{$this->_languageName}/")
            ? $name : "$this->_contextName/$name";
    }
    
    // }}}
    // {{{ _initPostProcess()
    
    /**
     * Called after the init function has had its fun to
     * do any cleanup required
     */
    function _initPostProcess ()
    {
    }
    
    // }}}
    
}

?>
