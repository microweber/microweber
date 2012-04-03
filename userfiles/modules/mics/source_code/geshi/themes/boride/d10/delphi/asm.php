<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/themes/boride/d10/delphi/asm.php
 *   Author: Benny Baumann
 *   E-mail: BenBE@benbe.omorphia.de
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
 * @author     Benny Baumann <BenBE@benbe.omorphia.de>, Nigel McNie <nigel@geshi.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2005 - 2006 Benny Baumann, Nigel McNie
 * @version    1.1.2alpha3
 *
 */

/*
 * Delphi ASM styles for boring Borland IDE theme
 */
$this->setStyle('start', 'color:#000080;font-weight:bold;');
$this->setStyle('end', 'color:#000080;font-weight:bold;');
$this->setStyle('keyword', 'color:#000080;font-weight:bold;');
$this->setStyle('keyop', 'color:#000080;font-weight:bold;');
$this->setStyle('control', 'color:#000;font-weight:bold;');
$this->setStyle('register', 'color:#000;');
$this->setStyle('instr/i386', 'color:#000;font-weight:bold;');
$this->setStyle('instr/i387', 'color:#000;font-weight:bold;');
$this->setStyle('instr/mmx', 'color:#000;font-weight:bold;');
$this->setStyle('instr/sse', 'color:#000;font-weight:bold;');
$this->setStyle('instr/3Dnow', 'color:#000;font-weight:bold;');
$this->setStyle('instr/3Dnow2', 'color:#000;font-weight:bold;');
$this->setStyle('symbol', 'color:#000;');
$this->setStyle('label', 'color:#000;');
$this->setStyle('num/int', 'color:#000;');
$this->setStyle('num/dbl', 'color:#000;');
$this->setStyle('hex', 'color:#000;');
$this->setStyle('oodynamic', 'color:#000;');

?>
