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
 * PHP styles for phpsyntax dynamic theme
 * @todo [blocking 1.1.2] add semi-colons to style definitions
 */
$string  = ini_get('highlight.string');
$comment = ini_get('highlight.comment');
$keyword = ini_get('highlight.keyword');
$bg      = ini_get('highlight.bg');
$default = ini_get('highlight.default');
$html    = ini_get('highlight.html');

$this->setRawStyle('php/php/*', 'color:' . $default);

$this->setStyle('keyword', 'color:' . $keyword);
$this->setStyle('type', 'color:' . $keyword);

$this->setStyle('symbol', 'color:' . $keyword);

$this->setStyle('single_string', 'color:' . $string);
$this->setStyle('single_string/esc', 'color:' . $string);
$this->setStyle('double_string', 'color:' . $string);
$this->setStyle('double_string/*', 'color:' . $string);

$this->setStyle('heredoc/start', 'color:' . $keyword);
$this->setStyle('heredoc/end', 'color:' . $keyword);

$this->setStyle('single_comment', 'color:' . $comment);
$this->setStyle('multi_comment', 'color:' . $comment);

$this->setRawStyle('html/html/*', 'color:' . $html);
$this->setRawStyle('javascript/javascript/*', 'color:' . $html);
$this->setRawStyle('css/css/*', 'color:' . $html);
$this->setRawStyle('doxygen/doxygen/*', 'color:' . $comment);

?>
