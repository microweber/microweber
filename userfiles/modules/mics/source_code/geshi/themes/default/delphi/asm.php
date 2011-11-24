<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/themes/default/delphi/asm.php
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
 * @author     Benny Baumann <BenBE@benbe.omorphia.de>, Nigel McNie <nigel@geshi.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2005 - 2006 Benny Baumann, Nigel McNie
 * @version    1.1.2alpha3
 *
 */

/*
 * Delphi ASM styles for default theme
 */
$this->setStyle('start', 'color:#f00;font-weight:bold;');
$this->setStyle('end', 'color:#f00;font-weight:bold;');
$this->setStyle('keyword', 'color:#00f;font-weight:bold;');
$this->setStyle('keyop', 'color:#f00;font-weight:bold;');
$this->setStyle('control', 'color:#00f;font-weight: bold;');
$this->setStyle('register', 'color:#00f;');
$this->setStyle('instr/i386', 'color:#00f;font-weight:bold;');
$this->setStyle('instr/i387', 'color:#00f;font-weight:bold;');
$this->setStyle('instr/mmx', 'color:#00f;font-weight:bold;');
$this->setStyle('instr/sse', 'color:#00f;font-weight:bold;');
$this->setStyle('instr/3Dnow', 'color:#00f;font-weight:bold;');
$this->setStyle('instr/3Dnow2', 'color:#00f;font-weight:bold;');

$this->setStyle('symbol', 'color:#008000;');
$this->setStyle('label', 'color:#933;');
$this->setStyle('num/int', 'color:#d2d;');
$this->setStyle('num/dbl', 'color:#c6c;');
$this->setStyle('hex', 'color: #2bf;');

$this->setStyle('oodynamic', 'color:#559;');

?>
