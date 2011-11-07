<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/themes/boride/delphi/delphi.php
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
 * @subpackage theme
 * @author     Benny Baumann <BenBE@benbe.omorphia.de>, Nigel McNie <nigel@geshi.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2005 - 2006 Benny Baumann, Nigel McNie
 * @version    1.1.2alpha3
 *
 */

/*
 * Delphi styles for boring Borland IDE theme
 */
$this->setStyle('single_string', 'color:#000;');
$this->setStyle('single_string/esc', 'color:#000;');

$this->setStyle('multi_comment', 'color:#000080;font-style:italic;');
$this->setStyle('single_comment', 'color:#000080;font-style:italic;');

$this->setStyle('preprocessor', 'color:#008000;font-style:italic;');
$this->setStyle('preprocessor/switch', 'color:#008000;font-style:italic;font-weight:bold;');
$this->setStyle('preprocessor/num/int', 'color:#008000;');
$this->setStyle('preprocessor/single_string', 'color:#008000;');

$this->setStyle('keyword', 'color:#000;font-weight:bold;');

$this->loadStyles('delphi/asm');
$this->setStyle('asm', 'color:#000;');
$this->setStyle('asm/start', 'color:#000;font-weight:bold;');
$this->setStyle('asm/end', 'color:#000;font-weight:bold;');
$this->setStyle('asm/keyword', 'color:#000;font-weight:bold;');
$this->setStyle('asm/keyop', 'color:#000;font-weight:bold;');
$this->setStyle('asm/control', 'color:#000;font-weight:bold;');
$this->setStyle('asm/register', 'color:#000;');
$this->setStyle('asm/instr/*', 'color:#000;font-weight:bold;');

?>
