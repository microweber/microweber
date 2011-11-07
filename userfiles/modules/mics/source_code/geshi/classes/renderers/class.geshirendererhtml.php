<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/classes/renderers/class.geshirendererhtml.php
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
 * The GeSHiRendererHTML class
 * 
 * @package    geshi
 * @subpackage renderer
 * @author     Nigel McNie <nigel@geshi.org>
 * @since      1.1.1
 * @version    1.1.2alpha3
 * @see        GeSHiRenderer
 */
class GeSHiRendererHTML extends GeSHiRenderer
{

    // {{{ parseToken()
        
    /**
     * Implements parseToken to put HTML tags around the tokens
     * 
     * @param string $token         The token to put tags around
     * @param string $context_name  The name of the context that the tag is in
     * @param array  $data          Miscellaneous data about the context
     * @return string               The token wrapped in the appropriate HTML
     */
    function parseToken ($token, $context_name, $data)
    {
        // ignore blank tokens
        if ('' == $token || geshi_is_whitespace($token)) {
            return $token;
        }

        $result = '';
        if (isset($data['url'])) {
            // There's a URL associated with this token
            $result .= '<a href="' . htmlspecialchars($data['url']) . '">';
        }
        $result .= '<span style="' . $this->_styler->getStyle($context_name) . '" ';
        $result .= 'title="' . $context_name . '">' . htmlspecialchars($token) . '</span>';
        if (isset($data['url'])) {
            // Finish the link
            $result .= '</a>';
        }
        return $result;
    }
    
    // }}}
    // {{{ getHeader()
    
    /**
     * Returns the header for the code. Currently just a boring preset.
     * 
     * @return string
     */
    function getHeader ()
    {
        return '<pre style="background-color:#ffc;border:1px solid #cc9;">';
    }
    
    // }}}
    // {{{ getFooter()
    
    /**
     * Returns the footer for the code. Currently just a boring preset.
     * 
     * @return string
     */
    function getFooter ()
    {
        return '</pre>';
    }
    
    // }}}
    
}

?>
