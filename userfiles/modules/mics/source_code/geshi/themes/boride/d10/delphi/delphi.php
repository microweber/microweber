<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/themes/boride/d10/delphi/delphi.php
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
 * Delphi styles for boring Borland IDE theme
 */
$this->setStyle('multi_comment', 'color:#008000;font-style:italic;');
$this->setStyle('single_comment', 'color:#008000;font-style:italic;');
$this->setStyle('single_string', 'color:#0000FF;');
$this->setStyle('single_string/esc', 'color:#0000FF;');

$this->setStyle('preprocessor', 'color:#008080;');
$this->setStyle('preprocessor/switch', 'color:#008080;font-weight:bold;');
$this->setStyle('preprocessor/num/int', 'color:#008080;font-style:italic;');
$this->setStyle('preprocessor/single_string', 'color:#008080;font-style:italic;');

$this->setStyle('keyword', 'color:#000080;font-weight:bold;');
$this->setStyle('keytype', 'color:#000;font-weight:bold;');
$this->setStyle('keyident', 'color:#000;font-weight:bold;');

$this->setStyle('symbol', 'color:#000;');
$this->setStyle('ctrlsym', 'color:#000;');
$this->setStyle('oopsym', 'color:#000;');
$this->setStyle('brksym', 'color:#000;');
$this->setStyle('mathsym', 'color:#000;');
$this->setStyle('cmpsym', 'color:#000;');

$this->setStyle('char', 'color:#800080;');
$this->setStyle('charhex', 'color:#800080;');
$this->setStyle('hex', 'color: #00F;');

$this->setStyle('oodynamic', 'color:#000;');

$this->setStyle('stdprocs/system', 'color:#444;');
$this->setStyle('stdprocs/sysutil', 'color:#444;');
$this->setStyle('stdprocs/class', 'color:#444;');
$this->setStyle('stdprocs/math', 'color:#444;');

$this->setStyle('num/int', 'color:#0000FF;');
$this->setStyle('num/dbl', 'color:#0000FF;');

$this->loadStyles('delphi/asm');

?>
