<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/classes/php/class.geshiphpcodeparser.php
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
 * The GeSHiPHPDoubleStringContext class represents a PHP double string
 * 
 * @todo The GeSHiPHPDoubleStringContext functionality may be do-able by the code parser
 * 
 * @package    geshi
 * @subpackage lang
 * @author     Nigel McNie <nigel@geshi.org>
 * @since      1.1.0
 * @version    1.1.2alpha3
 * @see        GeSHiStringContext, GeSHiContext
 * @todo perhaps this could be done by codeparser?
 */
class GeSHiPHPDoubleStringContext extends GeSHiStringContext
{
    var $_parentName = '';
    
    /**
     * The regular expressions used to match variables
     * in this context.
     * 
     * {@internal Do Not Change These! The code logic
     * depends on them, they are just assigned here so
     * that they aren't assigned every time the
     * _addParseData method is called}}
     * 
     * @var array
     * @access private
     */
    var $_regexes = array(
        'REGEX#(\{?\$\$?\{?[a-zA-Z_][a-zA-Z0-9_]*\}?)#',
        'REGEX#(\{?\$\$?\{?[a-zA-Z_][a-zA-Z0-9_]*\[[\$a-zA-Z0-9_\s\[\]\']*\]\}?)#',
        'REGEX#(\{?)(\$\$?\{?[a-zA-Z_][a-zA-Z0-9_]*)(\s*->\s*)([a-zA-Z_][a-zA-Z0-9_]*)(\}?)#'
    );
    
    function GeSHiPHPDoubleStringContext ($context_name, $init_function = '')
    {
        $this->GeSHiStringContext($context_name, $init_function);
        $this->_parentName = parent::name();
    }
    
    /**
     * Adds code detected as being in this context to the parse data
     */    
    function _addParseData ($code, $first_char_of_next_context = '')
    {
        $parent_name = $this->_parentName;
        
        geshi_dbg('GeSHiPHPDoubleStringContext::_addParseData(' . substr($code, 0, 15) . '...)');

        while (true) {
            $earliest_data = array('pos' => false, 'len' => 0);
            foreach ($this->_regexes as $regex) {
                $data = geshi_get_position($code, $regex, 0, false, true); // request table
                if ((false != $data['pos'] && false === $earliest_data['pos']) ||
                    (false !== $data['pos']) &&
                    (($data['pos'] < $earliest_data['pos']) ||
                    ($data['pos'] == $earliest_data['pos'] && $data['len'] > $earliest_data['len']))) {
                    $earliest_data = $data;
                }
            }

            if (false === $earliest_data['pos']) {
                // No more variables in this string
                break;
            }
            
            // bugfix: because we match a var, it might have been escaped.
            // so only do to -1 so we can catch slash if it has been
            $pos = ($earliest_data['pos']) ? $earliest_data['pos'] - 1 : 0;
            $len = ($earliest_data['pos']) ? $earliest_data['len'] + 1 : $earliest_data['len'];
            parent::_addParseData(substr($code, 0, $pos));
            
            // Now the entire possible var is in:
            $possible_var = substr($code, $pos, $len);
            geshi_dbg('Found variable at position ' . $earliest_data['pos'] . '(' . $possible_var . ')');
            
            // Check that the dollar sign that started this variable was not escaped
            //$first_part = str_replace('\\\\', '', substr($code, 0, $pos));
            //if ('\\' == substr($first_part, -1)) {
            // If \\ before var and { is not next character after that...
            if ('\\' == substr($possible_var, 0, 1) && '{' != substr($possible_var, 1, 1)) {
                // This variable has been escaped, so add the escaped dollar sign
                // as the correct context, and the rest of the variable (recurse to catch
                // other variables inside this possible variable)
                geshi_dbg('Variable was escaped');
                $this->_styler->addParseData(substr($possible_var, 0, 2), $parent_name . '/esc',
                    $this->_getExtraParseData(), $this->_complexFlag);
                $this->_addParseData(substr($possible_var, 2));
            } else {
                // Add first character that might have been a \\ but in fact isn't to the parent
                // but only do it if we had to modify the position
                if ('$' != substr($possible_var, 0, 1)) {
                    parent::_addParseData(substr($possible_var, 0, 1));
                    $possible_var = substr($possible_var, 1);
                }
                
                // Many checks could go in here...
                // @todo [blocking 1.1.5] check for ${foo} variables: start { matched by end }
                // because at the moment ${foo is matched for example.
                if ('{' == substr($possible_var, 0, 1)) {
                    if ('}' == substr($possible_var, -1)) {
                        $start_brace = '{';
                    } else {
                        $start_brace = '';
                        parent::_addParseData('{');
                        // remove brace from $possible_var. This will only be used
                        // if the variable isn't an OO variable anyway...
                        $possible_var = substr($possible_var, 1);
                    }
                } else {
                    $start_brace = '';
                }

                if (isset($earliest_data['tab'][5])) {
                    // Then we matched off the third regex - the one that does objects
                    // The first { if there is one, and $this (which is in index 2
                    $this->_styler->addParseData($start_brace . $earliest_data['tab'][2],
                        $parent_name . '/var', $this->_getExtraParseData(), $this->_complexFlag);
                    // The -> with any whitespace around it
                    $this->_styler->addParseData($earliest_data['tab'][3], $parent_name . '/symbol',
                        $this->_getExtraParseData(), $this->_complexFlag);
                    // The method name
                    $this->_styler->addParseData($earliest_data['tab'][4], $parent_name . '/oodynamic',
                        $this->_getExtraParseData(), $this->_complexFlag);
                    // The closing }, if any
                    if ($earliest_data['tab'][5]) {
                        if ($start_brace) {
                            $this->_styler->addParseData($earliest_data['tab'][5], $parent_name . '/var',
                                $this->_getExtraParseData(), $this->_complexFlag);
                        } else {
                            parent::_addParseData('}');
                        }
                    } 
                } else {
                    $this->_styler->addParseData($possible_var, $parent_name . '/var',
                        $this->_getExtraParseData(), $this->_complexFlag);
                }
            }
            
            // Chop off what we have done
            $code = substr($code, $earliest_data['pos'] + $earliest_data['len']);
        }
        // Add the rest
        parent::_addParseData($code, $first_char_of_next_context); 
    }
}
   
?>
