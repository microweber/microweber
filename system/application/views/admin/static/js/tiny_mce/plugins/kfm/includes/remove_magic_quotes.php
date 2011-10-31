<?php
// taken from http://www.phpfreaks.com/quickcode/Get-rid-of-magic_quotes_gpc/618.php
function traverse (&$arr)
{
	if (!is_array($arr))return;
	foreach ($arr as $key=>$val) {
		if (is_array($arr[$key])) traverse($arr[$key]);
		else $arr[$key] = stripslashes($arr[$key]);
	}
}
$gpc = array(&$_GET,&$_POST,&$_COOKIE);
traverse($gpc);
