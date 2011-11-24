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
 * @subpackage core
 * @author     Nigel McNie <nigel@geshi.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2004 - 2006 Nigel McNie
 * @version    1.1.2alpha3
 * 
 */

/**
 * The GeSHiStyler class
 * 
 * @package    geshi
 * @subpackage core
 * @author     Nigel McNie <nigel@geshi.org>
 * @since      1.1.0
 * @version    1.1.2alpha3
 */
class GeSHiStyler
{
    
    // {{{ properties
    
    /**
     * @var string
     */
    var $charset;

    /**
     * Array of themes to attempt to use for highlighting, in
     * preference order
     * 
     * @var array
     */
    var $themes = array('default');
    
    /**
     * @var string
     * Note: only set once language name is determined to be valid
     */
    var $language = '';
    
    /**
     * @var boolean
     */
    var $reloadThemeData = true;
    
    /**#@+
     * @access private
     */
    /**
     * @var array
     */
    var $_styleData = array();
    
    /**
     * @var array
     */
    var $_wildcardStyleData = array();
    
    /**
     * @var array
     */
    var $_contextCacheData = array();
    
    /**
     * @var GeSHiCodeParser
     */
    var $_codeParser = null;
    
    /**
     * @var GeSHiRenderer
     */
    var $_renderer = null;
    
    /**
     * @var string
     */
    var $_parsedCode = '';
    
    /**#@-*/
    
    // }}}
    // {{{ setStyle()
    
    /**
     * Sets the style of a specific context. Language name is prefixed,
     * to make theme files shorter and easier
     */
    function setStyle ($context_name, $style, $start_name = 'start', $end_name = 'end')
    {
        geshi_dbg('GeSHiStyler::setStyle(' . $context_name . ', ' . $style . ')');
        if ($context_name) {
            $context_name = "/$context_name";
        }
        $this->setRawStyle($this->language . $context_name, $style);
    }
    
    // }}}
    // {{{ setRawStyle()
    
    /**
     * Sets styles with explicit control over style name
     */
    function setRawStyle ($context_name, $style)
    {
        if (substr($context_name, -1) != '*') {
            $this->_styleData[$context_name] = $style;
        } else {
            $this->_wildcardStyleData[substr($context_name, 0, -2)] = $style;
        }
    }
    
    // }}}        
    // {{{ removeStyleData()
    
    /**
     * Removes any style data for the related context, including
     * data for the start and end of the context
     */
    function removeStyleData ($context_name, $context_start_name = 'start', $context_end_name = 'end')
    {
        unset($this->_styleData[$context_name]);
        unset($this->_styleData["$context_name/$context_start_name"]);
        unset($this->_styleData["$context_name/$context_end_name"]);
        geshi_dbg('  removed style data for ' . $context_name);
    }

    // }}}
    // {{{ getStyle()
    
    function getStyle ($context_name)
    {
        if (isset($this->_styleData[$context_name])) {
            return $this->_styleData[$context_name];
        }
        // If style for starter/ender requested and we got here, use the default
        if ('/end' == substr($context_name, -4)) {
            $this->_styleData[$context_name] = $this->getStyle(substr($context_name, 0, -4));
            return $this->_styleData[$context_name]; 
        }
        if ('/start' == substr($context_name, -6)) {
            $this->_styleData[$context_name] = $this->getStyle(substr($context_name, 0, -6));
            return $this->_styleData[$context_name]; 
        }
        
        // Check for a one-level wildcard match
        $wildcard_idx = substr($context_name, 0, strrpos($context_name, '/'));
        if (isset($this->_wildcardStyleData[$wildcard_idx])) {
            $this->_styleData[$context_name] = $this->_wildcardStyleData[$wildcard_idx];
            return $this->_wildcardStyleData[$wildcard_idx];
        }
        
        // Maybe a deeper match?
        foreach ($this->_wildcardStyleData as $context => $style) {
            if (substr($context_name, 0, strlen($context)) == $context) {
                $this->_styleData[$context_name] = $style;
                return $style;
            }
        }
        
        //@todo [blocking 1.1.5] Make the default style for otherwise unstyled elements configurable
        $this->_styleData[$context_name] = 'color:#000;';
        return 'color:#000;';
    }
    
    // }}}
    // {{{ loadStyles()
    
    function loadStyles ($language = '', $load_theme = false)
    {
        if (!$language) {
            $language = $this->language;
        }
        geshi_dbg('GeSHiStyler::loadStyles(' . $language . ')');
        if ($this->reloadThemeData) {
            geshi_dbg('  Loading theme data');
            // Trash old data
            if ($load_theme) {
                geshi_dbg('  Old data trashed');
                $this->_styleData = array();
            }
            
            // Lie for a short while, to get extra style names to behave
            $tmp = $this->language;
            $this->language = $language;
            foreach ($this->themes as $theme) {
                $theme_file = GESHI_THEMES_ROOT . $theme . GESHI_DIR_SEP . $language . '.php';
                if (is_readable($theme_file)) {
                    require $theme_file;
                    break;
                }
            }
            
            if ($load_theme) {
                $this->reloadThemeData = false;
            }
            $this->language = $tmp;
        }
    }
    
    // }}}
    // {{{ resetParseData()

    /**
     * Sets up GeSHiStyler for assisting with parsing.
     * Makes sure that GeSHiStyler has a code parser and
     * renderer associated with it.
     */
    function resetParseData ()
    {
        // Set result to empty
        $this->_parsedCode = '';
        
        // If the language we are using does not have a code
        // parser associated with it, use the default one
        if (is_null($this->_codeParser)) {
            /** Get the GeSHiCodeParser class */
            require_once GESHI_CLASSES_ROOT . 'class.geshicodeparser.php';
            /** Get the default code parser class */
            require_once GESHI_CLASSES_ROOT . 'class.geshidefaultcodeparser.php';
            $this->_codeParser =& new GeSHiDefaultCodeParser($this, $this->language);
        }

        // It the user did not explicitly set a renderer with GeSHi::accept(), then
        // use the default renderer (HTML)
        if (is_null($this->_renderer)) {
            /** Get the GeSHiRenderer class */
            require_once GESHI_CLASSES_ROOT . 'class.geshirenderer.php';
            /** Get the renderer class */
            require_once GESHI_RENDERERS_ROOT . 'class.geshirendererhtml.php';
            $this->_renderer =& new GeSHiRendererHTML;
        }
        
        // Load theme data now
        $this->loadStyles('', true);
    }

    // }}}
    // {{{ setCodeParser()
    
    /**
     * Sets the code parser that will be used. This is used by language
     * files in the geshi/languages directory to set their code parser
     * 
     * @param GeSHiCodeParser The code parser to use
     */
    function setCodeParser (&$codeparser)
    {
        if (is_subclass_of($codeparser, 'GeSHiCodeParser')) {
            $this->_codeParser =& $codeparser;
        } else {
            trigger_error('GeSHiStyler::setCodeParser(): code parser must be a subclass '
                . 'of GeSHiCodeParser', E_USER_ERROR);
        }
    }
    
    // }}}
    // {{{ useThemes()
    
    /**
     * Sets the themes to use
     */
    function useThemes ($themes)
    {
        $themes = (array) $themes;
        $this->themes = array_merge($themes, $this->themes);
        $this->themes = array_unique($this->themes);
        // Could check here: get first element of orig. $this->themes, if different now then reload
        $this->reloadThemeData = true;
    }
    
    // }}}
    // {{{ addParseData()
    
    /**
     * Recieves parse data from the context tree. Sends the
     * data on to the code parser, then to the renderer for
     * building the result string
     */    
    function addParseData ($code, $context_name, $data = null, $complex = false)
    {
        // @todo [blocking 1.1.5] test this, esp. not passing back anything and passing back multiple
        // can use PHP code parser for this
        // @todo [blocking 1.1.9] since we are only looking for whitespace at start and end this can
        // be optimised
        if (GESHI_COMPLEX_NO == $complex) {
            $this->_addToParsedCode(array($code, $context_name, $data));
        } elseif (GESHI_COMPLEX_PASSALL == $complex) {
            // Parse all at once
            $this->_addToParsedCode($this->_codeParser->parseToken($code, $context_name, $data));
        } elseif (GESHI_COMPLEX_TOKENISE == $complex) {
            $matches = array();
            preg_match_all('/^(\s*)(.*?)(\s*)$/si', $code, $matches);
            //echo 'START<br />';
            //print_r($matches);
            if ($matches[1][0]) {
                $this->_addToParsedCode($this->_codeParser->parseToken($matches[1][0],
                    $context_name, $data));
            }
            if ('' != $matches[2][0]) {
                while ('' != $matches[2][0]) {
                    $pos = geshi_get_position($matches[2][0], 'REGEX#(\s+)#');
                    if (false !== $pos['pos']) {
                        // Parse the token up to the whitespace
                        //echo 'code: |' . substr($matches[2][0], 0, $pos['pos']) . '|<br />';
                        $this->_addToParsedCode(
                            $this->_codeParser->parseToken(substr($matches[2][0], 0, $pos['pos']),
                            $context_name, $data)
                        );
                        // Parse the whitespace
                        //echo 'ws: |' . substr($matches[2][0], $pos['pos'], $pos['len']) . '|<br />';
                        $this->_addToParsedCode(
                            $this->_codeParser->parseToken(substr($matches[2][0], $pos['pos'], $pos['len']),
                            $context_name, $data)
                        );
                        // Trim what we just parsed
                        $matches[2][0] = substr($matches[2][0], $pos['pos'] + $pos['len']);
                    } else {
                        // No more whitespace
                        //echo 'no more whitespace: |' . $matches[2][0] . '<br />';
                        $this->_addToParsedCode($this->_codeParser->parseToken($matches[2][0],
                            $context_name, $data));
                        break;
                    }
                }
            }
            if ($matches[3][0]) {
                $this->_addToParsedCode($this->_codeParser->parseToken($matches[3][0],
                    $context_name, $data));
            }
        } // else wtf???
    }
    
    // }}}
    // {{{ _addToParsedCode()
    
    /**
     * Adds data from the renderer to the parsed code
     */
    function _addToParsedCode ($data)
    {
        if ($data) {
            if (!is_array($data[0])) {
                $this->_parsedCode .= $this->_renderer->parseToken($data[0], $data[1], $data[2]);
            } else {
                foreach ($data as $dat) {
                    $this->_parsedCode .= $this->_renderer->parseToken($dat[0], $dat[1], $dat[2]);
                }
            }
        }
    }
    
    // }}}
    // {{{ addParseDataStart()
    
    function addParseDataStart ($code, $context_name, $start_name = 'start', $complex = false)
    {
    	$this->addParseData($code, "$context_name/$start_name", null, $complex);
    }
    
    // }}}
    // {{{ addParseDataEnd()
    
    function addParseDataEnd ($code, $context_name, $end_name = 'end', $complex = false)
    {
    	$this->addParseData($code, "$context_name/$end_name", null, $complex);
    }
    
    // }}}
    // {{{ getParsedCode()
    
    function getParsedCode ()
    {
        // Flush the last of the code
        $this->_addToParsedCode($this->_codeParser->flush());
        
        $result = $this->_renderer->getHeader() . $this->_parsedCode . $this->_renderer->getFooter();
        $this->_parsedCode = '';
        return $result;
    }
    
    // }}}

}

?>
