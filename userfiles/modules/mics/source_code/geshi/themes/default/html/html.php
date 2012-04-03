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
 * HTML styles for default theme
 */
$this->setStyle('entity', 'color:#00c;');
$this->setStyle('comment', 'color:#888;');
$this->setStyle('doctype', 'font-weight:bold;color:#933;');
$this->setStyle('string', 'color:#933;');

$this->setStyle('tag', 'color:#008000;');
$this->setStyle('tag/start', 'font-weight:bold;color:#000;');
$this->setStyle('tag/end', 'font-weight:bold;color:#000;');

$this->setStyle('tag/attribute', 'color:#006;');
$this->setStyle('tag/symbol', 'color:#008000;');

$this->loadStyles('css/css');
$this->loadStyles('javascript/javascript');

?>
