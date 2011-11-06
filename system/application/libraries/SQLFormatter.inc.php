<?php
/*
Copyright (C) 2008  Ziadin Givan

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
/**
 * SQL formatter class
 * @author Ziadin Givan
 * @copyright Ziadin Givan 
 */
class SQLFormatter
{
	function beautify( $sql, $indent )
	{
		//todo:save strings
		$sql = preg_replace('/[\r\n]/','', $sql);//remove new line  
		$sql = preg_replace('/\s+/',' ', $sql);//remove trailing spaces
		$sql = preg_replace('/\s*(FROM|WHERE|LIMIT|VALUES)\s*/',"\n$indent$1 ", $sql);//put from, where, limit on a new line
		$sql = preg_replace('/;/',";\n", $sql);//put a new query on a new line
		//todo:indent

		//todo restore strings
		return $sql;
	}
}