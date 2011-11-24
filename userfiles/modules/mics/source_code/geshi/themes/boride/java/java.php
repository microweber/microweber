<?php
/**
 * GeSHi - Generic Syntax Highlighter
 *
 * For information on how to use GeSHi, please consult the documentation
 * found in the docs/ directory, or online at http://geshi.org/docs/
 *
 *  This file is part of GeSHi.
 *
 *  GeSHi is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  GeSHi is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with GeSHi; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * You can view a copy of the GNU GPL in the COPYING file that comes
 * with GeSHi, in the docs/ directory.
 *
 * @package    geshi
 * @subpackage theme
 * @author     Benny Baumann <BenBE@benbe.omorphia.de>, Nigel McNie <nigel@geshi.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2004 - 2006 Nigel McNie
 * @version    1.1.2alpha3
 *
 */

/*
 * Java styles for boring Borland IDE theme
 */
$this->setStyle('single_string', 'color:#000;');
$this->setStyle('single_string/esc', 'color:#000;font-weight:bold;');
$this->setStyle('double_string', 'color:#000;');
$this->setStyle('double_string/esc', 'color:#000;font-weight:bold;');

$this->setStyle('single_comment', 'color:#000080;font-style:italic;');
$this->setStyle('multi_comment', 'color:#000080;font-style:italic;');

$this->setStyle('keyword', 'color:#000;font-weight:bold;');
$this->setStyle('java/*', 'color:#000;');
$this->setStyle('const', 'color:#000;');
$this->setStyle('dtype', 'color:#000;font-weight:bold;');

$this->setStyle('symbol', 'color:#000;');
$this->setStyle('ootoken', 'color:#000;');
$this->setStyle('num/int', 'color:#000;');
$this->setStyle('num/dbl', 'color:#000;');

//Due to no distinguishing normal comments and doxygen comments, just make them comment style.
//$this->loadStyles('doxygen/doxygen');
$this->setRawStyle('doxygen/doxygen', 'color:#000080;font-style:italic;');

?>
