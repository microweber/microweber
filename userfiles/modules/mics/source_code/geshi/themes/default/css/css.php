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
 * CSS styles for default theme
 */
$this->setStyle('string', 'color:#f00;');
$this->setStyle('string/esc', 'color:#006;font-weight:bold;');

$this->setStyle('comment', 'color:#888;font-style:italic;');

$this->setStyle('psuedoclass', 'color:#33f;');
$this->setStyle('symbol', 'color:#008000;');
$this->setStyle('class', 'color:#969;');
$this->setStyle('id', 'color:#969;font-weight:bold;');

$this->setStyle('rule/start', 'font-weight:bold;color:#000;');
$this->setStyle('rule/end', 'font-weight:bold;color:#000;');
$this->setStyle('rule/attribute', 'font-weight:bold;color:#000;');
$this->setStyle('rule/paren', 'color:#933;');
$this->setStyle('rule/color', 'color:#933;');
$this->setStyle('rule/type', 'color:#933;');
$this->setStyle('rule/symbol', 'color:#008000;');
$this->setStyle('rule/value', 'color:#933;');

$this->setStyle('attribute_selector', 'color:#008000;');

$this->setStyle('at_rule/start', 'color:#c9c;font-weight:bold;');
$this->setStyle('at_rule/end', 'color:#008000;');
$this->setStyle('at_rule/paren', 'color:#933;');
$this->setStyle('at_rule/symbol', 'color:#008000;');

//$this->setStyle('inline_media', 'color:#b1b100;');
//$this->setStyle('inline_media/starter', 'color:#c9c;font-weight:bold;');
$this->setStyle('inline_media/start', 'color:#000;font-weight:bold;');
$this->setStyle('inline_media/end', 'color:#000;font-weight:bold;');

?>
