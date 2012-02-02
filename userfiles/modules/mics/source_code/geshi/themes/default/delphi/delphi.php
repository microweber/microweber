<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/themes/default/delphi/delphi.php
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
 * Delphi styles for default theme
 */
$this->setStyle('multi_comment', 'color:#888;font-style:italic;');
$this->setStyle('single_comment', 'color:#888;font-style:italic;');
$this->setStyle('single_string', 'color:#f00;');
$this->setStyle('single_string/esc', 'color:#006;font-weight:bold;');

$this->setStyle('preprocessor', 'color:#080;font-style:italic;');
$this->setStyle('preprocessor/switch', 'color:#080;font-style:italic;font-weight:bold;');
$this->setStyle('preprocessor/num/int', 'color:#080;');
$this->setStyle('preprocessor/single_string', 'color:#080;');

$this->setStyle('keyword', 'color:#f00;font-weight:bold;');
$this->setStyle('keytype', 'color:#000;font-weight:bold;');
$this->setStyle('keyident', 'color:#000;font-weight:bold;');

$this->setStyle('symbol', 'color:#008000;');
$this->setStyle('ctrlsym', 'color:#008000;');
$this->setStyle('oopsym', 'color:#008000;');
$this->setStyle('brksym', 'color:#008000;');
$this->setStyle('mathsym', 'color:#008000;');
$this->setStyle('cmpsym', 'color:#008000;');

$this->setStyle('char', 'color:#db9;');
$this->setStyle('charhex', 'color:#db9;');
$this->setStyle('hex', 'color: #2bf;');

$this->setStyle('oodynamic', 'color:#559;');

$this->setStyle('stdprocs/system', 'color:#444;');
$this->setStyle('stdprocs/sysutil', 'color:#444;');
$this->setStyle('stdprocs/class', 'color:#444;');
$this->setStyle('stdprocs/math', 'color:#444;');

$this->setStyle('num/int', 'color:#d2d;');
$this->setStyle('num/dbl', 'color:#c6c;');

$this->loadStyles('delphi/asm');

?>
