<?php


function string_cyrlat($str){

$cyr  = array('а','б','в','г','д','e','ж','з','и','й','к','л','м','н','о','п','р','с','т','у', 'ф','х','ц','ч','ш','щ','ъ','ь', 'ю','я','А','Б','В','Г','Д','Е','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У', 'Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ь', 'Ю','Я' );
$lat = array( 'a','b','v','g','d','e','zh','z','i','y','k','l','m','n','o','p','r','s','t','u','f' ,'h' ,'ts' ,'ch','sh' ,'sht' ,'a' ,'y' ,'yu' ,'ya','A','B','V','G','D','E','Zh','Z','I','Y','K','L','M','N','O','P','R','S','T','U','F' ,'H' ,'Ts' ,'Ch','Sh' ,'Sht' ,'A' ,'Y' ,'Yu' ,'Ya' );
$textcyr = str_replace($cyr, $lat, $str);
$textlat = str_replace($lat, $cyr, $str);
echo("$textcyr $textlat");

	
}




function string_cyr2lat($str)
{
	
		
	$str=strtolower($str);
	$str=str_replace('а','a',$str);
	$str=str_replace('б','b',$str);
	$str=str_replace('в','v',$str);
	$str=str_replace('г','g',$str);
	$str=str_replace('д','d',$str);
	$str=str_replace('е','e',$str);
	$str=str_replace('ж','j',$str);
	$str=str_replace('з','z',$str);
	$str=str_replace('и','i',$str);
	$str=str_replace('й','i',$str);
	$str=str_replace('к','k',$str);
	$str=str_replace('л','l',$str);
	$str=str_replace('м','m',$str);
	$str=str_replace('н','n',$str);
	$str=str_replace('о','o',$str);
	$str=str_replace('п','p',$str);
	$str=str_replace('р','r',$str);
	$str=str_replace('с','s',$str);
	$str=str_replace('т','t',$str);
	$str=str_replace('у','u',$str);
	$str=str_replace('ф','f',$str);
	$str=str_replace('х','h',$str);
	$str=str_replace('ц','c',$str);
	$str=str_replace('ч','ch',$str);
	$str=str_replace('ш','sh',$str);
	$str=str_replace('щ','sht',$str);
	$str=str_replace('ь','i',$str);
	$str=str_replace('ъ','a',$str);
	$str=str_replace('ю','iu',$str);
	$str=str_replace('я','ia',$str);
	
	return $str;
}

function string_lat2cyr($str)
{
	$str=strtolower($str);
	$str=str_replace('a','а',$str);
	$str=str_replace('b','б',$str);
	$str=str_replace('v','в',$str);
	$str=str_replace('g','г',$str);
	$str=str_replace('d','д',$str);
	$str=str_replace('e','е',$str);
	$str=str_replace('j','ж',$str);
	$str=str_replace('z','з',$str);
	$str=str_replace('i','и',$str);
	$str=str_replace('i','й',$str);
	$str=str_replace('k','к',$str);
	$str=str_replace('l','л',$str);
	$str=str_replace('m','м',$str);
	$str=str_replace('n','н',$str);
	$str=str_replace('o','о',$str);
	$str=str_replace('p','п',$str);
	$str=str_replace('r','р',$str);
	$str=str_replace('s','с',$str);
	$str=str_replace('t','т',$str);
	$str=str_replace('u','у',$str);
	$str=str_replace('f','ф',$str);
	$str=str_replace('h','х',$str);
	$str=str_replace('c','ц',$str);
	$str=str_replace('ch','ч',$str);
	$str=str_replace('sh','ш',$str);
	$str=str_replace('sht','sщ',$str);
	$str=str_replace('i','ь',$str);
	$str=str_replace('a','ъ',$str);
	$str=str_replace('iu','ю',$str);
	$str=str_replace('ia','я',$str);
	
	return $str;
}

if ( ! function_exists('word_limiter'))
{
	function word_limiter($str, $limit = 100, $end_char = '&#8230;')
	{
		if (trim($str) == '')
		{
			return $str;
		}
	
		preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);
			
		if (strlen($str) == strlen($matches[0]))
		{
			$end_char = '';
		}
		
		return rtrim($matches[0]).$end_char;
	}
}
	


if ( ! function_exists('character_limiter'))
{
	function character_limiter($str, $n = 500, $end_char = '&#8230;')
	{
		if (strlen($str) < $n)
		{
			return $str;
		}
		
		$str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

		if (strlen($str) <= $n)
		{
			return $str;
		}
									
		$out = "";
		foreach (explode(' ', trim($str)) as $val)
		{
			$out .= $val.' ';			
			if (strlen($out) >= $n)
			{
				return trim($out).$end_char;
			}		
		} 
	}
}
	
	

?>