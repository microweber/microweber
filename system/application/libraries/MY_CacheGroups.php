<?php

/**
 * Matchbox Config class
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
 * @version   $Id: MY_Config.php 204 2008-02-24 01:30:00Z zacharias@dynaknudsen.dk $
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Extends the CodeIgniter Config class
 *
 * All code not encapsulated in {{{ Matchbox }}} was made by EllisLab
 *
 * @package   Matchbox
 * @copyright 2007-2008 Zacharias Knudsen
 * @license   http://www.gnu.org/licenses/gpl.html
 */
class MY_CacheGroups 
{
	const GROUP_GLOBAL = 'global';
	
	public static function getCacheDirectory($aCacheGroup = self::GROUP_GLOBAL)
	{
		$cacheDir = CACHEDIR_ROOT. $aCacheGroup;
		
		if (!is_dir($cacheDir)) {
			mkdir($cacheDir);
		}
		
		return $cacheDir;
	}
	
	public static function cleanCacheDir($aCacheGroup = self::GROUP_GLOBAL)
	{
		recursive_remove_directory(CACHEDIR_ROOT. $aCacheGroup);
	}
	

}

?>