<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/languages/sql/class.geshisqlcodeparser.php
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
 * @author     Nigel McNie
 * @copyright  (C) 2006 Nigel McNie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version    1.1.2alpha3
 */

class GeSHiSQLCodeParser extends GeSHiCodeParser
{
    var $_prevToken = '';
    var $_prevContextName = '';
    var $_prevData = '';
    
    function parseToken ($token, $context_name, $data)
    {
        if (geshi_is_whitespace($token)
            || false !== strpos($context_name, 'comment')) {
            return array($token, $context_name, $data);
        }
        
        // make sure that the NEXT token after this is not a (, because
        // then we would be clobbering a function name
        $ctype = substr($context_name, strlen($this->_language) + 1);
        if ((',' == $this->_prevToken || '(' == $this->_prevToken)
            && in_array($ctype, array('type', 'keyword/nonreserved'))) {
            $context_name = $this->_language;
        }
        
        $this->_prevToken = $token;
        $this->_prevContextName = $context_name;
        $this->_prevData = $data; // needed?
        
        return array($token, $context_name, $data);
    }
}

?>
