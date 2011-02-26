<?php

/**
 * Matchbox Configuration file
 *
 * This file is part of Matchbox
 *
 * Matchbox is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * Matchbox is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   Matchbox
 * @copyright 2007-2008 Zacharias Knudsen
 * @license   http://www.gnu.org/licenses/gpl.html
 * @version   $Id: matchbox.php 204 2008-02-24 01:30:00Z zacharias@dynaknudsen.dk $
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * The files that should be excluded from the caller search
 *
 * When trying to figure out which module requested a particular
 * resource, the file that called e.g. $this->load->view('filename')
 * will be used to determine the module. However, in some cases you
 * might need to exclude certain files in this search. Perhaps you are
 * using a custom view library. In that case, whenever you call the
 * custom method, the library will be considered the caller instead of
 * your controller and so the module cannot be determined. Therefore
 * you will need to add the file to this array.
 *
 * If you have multiple files with the same name, but you only wish
 * to exclude one of them, you can also add a bit of the filepath to
 * distinguish it from the others.
 *
 * NO FILE EXTENSION!
 *
 * Prototype:
 * $config['callers'] = array('libraries/filename');
 */
$config['callers'] = array();

/**
 * The directories in which your modules are located
 *
 * Should contain an array of directories RELATIVE to the application
 * folder in which to look for modules. You'd usually want to have at
 * least 'modules' in your array which will have codeigniter look for
 * modules in /application/modules. You can add as many directories
 * as you like, though.
 *
 * NO TRAILING SLASHES!
 *
 * Prototype:
 * $config['direcotires'] = array('modules');
 */
$config['directories'] = array('modules');

?>