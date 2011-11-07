<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/classes/class.geshicodeparser.php
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
 * @subpackage core
 * @author     Nigel McNie <nigel@geshi.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2004 - 2006 Nigel McNie
 * @version    1.1.2alpha3
 * 
 */

/**
 * The GeSHiCodeParser class. An abstract implementation of a class that can receive tokens,
 * modify them and send them back.
 * 
 * A language might use this to improve highlighting by detecting things that the context
 * tree cannot detect by itself.
 * 
 * @package    geshi
 * @subpackage core
 * @author     Nigel McNie <nigel@geshi.org>
 * @since      1.1.1
 * @version    1.1.2alpha3
 * @abstract
 * @todo [blocking 1.1.9] From my nigel@geshi.org email I wrote
 * a useful description of how the parseToken method works, which
 * I should put in here and on the wiki
 * 
 * @todo [blocking 1.1.2] The java stack/reference stuff is useful
 * for C as well, perhaps it could be reparented into here?
 */
class GeSHiCodeParser
{
    
    // {{{ properties
    
    /**#@+
     * @access private
     */
    
    /**
     * The GeSHiStyler being used to highlight the code
     * 
     * @var GeSHiStyler
     */
    var $_styler = null;
    
    /**
     * The language/dialect that is being highlighted
     * 
     * @var string
     */
    var $_language = '';
    
    /**
     * A stack. Not necessary for all code parsers but this class provides a common
     * implementation
     */
    var $_stack = array();
    
    /**#@-*/
    
    // }}}
    // {{{ GeSHiCodeParser()
    
    /**
     * Constructor. Assigns the GeSHiStyler object to use
     * 
     * @param GeSHiStyler The styler oject to use
     */
    function GeSHiCodeParser($language)
    {
        $this->_styler   =& geshi_styler();
        $this->_language =  $language;
    }
    
    // }}}
    // {{{ parseToken()
    
    /**
     * Recieves tokens and returns them, possibly modified
     * 
     * @param string The token recieved
     * @param string The name of the context the token is in
     * @param string Any extra data associated with the context
     * @return mixed Either <kbd>false</kbd>, an array($token, $context_name, $data)
     *               or an array of arrays like this.
     * @abstract
     */
    function parseToken ($token, $context_name, $data) {}
    
    // }}}
    // {{{ sourcePreProcess()
    
    /**
     * Is given the entire source code before parsing begins so that various information
     * about the source can be stored.
     * 
     * This method is completely optional. Note that there is no postprocess method - the
     * information gathered by this method should be exploited by {@link parseToken()}
     * 
     * @param  string The source code
     * @return string The source code modified as necessary
     */
    function sourcePreProcess ($code)
    {
        return $code;
    }
    
    // }}}
    // {{{ push()

    /**
     * This method handles storing of stuff into a stack of elements.
     */
    function push ($token, $context_name, $data)
    {
        $this->_stack[] =  array($token, $context_name, $data);
    }

    // }}}
    // {{{ flush()
    
    /**
     * If the code parser uses a stack, this method should empty and return it.
     * 
     * @return array The contents of the stack
     */
    function flush ($token = '', $context_name = '', $data = array())
    {
        if ('' != $token) {
            $this->push($token, $context_name, $data);
        }
        $result = $this->_stack;
        // Does this do anything?
        if (!$result) {
            $result = false;
        }
        $this->_stack = array();
        return $result;
    }
    
    // }}}
    
}

?>
