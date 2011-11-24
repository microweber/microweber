<?php
/*
Copyright (C) 2008 Ziadin Givan

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
 *  Php code beautifier 
 *  @author Ziadin Givan
 *  @copyright Ziadin Givan 
 */
class PhpBeautifier
{
	private $comments = array();
	private $docComments = array();
	private $inlineHTML = array();
	private $strings= array();
	/**
	 * options
	 */
	public $tokenSpace = true;
	public $blockLine = true;
	public $optimize = true;
	public $trimStrings = false;
	public $formatSQL = false;
	public $formatHTML = true;
	public $indent = "\t";//indent with tabs, change with space character to indent with spaces

	/**
	 * Custom sort by size descending
	 *
	 * @param string $a
	 * @param string $b
	 * @return string
	 */
	private function sort($a, $b)
	{
		$lengthA = strlen($a);
		$lengthB = strlen($b);
  	    if ($lengthA == $lengthB) 
		{
			return 0;
		}
		return ($lengthA < $lengthB) ? 1 : -1;
	}

	/**
	 * Process comments, format phpdoc comments
	 *
	 * @param string $comment
	 * @return string
	 */
	private function processComment( $comment )
	{
		$comment = trim( $comment );
		//todo: format phpDoc comments
		return $comment;
	}
	/**
	 * Format array definitions
	 *
	 * @param string $array
	 * @return string
	 */
	private function processArray( $array )
	{
		//todo: format multidimensional arrays
		return $array;
	}

	private function replace( &$array, $replacement, &$str )
	{
		//remove duplicates and sort, put the bigger ones first (this avoids replacing a small string that is contained in a bigger string)
		$array = array_unique( $array );
		usort( $array, array($this, 'sort') );
		
		$i = 0;
		foreach( $array as $replace)
		{
			$str = str_replace($replace, $replacement . $i . '_beautify_', $str);
			$i++;
		}
	}
		
	private function replaceStrings( $matches )
	{
		$this -> strings[] = $matches[0];
		return $matches[0];
	}
	
	private function restoreComments( &$matches )
	{
		return trim( $this -> comments[ $matches[1] ]);
	}

	private function restoredocComments( &$matches )
	{
		return $this -> processComment( $this -> docComments[ $matches[1] ]);
	}
	
	private function restoreHTML( $matches )
	{
		if ( $this -> formatHTML == true ) 
		{
			include_once 'HTMLFormatter.inc.php';
			$htmlFormatter = new HTMLFormatter();
			$this -> inlineHTML[ $matches[1] ] = $htmlFormatter -> beautify ( $this -> inlineHTML[ $matches[1] ] );
		}
		return trim( $this -> inlineHTML[ $matches[1] ]);
	}

	private function restoreStrings( &$matches )
	{
		$string = $this -> strings[ $matches[1] ];
		if ( $this -> optimize === true && $string[ 0 ] == '"')
		{
			//if a double quoted string does not contain variables or special characters, transform it to a single quoted string to save parsing time
			$match = preg_match('/(\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*|\\\\[nrtvf$]|\\\\x[0-9A-Fa-f]{1,2}|\[0-7]{1,3})/', $string );
			if ( $match === 0 )//search for variables
			{
				//unescape double quotes
				$string = str_replace('\"', '"', $string); 
				//escape single quotes
				$string = addcslashes($string, '\''); 
				//change quotes
				$string[ 0 ] = '\'';
				$string[ strlen($string) -1 ] = '\'';
			}
		}
		if ( $this -> trimStrings === true ) 
		{
			$string = preg_replace('/(?<=^["\'])\s*/', '', $string);//remove space from the begining of the string
			$string = preg_replace('/\s*(?=["\']$)/', '', $string);//remove space from the end of the string
		}

		//if sql optimization is selected and the string is a SQL query then beautify query
		if ( $this -> formatSQL && preg_match('/\s*[\'"]\s*(SELECT|UPDATE|INSERT)\s/i',$string) > 0)
		{
			include_once 'SQLFormatter.inc.php';
			$sqlFormatter = new SQLFormatter();
			$string = $sqlFormatter -> beautify( $string, $this -> indent );
		}
		return $string;
	}

	/**
	 * Prepare source to be formated, save comments and strings, call the formatting function and then restore them
	 */
	public function process($str)
	{
		$tokens = token_get_all($str);
		
		//the source is not php code, return the string without further processing
		if ( empty($tokens) )
		{
			return $str;
		}
		
		$this -> comments = array();
		$this -> docComments = array();
		$this -> strings = array();
		$this -> inlineHTML = array();
		
		for($i =0; $i< $count = count($tokens);$i++)
		{
			$token = $tokens[$i];
			
			//save strings and comments to preserve and restore them later to avoid running regullar expressions against comments and strings
			if ( is_array($token) && ! empty( $token[1] ) )
			{
				//save all comments strings and inline html
				if ( $token[0] === T_COMMENT ) //save comments
				{
					$this -> comments[] = $this -> processComment($token[1]);		
				} else if ( $token[0] === T_DOC_COMMENT)//save phpDoc comments
				{
					$this -> docComments[] = $this -> processComment($token[1]);
				}
				else if ( $token[0] === T_CONSTANT_ENCAPSED_STRING )//save strings
				{
					$this -> strings[] = $token[1];
				} else if ( $token[0] === T_INLINE_HTML )//save inline html
				{
						$this -> inlineHTML[] = $token[1];
				}
			}
		}

		//"replace" (search and save to the $this -> strings) HEREDOC strings
		$str = preg_replace_callback('/<<<(.{3}).*\1/s',array($this, 'replaceStrings'), $str);
		
		//"replace" (search and save to the $this -> strings) double quoted strings
		$str = preg_replace_callback('/"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"/s',array($this, 'replaceStrings'), $str);
		
		$this -> replace($this -> inlineHTML , 'html_replacement' , $str);
		$this -> replace($this -> comments , 'line_comment_replacement', $str );
		$this -> replace($this -> docComments , 'multi_comment_replacement' , $str);
		$this -> replace($this -> strings , 'string_replacement' , $str);

		//indent and reformat source
		$str = $this -> indent($this -> format($str));
		
		//put comments back
		$str = preg_replace_callback('/line_comment_replacement(\d+)_beautify_/s',array($this, 'restoreComments'), $str);
		$str = preg_replace_callback('/multi_comment_replacement(\d+)_beautify_/s',array($this, 'restoredocComments'), $str);
		
		//put strings back
		$str = preg_replace_callback('/string_replacement(\d+)_beautify_/s',array($this,'restoreStrings'), $str);

		//put inline html back
		$str = preg_replace_callback('/html_replacement(\d+)_beautify_/s',array($this,'restoreHTML'), $str);
		
		return $str;
	}

	/**
	 * Apply custom formatting to the source code and remove unneeded space
	 */ 
	private function format($str)
	{
		//remove redundant carachters
		$str=preg_replace("/[\r\n\t]/", '', $str);//remove all line breaks
		$str=preg_replace("/[ ]+/", " ", $str);//remove all trailing space

		// insert missing braces (does only match up to 2 nested parenthesis)
		$str=preg_replace("/(if|for|while|switch|foreach)\s*(\([^()]*(\([^()]*\)[^()]*)*\))([^{;]{2,};)/i", "\\1 \\2 \n{\\4\n}", $str);
		
		// missing braces for else statements
		$str=preg_replace("/(else)\s*([^{;]*;)/i", "\\1\n {\\2\n}", $str);

		// line break check
		$str=preg_replace("/([;{}]|case\s[^:]+:)\n?/i", "\\1\n", $str);
		$str=preg_replace("/^function\s+([^\n]+){/mi", "function \\1\n{", $str);	

		// remove inserted line breaks at else and for statements
		$str=preg_replace("/}\s*else\s*/m", "} \nelse \n", $str);
		$str=preg_replace("/(for\s*\()([^;]+;)(\s*)([^;]+;)(\s*)/mi", "\\1\\2 \\4 ", $str);

		// remove spaces between function call and parenthesis and start of argument list
		$str=preg_replace("/(\w+)\s*\(\s*/", "\\1(", $str);

		// set one space between control keyword and condition
		$str=preg_replace("/(if|for|while|switch|foreach|catch)\s*(\([^{]+\))\s*{/i", "\\1 \\2 \n{", $str);

		//add a line break before { for try 
		$str=preg_replace("/(try)\s*{/i", "\\1 \\2 \n{", $str);
		
		//add a line break before { for functions as well
		$str=preg_replace("/(function|class)\s+(.*?)\s*{\s*/i", "\\1 \\2 \n{\n", $str);

		/**
		* Comma
		*/
		//put space after , 
		$str=preg_replace("/\s*\,(?!(\s))/", ", ", $str);

		if ( $this -> tokenSpace === true )
		{
			/**
			* Object method, property
			*/
			//put space after -> 
			 $str=preg_replace("/(\s*\-\>\s*)/", " -> ", $str);

			/**
			* Double colons
			*/
			//put space after ::
			 $str=preg_replace("/(\s*\:\:\s*)/", " :: ", $str);	
			 	
			/**
			* Square braces
			*/
			//put space after [
			$str=preg_replace("/\[(?!(\s))/", "[ ", $str);

			//put space before ]
			$str=preg_replace("/(?<!(\s))\]/", " ]", $str);

			/**
			* Round braces
			*/
			//put space after ( 
			$str=preg_replace("/\s*(\()\s*(?!\))/", "( ", $str);

			//put space before )
			$str=preg_replace("/((?<!\()\s*\))/", " )", $str);

			/**
			* Concatenation dots
			*/
			//put space after .
			$str=preg_replace("/\.(?!([=\s]))/", ". ", $str);

			//put space before .
			$str=preg_replace("/(?<!(\s))\./", " .", $str);

			/**
			* Concatenation equal sign
			*/

			//put one space before and after !== != == = .= => <= >=
			$str=preg_replace("/\s*([><+-.!]?=+[>]?)\s*/", " \\1 ", $str);

			//put space between logical operators
			$str=preg_replace("/\s*(\&\&|\|\|)\s*/", " \\1 ", $str);
		}
		
		if ( $this -> blockLine === true )
		{
			//put a line before class properties and constants
			$str=preg_replace("/\s*((public\s+|private\s+|static\s+|protected\s+|const\s+).*;)\n*/i", "\n\\1\n", $str);

			//put a line before methods, functions
			$str=preg_replace("/\s*(((public|private|static|protected)\s*)*function)/i", "\n\n\\1", $str);

			//put an \n before each block start
			$str=preg_replace("/(?<!{\n)(if|for|while|foreach|try|switch)(?=\s*[({])/is", "\n\\1", $str);

			//put an \n after each block {
			$str=preg_replace("/\}(?![ \t]*(\n\n|\s*}|\s*else|\s*catch))\n*/is", "}\n\n", $str);

		}

		//optimize code
		if ( $this -> optimize === true )
		{
			//optimize echo statements, put multiple parameters instead of string concatenation ( replace '.' with ',')
			$str= preg_replace_callback('/(?<=echo)(.*?)(?=[\n;])/i', array($this, 'optimizeEcho'), $str);

			//always quote non numerical array keys
			$str= preg_replace_callback('/(?<=\[)(.*?)(?=\])/', array( $this, 'optimizeArray'), $str);
		}

		//join else if
		$str=preg_replace("/(else\s*if)/is", "elseif", $str);

		//put an \n after comments
		$str=preg_replace('/\s*(line_comment_replacement\d+_beautify_)\s*/s', "\\1\n", $str);

		//put an \n after multie line comments
		$str=preg_replace('/\s*(multi_comment_replacement\d+_beautify_)\s*/s', "\n\\1\n", $str);
		
		//put a line break after <?php
		$str=preg_replace("/\<\?php\s*/", "<?php\n", $str);

		//remove line breaks before ?\> (if i don't escape > php thinks is a valid script close tag, strange ... )
		$str=preg_replace("/\s*\?\>/", "\n?>", $str);

		//arrays
		//$str= preg_replace_callback('/(?<=Array\().*?(?=\);)/is', array($this , 'processArray'), $str);

		return $str;
	}

	/**
	 * Indent php source code
	 */
	private function indent($str)
	{
		$count = substr_count($str, '}') - substr_count($str, '{');

		if ( $count < 0 )
		{
			$count = 0;
		}

		$strarray=explode("\n", $str);

		for( $i=0; $i < count($strarray); $i++)
		{
			$strarray[$i]=trim($strarray[$i]);
			if (strstr($strarray[$i], '}'))
			{
				$count--;
			}

			if (preg_match("/^case\s/i", $strarray[$i]))
			{
				$level = str_repeat($this -> indent, ($count-1) );
			} else if (preg_match("/^or\s/i", $strarray[$i]))
			{
				$level = str_repeat($this -> indent, ($count+1));
			} else 
			{
				$level = str_repeat($this -> indent, $count);
			}

			$strarray[$i] = $level . $strarray[$i];
			
			if (strstr($strarray[$i], '{'))
			{
				$count++;
			}
		}
		$formatdstr=implode("\n", $strarray);
		return $formatdstr;
	}

	/* optimize functions */

	/*
	* Optimize echo statement
	*/
	function optimizeEcho( $matches )
	{
		return str_replace('.',',',$matches[1]);	
	}
	
	/*
	*
	* Optimize array keys
	*/
	function optimizeArray( $matches )
	{
		if ( empty( $matches[1] ))  
		{
			return $matches[1];
		} else if ( ctype_digit( trim($matches[1]) ) == true || strpos($matches[1],'$') !== false ) 
		{
			return $matches[1] ;
		}
		else if ( strpos($matches[1], 'string_replacement') === false ) 
		{
			if ( $this -> tokenSpace == true ) 
			{
				return ' \'' . trim($matches[1]) . '\' ';	
			} else
			{
				return '\'' . trim($matches[1]) . '\'';	
			}
		} else 
		{
			return $matches[1];
		}
	}
	/**
	* Beautifies all files in the $source folder and saves them in the $target directory, if $target is not specified then the files will be overwritten
	*/
	function folder( $source, $target = null )
	{
			//if target is not specified, overwrite original files
			if ( empty( $target) )
			{
				$target = $source;
			}
		
			if ( is_dir( $source ) )
			{
				@mkdir( $target );
			   
				$d = dir( $source );
			   
				while ( FALSE !== ( $entry = $d->read() ) )
				{
					if ( $entry == '.' || $entry == '..' )
					{
						continue;
					}
				   
					$Entry = $source . '/' . $entry;   
					        
					if ( is_dir( $Entry ) )
					{
						if ( $Entry != 'framework' )
						$this -> folder( $Entry, $target . '/' . $entry );
						echo $entry . "\n";
						flush();
						continue;
					}

					$ext = strtolower(substr($entry,-3));
					if ( $ext == 'php' || $ext == 'inc')
					{
					   echo 'Beautifying => ' . $target . '/' . $entry . ' => ' . $target . '/' . $entry .  "\n";
					   flush();
					   $this -> file( $Entry, $target . '/' . $entry );

					} else
					{
						copy( $Entry, $target . '/' . $entry );
					}
				}
			   
				$d->close();
			}else
			{
				copy( $source, $target );
			}
		}

	/**
	 * Reformats a php file
	 * 
	 */
	function file( $source, $destination = null )
	{
		$str = file_get_contents( $source );
		$str = $this -> process($str);
		if ( empty($destination) )
		{
			echo $str;
		} else
		{
			file_put_contents( $destination, $str );
		}
	}
}
