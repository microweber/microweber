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
 * HTML formatter class
 * @author Ziadin Givan
 * @copyright Ziadin Givan 
 */
class HTMLFormatter
{
	private $indent;
	private $html;
	private $position = 0;
	
	function indent( $matches )
	{
		
		if ( strpos($matches[0],'/') !== false ) 
		{
			$this -> indent--;
		} else
		{
			$this -> indent++;
		}
		return "\n" . $this -> indent . ' > ' . str_repeat("\t", ($this -> indent) ) . $matches[0];
	}

	function nextNode()
	{
		$startTag = strpos($this -> html, '<', $this -> position);
		$endTag = strpos($this -> html, '>', $startTag);
		if ( $startTag !== false && $endTag !== false ) 
		{
			return false;
		} else
		{
			$this -> position = $endTag;
			$node = substr( $this -> html, $startTag, ($endTag - $startTag ) + 1 );
			return $node;
		}
	}

	function beautify( $html )
	{
		//todo restore strings
		$html = preg_replace('/[\r\n]/','', $html);//remove new line  
		$html = preg_replace('/\s+/',' ', $html);//remove trailing spaces
		$this -> indent = 0;
		$this -> html = $html;
		$this -> indent = 1;
		while ( $node = $this -> nextNode() ) 
		{
			if ( strpos($node,'/') !== false ) 
			{
				$this -> indent--;
			} else
			{
				$this -> indent++;
			}
			$this -> html = str_replace($node, "\n" . $this -> indent . ' > ' . str_repeat("\t", ($this -> indent) ) . $node, $this -> html );
		}
		return $this -> html;
	}
}
