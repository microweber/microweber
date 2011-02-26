<?php
class BBcode
{
	var $bbcodes;
	var $pattern;
	var $replace;
	
	function BBcode()
	{
		$this->bbcodes['b'] = true; //true for bold 
		$this->bbcode['i'] = true;   //true for italic
		$this->bbcode['u'] = true;  //true for underline
		$this->bbcode['o'] = true;  //true for highlight
		$this->bbcode['s'] = true;  //true for strike
		$this->bbcode['list'] = true;  //true for list
		$this->bbcode['url'] = true;  //true for link
		$this->bbcode['img'] = true;  //true for link
		$this->bbcode['email'] = true;  //true for email
		$this->bbcode['color'] = true;  //true for text color
		$this->bbcode['size'] = true;  //true for text size

		$this->pattern[] = '#\[b\](.+)\[/b\]#isU';
		if ($this->bbcodes['b']) {
			$this->replace[] = '<span style="font-weight: bold">$1</span>';
		} else {
			$this->replace[] = '$1';
		}
		if ($this->bbcode['i']) {
			$this->pattern[] = '#\[i\](.+)\[/i\]#isU';
			$this->replace[] = '<span style="font-style: italic">$1</span>';
		}
		if ($this->bbcode['u']) {
			$this->pattern[] = '#\[u\](.+)\[/u\]#isU';
			$this->replace[] = '<span style="text-decoration: underline">$1</span>';
		}
		if ($this->bbcode['o']) {
			$this->pattern[] = '#\[o\](.+)\[/o\]#isU';
			$this->replace[] = '<span class="highlight">$1</span>';
		}
		if ($this->bbcode['s']) {
			$this->pattern[] = '#\[s\](.+)\[/s\]#isU';
			$this->replace[] = '<span style="text-decoration: line-through;">$1</span>';
		}
		if ($this->bbcode['list']) {
			$this->pattern[] = '#[\n\r]*\[list\](.+)\[/list\][\n\r]*#ise';
			$this->replace[] = '$this->ParseList(\'$1\')';
		}
		if ($this->bbcode['url']) {
			$this->pattern[] = '#\[url\]([^ ]+)\[/url\]#ieU';
			$this->pattern[] = '#\[url=([^ ]+)\](.+)\[/url\]#ieU';
			$this->replace[] = '$this->ParseUrl(\'$1\')';
			$this->replace[] = '$this->ParseUrl(\'$1\', \'$2\')';
		}
		if ($this->bbcode['img']) {
			$this->pattern[] = '#\[img\]([^ ]+)\[/img\]#ieU';
			$this->pattern[] = '#\[img=([^ ]+)\]#ieU';
			$this->replace[] = '$this->ParseImage(\'$1\')';
			$this->replace[] = '$this->ParseImage(\'$1\')';
		}
		if ($this->bbcode['email']) {
			$this->pattern[] = '#\[(e?mail)\]([^ ]+)\[/\\1\]#ieU';
			$this->pattern[] = '#\[e?mail=([^ ]+)\]#ieU';
			$this->replace[] = '$this->ParseEmail(\'$2\')';
			$this->replace[] = '$this->ParseEmail(\'$1\')';
		}
		if ($this->bbcode['color']) {
			$this->pattern[] = '#\[color=(.+)\](.+)\[/color\]#isU';
			$this->replace[] = '<span style="color: $1;">$2</span>';
		}
		if ($this->bbcode['size']) {
			$this->pattern[] = '#\[size=(.+)\](.+)\[/size\]#isU';
			$this->replace[] = '<span style="font-size: ${1}px;">$2</span>';
		}

		$this->pattern[] = '#[\n\r]+#is';
		$this->replace[] = '<br />';
}
	//  Replace bbcode in message and return the new message
	function ParseList($message)
	{
		return '<ul><li>'.preg_replace('#[\n\r]+#is', '</li><li>', trim($message)).'</li></ul>';
	}

	function ParseUrl($url, $name = false)
	{
		if (!preg_match("#^(http://|ftp://|https://)#i", $url)) {
			$url = 'http://'. $url;
		}
		if (!preg_match("#^(http://|ftp://|https://)?(.+\.)*(.{2,4})(.+)?$#iU", $url)) {
			return $url;
		}
		if ($name) {
			return '<a href="'. $url .'" title="'. $url .'">'. $name.'</a>';
		}
		return '<a href="'. $url .'" title="'. $url .'">'. $url .'</a>';
	}

	//Fonction pour vérifier l'éxtension de l'image et sa taille:
	function ParseImage($image)
	{
		// ext allowed
		$exts = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
		$ext = strtolower(substr($image, strrpos($image, ".") + 1));
		if (in_array($ext, $exts)) {
/*			
			$h = null;
			$l = null;
			if (list($h,$l) = @getimagesize($image)) {
				if ($h>100) {
					$l = $l*$h/100;
					$h = 100;
				} else {
					$h = false;
				}
				if ($l>100) {
					$h = $h*$l/100;
					$l = 100;
				} else {
					$l = false;
				}
			}
*/
			$img = "<img src='$image' alt='?'";
/*			
			if ($l || $h) {
				$img .= "style='";
				if ($l) {
					$img .= "width: ${l}px; ";
				}
				if ($h) {
					$img .= "height: ${h}px; ";
				}
			}
*/			
			$img .= "' />";
			return $img;
		}
		return $image;
	}

	function ParseEmail($adr)
	{
		if (preg_match("#^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z]{2,4}$#", $adr)) {
			return "<a href='mailto:$adr'>$adr</a>";
		}
		return $adr;
	}

	//  Replace bbcode in message and return the new message
	function Parse($message)
	{
		$message = preg_replace($this->pattern, $this->replace, $message);
		//$message = htmlentities($message);
		return stripslashes($message);
	}
}

?>