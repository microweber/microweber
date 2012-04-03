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
 * @author     Nigel McNie <nigel@geshi.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2004 - 2006 Nigel McNie
 * @version    1.1.2alpha3
 *
 */

/*
 * PHP4 styles for default theme
 */
$this->setStyle('start', 'font-weight:bold;color:#000;');
$this->setStyle('end', 'font-weight:bold;color:#000;');

$this->setStyle('keyword', 'color:#a1a100;');
$this->setStyle('type', 'color:#7f0055;');
$this->setStyle('constant', 'font-weight:bold;color:#000;');
$this->setStyle('function', 'color:#006;');

$this->setStyle('symbol', 'color:#008000;');
$this->setStyle('varstart', 'color:#33f;');
$this->setStyle('var', 'color:#33f;');

$this->setStyle('num/int', 'color:#11e;');
$this->setStyle('num/dbl', 'color:#c6c;');

$this->setStyle('oodynamic', 'color:#933;');
$this->setStyle('oostatic', 'color:#933;font-weight:bold;');

$this->setStyle('single_string', 'color:#f00;');
$this->setStyle('single_string/esc', 'color:#006;font-weight:bold;');
$this->setStyle('double_string', 'color:#f00;');
$this->setStyle('double_string/esc', 'color:#006;font-weight:bold;');

$this->setStyle('double_string/var', 'color:#22f;');
$this->setStyle('double_string/symbol', 'color:#008000;');
$this->setStyle('double_string/oodynamic', 'color:#933;');

$this->setStyle('heredoc', 'color:#f00;');
$this->setStyle('heredoc/start', 'color:#006;font-weight:bold;');
$this->setStyle('heredoc/end', 'color:#006;font-weight:bold;');
$this->setStyle('heredoc/var', 'color:#22f;');
$this->setStyle('heredoc/symbol', 'color:#008000;');
$this->setStyle('heredoc/oodynamic', 'color:#933;');

$this->setStyle('single_comment', 'color:#888;font-style:italic;');
$this->setStyle('multi_comment', 'color:#888;font-style:italic;');
$this->setStyle('phpdoc_comment', 'color:#555;font-style:italic;');
$this->setStyle('phpdoc_comment/tag', 'color:#ca60ca;font-weight:bold;');
$this->setStyle('phpdoc_comment/link', 'color:#0095ff;font-weight:bold;');
$this->setStyle('phpdoc_comment/htmltag', 'color:#000;font-weight:bold;');

// Stuff set in the code parser
$this->setStyle('classname', 'color:#933;');
$this->setStyle('definedconstant', 'font-weight:bold;color:#444;');
$this->setStyle('phpdoc_comment/tagvalue', 'color:#0095ff;font-weight:bold;');
$this->setStyle('phpdoc_comment/var', 'color:#33f;');
$this->setStyle('functioncall', 'color:#600;font-weight:bold;');
$this->setStyle('functionname', 'color:#600;');

$this->loadStyles('html/html');

?>
