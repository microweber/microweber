<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/themes/default/web3d/vrml97.php
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
 * @subpackage theme
 * @author     Nigel McNie
 * @copyright  (C) 2004 - 2006 Nigel McNie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version    1.1.2alpha3
 */

$this->setStyle('node', 'color:#b1b100;');
$this->setStyle('field', 'font-weight:bold;color:red;');
$this->setStyle('keyword', 'font-weight:bold;color:blue;');
$this->setStyle('fieldaccess', 'font-weight:bold;color:purple;');
$this->setStyle('fieldtype', 'color:green;');
$this->setStyle('nodesymbol', 'color:#008000;');
$this->setStyle('arraysymbol', 'color:#008000;');
$this->setStyle('num/int', 'color: #11e;');
$this->setStyle('num/dbl', 'color: #c6c;');
$this->setStyle('string', 'color:#933;');
$this->setStyle('comment', 'color:#888;background-color:#EEE;font-style:bold;');
$this->loadStyles('javascript/javascript');

?>
