<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/themes/default/sql/sql.php
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
 * @copyright  (C) 2006 Nigel McNie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version    1.1.2alpha3
 */

$this->setStyle('keyword/*', 'color:#933;font-weight:bold;');
$this->setStyle('type', 'color:#b1b100;font-weight:bold;');
$this->setStyle('symbol', 'color:#080;');
$this->setStyle('operator', 'color:#080;');

$this->setStyle('num/int', 'color:#22f;');
$this->setStyle('num/dbl', 'color:#c3c;');

$this->setStyle('quoted_identifier', 'color:#f00;');
$this->setStyle('quoted_identifier/esc', 'color:#006;font-weight:bold;');
$this->setStyle('string', 'color:#f00;');
$this->setStyle('string/esc', 'color:#006;font-weight:bold;');
$this->setStyle('bitstring', 'color:#f00;');
$this->setStyle('hexstring', 'color:#f00;');

$this->setStyle('positional_parameter', 'color:#606;');

$this->setStyle('single_comment', 'color:#888;');
$this->setStyle('multi_comment', 'color:#888;');

?>
