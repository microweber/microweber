<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/classes/class.geshirenderer.php
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
 * @subpackage renderer
 * @author     Nigel McNie <nigel@geshi.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2004 - 2006 Nigel McNie
 * @version    1.1.2alpha3
 * 
 */

/**
 * The GeSHiRenderer class
 * 
 * @package    geshi
 * @subpackage renderer
 * @author     Nigel McNie <nigel@geshi.org>
 * @since      1.1.1
 * @version    1.1.2alpha3
 * @abstract
 */
class GeSHiRenderer
{
    
    // {{{ properties
    
    /**
     * The styler object being used
     * 
     * @var GeSHiStyler
     * @access private
     */
    var $_styler = null;
    
    // }}}
    // {{{ GeSHiRenderer()
    
    /**
     * Constructor.
     */
    function GeSHiRenderer ()
    {
        $this->_styler =& geshi_styler();
    }
    
    // }}}
    // {{{ parseToken()
    
    /**
     * Abstract. Renderers should implement this method to
     * get access to parse tokens
     * 
     * @abstract
     */
    function parseToken ($token, $context_name, $data) {}
    
    // }}}
    // {{{ getHeader()
    
    /**
     * Should return any header data for the renderer
     * 
     * @abstract
     */
    function getHeader () {}
    
    // }}}
    // {{{ getFooter()
    
    /**
     * Should return any footer data for the renderer
     * 
     * @abstract
     */
    function getFooter () {}
    
    // }}}
    
}

?>
