<?php
/*
# Copyright (c) 1998-2005 by GraFX Software Solutions.
# Copyright (c) www.grafxsoftware.com
# All rights reserved
# $Id: cls_string.php 11769 2009-03-28 08:40:15Z codefaster $
*/

class String
{
	var $return_string;
	var $string;
	var $intvars = array();

	function String($string = "")
	{
		$this->string = $string;
	}
// change the entry string in MYSQL

    function setIntVars($name){
    	$this->intvars[]=$name;
    }

	function change_mysql()
	{
	 $temp="";
	  if($this->string<>"")
         if(strlen($this->string) > 0)
            {
               	  $temp = str_replace("\\", "\\\\", $this->string);
				  $temp = str_replace("'", "\\'", $temp);
				  $temp = str_replace("\"", "\\\"", $temp);
            }
        return $temp;
	}//end getId

	function encode($passwd)
    {
    	return md5(input_text($passwd));
    }

    function generatePasswd()
    {
    	return substr(md5(time()),0,10);;
    }



	function js_ready($text)
	{
		$text=str_replace("\n","",$text);
		$text=str_replace("\r","",$text);
    	return addslashes($text);

    }



	function strips_univ($text)
	{
		if (get_magic_quotes_gpc())
    	   return stripslashes($text);
        else
    	   return $text;
    }
	// for text areas
	function textarea($text="")
	{
		$text = str_replace( '$', "&#036;", $text);
		$text=$this->strips_univ($text);
		return preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $text );
	}
	function changeEntertoBR($t="")
	{
		return str_replace( "\n", "<br />", $t );
	}
	/*-------------------------------------------------------------------------*/
	//
	// Convert <br /> to newlines
	//
	/*-------------------------------------------------------------------------*/
	function changeBRtoEnter($text="")
	{
		$text = str_replace( "<br />", "\n", $text );
		$text = str_replace( "<br>"  , "\n", $text );
		return $text;
	}
    /*-------------------------------------------------------------------------*/
    // Makes incoming info "safe"
    /*-------------------------------------------------------------------------*/
    function parse_all()
    {
    	global $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_CLIENT_IP, $REQUEST_METHOD, $REMOTE_ADDR, $HTTP_PROXY_USER, $HTTP_X_FORWARDED_FOR;
    		// PHP5 with register_long_arrays off?
			if (isset($_POST))
			{
				$HTTP_POST_VARS = $_POST;
				$HTTP_GET_VARS = $_GET;
				$HTTP_SERVER_VARS = $_SERVER;
				$HTTP_COOKIE_VARS = $_COOKIE;
				$HTTP_ENV_VARS = $_ENV;
				$HTTP_POST_FILES = $_FILES;
				// _SESSION is the only superglobal which is conditionally set
				if (isset($_SESSION))
				{
					$HTTP_SESSION_VARS = $_SESSION;
				}
			}

		$return = array();

    	//



    	if( is_array($HTTP_GET_VARS) )
		{
			while( list($k, $v) = each($HTTP_GET_VARS) )
			{

				 // Unset the globals
                unset($GLOBALS[$k]);

				if( is_array($HTTP_GET_VARS[$k]) )
				{
					while( list($k2, $v2) = each($HTTP_GET_VARS[$k]) )
					{
						$return[$k][ $this->clean_key($k2) ] = $this->clean_value($v2);
					}
				}
				else
				{
					$return[$k] = $this->clean_value($v);
				}
			}
		}
		// Overwrite GET data with post data
		if( is_array($HTTP_POST_VARS) )
		{

			// Unset the globals
            unset($GLOBALS[$k]);

			while( list($k, $v) = each($HTTP_POST_VARS) )
			{
				if ( is_array($HTTP_POST_VARS[$k]) )
				{
					while( list($k2, $v2) = each($HTTP_POST_VARS[$k]) )
					{
						$return[$k][ $this->clean_key($k2) ] = $this->clean_value($v2);
					}
				}
				else
				{
					$return[$k] = $this->clean_value($v);
				}
			}
		}
		//----------------------------------------
		// Sort out the accessing IP
		//----------------------------------------
		$addrs = array();
		foreach( array_reverse( explode( ',', $HTTP_X_FORWARDED_FOR ) ) as $x_f )
		{
			$x_f = trim($x_f);
			if ( preg_match( '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $x_f ) )
			{
				$addrs[] = $x_f;
			}
		}
		$addrs[] = $_SERVER['REMOTE_ADDR'];
		$addrs[] = $HTTP_PROXY_USER;
		$addrs[] = $REMOTE_ADDR;

		$return['IP_ADDRESS'] = $this->select_var( $addrs );
		// Make sure we take a valid IP address
		$return['IP_ADDRESS'] = preg_replace( "/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})/", "\\1.\\2.\\3.\\4", $return['IP_ADDRESS'] );
		$return['request_method'] = ( $_SERVER['REQUEST_METHOD'] != "" ) ? strtolower($_SERVER['REQUEST_METHOD']) : strtolower($REQUEST_METHOD);
		// REFERER
		$return['HTTP_REFERER'] = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"";

		// cleanup
		foreach($this->intvars as $value)
		{
		   if(isset($return[$value]))
				$return[$value] = intval($return[$value]);
		   else
				$return[$value] = 0;
		 }



		return $return;
	}
	   /*-------------------------------------------------------------------------*/
    // Variable chooser
    /*-------------------------------------------------------------------------*/
    function select_var($array) {
    	if ( !is_array($array) ) return -1;
    	ksort($array);

    	$chosen = -1;  // Ensure that we return zero if nothing else is available
    	foreach ($array as $k => $v)
    	{
    		if (isset($v))
    		{
    			$chosen = $v;
    			break;
    		}
    	}
    	return $chosen;
    }

    /*-------------------------------------------------------------------------*/
    // Key Cleaner - ensures no hackers entry with form elements
    /*-------------------------------------------------------------------------*/
    function clean_key($key) {
    	if ($key == "")
    	{
    		return "";
    	}
    	$key = preg_replace( "/\.\./"           , ""  , $key );
    	$key = preg_replace( "/\_\_(.+?)\_\_/"  , ""  , $key );
    	$key = preg_replace( "/^([\w\.\-\_]+)$/", "$1", $key );
    	return $key;
    }
    function clean_value($val) {
    	if ($val == "")
    	{
    		return "";
    	}
    	$val = str_replace( "&#032;", " ", $val );
    	$val = str_replace( "&"            , "&amp;"         , $val );
    	$val = str_replace( "<!--"         , "&#60;&#33;--"  , $val );
    	$val = str_replace( "-->"          , "--&#62;"       , $val );
    	$val = preg_replace( "/<script/i"  , "&#60;script"   , $val );
    	$val = str_replace( ">"            , "&gt;"          , $val );
    	$val = str_replace( "<"            , "&lt;"          , $val );
    	$val = str_replace( "\""           , "&quot;"        , $val );
        $val = preg_replace( "/\n/"        , "<br>"          , $val ); // Convert literal newlines
    	$val = preg_replace( "/\\\$/"      , "&#036;"        , $val );
    	$val = preg_replace( "/\r/"        , ""              , $val ); // Remove literal carriage returns
    	$val = str_replace( "!"            , "&#33;"         , $val );
    	$val = str_replace( "'"            , "&#39;"         , $val ); // IMPORTANT: It helps to increase sql query safety.


		 $val = $this->strips_univ($val);
    	// Swop user inputted backslashes
    	$val = preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $val );
    	return $val;
    }

// change the entry string in MYSQL

	function change_mysql_string($string)
	{
	 $temp="";
	  if($string<>"")
         if(strlen($string) > 0)
            {
               	  $temp = str_replace("\\", "\\\\", $string);
				  $temp = str_replace("'", "\\'", $temp);
				  $temp = str_replace("\"", "\\\"", $temp);
            }
        return $temp;
	}//end

// verify mail syntax
function checkemail($st)
{
if (strpos($st,"@")<2) {return false;}
if (strrpos($st,".")<2) return false;
return true;
}

// return a date at interval $days from today
function add_date_days($days){
$SQL = "SELECT DATE_FORMAT(DATE_ADD(Now(),INTERVAL ".$days." DAY ),'%Y-%m-%d') as data;";
$data=date("Y-m-d");
$retid = mysql_query($SQL) or die(mysql_error());
if ($row = mysql_fetch_array($retid))
do{
	$data = $row["data"];
}while($row = mysql_fetch_array($retid));
return $data;
}
// return a date at interval $days from $data
function add_date_days_from($data, $days){
$SQL = "SELECT DATE_FORMAT(DATE_ADD('".$data."',INTERVAL ".$days." DAY ),'%Y-%m-%d') as data;";
$data=date("Y-m-d");
$retid = mysql_query($SQL) or die(mysql_error());
if ($row = mysql_fetch_array($retid))
do{
	$data = $row["data"];
}while($row = mysql_fetch_array($retid));
return $data;
}
// return 1 or 0 if date $date is (or not) between date $first and $second
function isBetween($date,$first,$second){
$SQL = "SELECT  TO_DAYS('".$date."') BETWEEN TO_DAYS('".$first."') AND TO_DAYS('".$second."') as rez;";

$rez=0;
$retid = mysql_query($SQL) or die(mysql_error());
if ($row = mysql_fetch_array($retid))
do{
	$rez = $row["rez"];
}while($row = mysql_fetch_array($retid));
return $rez;
}

   function parse_all_no_check()
    {
    	global $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_CLIENT_IP, $REQUEST_METHOD, $REMOTE_ADDR, $HTTP_PROXY_USER, $HTTP_X_FORWARDED_FOR;
    	$return = array();
		if( is_array($HTTP_GET_VARS) )
		{
			while( list($k, $v) = each($HTTP_GET_VARS) )
			{
				if( is_array($HTTP_GET_VARS[$k]) )
				{
					while( list($k2, $v2) = each($HTTP_GET_VARS[$k]) )
					{
						$return[$k][ $this->clean_key($k2) ] = $v2;
					}
				}
				else
				{
					$return[$k] = $v;
				}
			}
		}
		// Overwrite GET data with post data
		if( is_array($HTTP_POST_VARS) )
		{
			while( list($k, $v) = each($HTTP_POST_VARS) )
			{
				if ( is_array($HTTP_POST_VARS[$k]) )
				{
					while( list($k2, $v2) = each($HTTP_POST_VARS[$k]) )
					{
						$return[$k][ $this->clean_key($k2) ] = $v2;
					}
				}
				else
				{
					$return[$k] = $v;
				}
			}
		}
		//----------------------------------------
		// Sort out the accessing IP
		//----------------------------------------
		$addrs = array();
		foreach( array_reverse( explode( ',', $HTTP_X_FORWARDED_FOR ) ) as $x_f )
		{
			$x_f = trim($x_f);
			if ( preg_match( '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $x_f ) )
			{
				$addrs[] = $x_f;
			}
		}
		$addrs[] = $_SERVER['REMOTE_ADDR'];
		$addrs[] = $HTTP_PROXY_USER;
		$addrs[] = $REMOTE_ADDR;

		$return['IP_ADDRESS'] = $this->select_var( $addrs );
		// Make sure we take a valid IP address
		$return['IP_ADDRESS'] = preg_replace( "/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})/", "\\1.\\2.\\3.\\4", $return['IP_ADDRESS'] );
		$return['request_method'] = ( $_SERVER['REQUEST_METHOD'] != "" ) ? strtolower($_SERVER['REQUEST_METHOD']) : strtolower($REQUEST_METHOD);
		// REFERER
		$return['HTTP_REFERER'] = $_SERVER['HTTP_REFERER'];

		return $return;
	}
	// change back things if needed
	function cleanDescription2($val) {
    	if ($val == "")
    	{
    		return "";
    	}
		$val = str_replace( "&lt;br /&gt;<br>" , "<br />", $val );
    	$val = str_replace( "&amp;"         , "&"           , $val );
    	$val = str_replace( "&#60;&#33;--"  ,"<!--"         , $val ); //-->
    	$val = str_replace( "--&#62;"       ,"-->"          , $val );
		$val = str_replace( "&#60;script"   ,"/<script/i"  , $val );
    	$val = str_replace( "&quot;"        , "\""           , $val );
    	$val = str_replace( "/\r/"        , ""              , $val );
    	$val = str_replace( "&#33;"         , "!"            , $val );
    	$val = str_replace( "&#39;"         , "'"            , $val );
		$val = str_replace( "&nbsp;"         , " "            , $val );
		$val = str_replace( "&amp;nbsp;"      , " "            , $val );
		$val = str_replace( "&gt;"            , ">"          , $val );
    	$val = str_replace( "&lt;"            , "<"          , $val );
		$val = str_replace( "<br>"         , ""           , $val );
		$val = str_replace( "\'"         , "'"           , $val );
    	return $val;
    } // function cleanDescription2

	function cleanDescriptionForRss($val) {
    	if ($val == "")
    	{
    		return "";
    	}
		$val = str_replace( "&lt;br /&gt;<br>" , "<br />", $val );
    	$val = str_replace( "&amp;"         , "&"           , $val );
    	$val = str_replace( "&#60;&#33;--"  ,"<!--"         , $val ); //-->
    	$val = str_replace( "--&#62;"       ,"-->"          , $val );
		$val = str_replace( "&#60;script"   ,"/<script/i"  , $val );
    	$val = str_replace( "&quot;"        , "\""           , $val );
		$val = str_replace( "\""        , "\""           , $val );
    	$val = str_replace( "/\r/"        , ""              , $val );
    	$val = str_replace( "&#33;"         , "!"            , $val );
    	$val = str_replace( "&#39;"         , "'"            , $val );
		$val = str_replace( "&nbsp;"         , " "            , $val );
		$val = str_replace( "&amp;nbsp;"      , " "            , $val );
		$val = str_replace( "&"         , "\&"           , $val );
		$val = str_replace( "<"      , "&lt;"            , $val );
		$val = str_replace( ">"      , "&gt;"            , $val );
		//$val = str_replace( "<br>"         , ""           , $val );
		$val = str_replace( "\'"         , "'"           , $val );
    	return $val;
    }// function cleanDescriptionForRss

	function cleanDescriptionForEditor($val) {
    	if ($val == "")
    	{
    		return "";
    	}
    	$val = str_replace( "\r\n"         , ""           , $val );
    	$val = str_replace( "<br>"         , ""           , $val );
		$val = str_replace( "\n"         , ""           , $val );
    	$val = str_replace( "&amp;"  ,"&"         , $val ); //-->
    	$val = str_replace( "&ldquo;"  ,"\""         , $val ); //-->
    	$val = str_replace( "&rdquo;"  ,"\""         , $val ); //-->
		$val = str_replace( "&lt;"      , "<"            , $val );
		$val = str_replace( "&gt;"      , ">"            , $val );
		$val = str_replace( "&amp;nbsp;"      , " "            , $val );
		$val = str_replace( "&amp;ldquo;"      , "'"            , $val );
		$val = str_replace( "&amp;rdquo;"      , "'"            , $val );
		$val = str_replace( "&quot;"      , "\""            , $val );
		$val = str_replace( "&#39;"         , "'"            , $val );

    	return $val;
    }// function cleanDescriptionForEditor

	// Link cleaner for SEO URL
	function CleanLink($link)
	{
		if ($link == "")
		{
			return "";
		}
		else
		{
			$link = str_replace("&amp;#259;","a",$link);
			$link = str_replace("&amp;#351;","s",$link);
			$link = str_replace("&amp;#355;","t",$link);
			$link = str_replace( "&#39;","",$link);
			$link = str_replace( "`","",$link);
			$link = str_replace( "~","",$link);
			$link = str_replace( "!","",$link);
			$link = str_replace( "@","",$link);
			$link = str_replace( "#","",$link);
			$link = str_replace( "$","",$link);
			$link = str_replace( "%","",$link);
			$link = str_replace( "^","",$link);
			$link = str_replace( "&","",$link);
			$link = str_replace( "*","",$link);
			$link = str_replace( "(","",$link);
			$link = str_replace( ")","",$link);
			$link = str_replace( "+","",$link);
			$link = str_replace( "=","",$link);
			$link = str_replace( "|","",$link);
			$link = str_replace( "{","",$link);
			$link = str_replace( "}","",$link);
			$link = str_replace( "[","",$link);
			$link = str_replace( "]","",$link);
			$link = str_replace( "/","",$link);
			$link = str_replace( "?","",$link);
			$link = str_replace( "<","",$link);
			$link = str_replace( ">","",$link);
			$link = str_replace( ",","",$link);
			$link = str_replace( ".","",$link);
			$link = str_replace( "'","",$link);
			$link = str_replace( "\"","",$link);
			$link = str_replace( ";","",$link);
			$link = str_replace( ":","",$link);
			$link = trim($link);
			$link = str_replace( " ","-",$link);
			$link = str_replace( "--","-",$link);
			return $link;
		}
	}// Function CleanLink 2004-02-10 iborbely

	// Replace Special characters (mostly in languages)
	function replaceRomaninanChar($value)
	{
		$value = str_replace("&amp;#259;","a",$value);
		$value = str_replace("&amp;#351;","s",$value);
		$value = str_replace("&amp;#355;","t",$value);
		$value = str_replace("&#350;","S",$value);
		$value = str_replace("&#354;","T",$value);
		$value = str_replace("&#258;","A",$value);
		$value = str_replace("&Icirc;","Î",$value);
		$value = str_replace("&Acirc;","Â",$value);
		$value = str_replace("&#351;","s",$value);
		$value = str_replace("&#355;","t",$value);
		$value = str_replace("&#259;","a",$value);
		$value = str_replace("&icirc;","î",$value);
		$value = str_replace("&acirc;","â",$value);
		return $value;
	}//Function replaceRomaninanChar 2004-03-04 iborbely

	// change entry string into MYSQL
	//chop spaces from parameters
    function input_text($str)
    {	//filter of spaces
        $str = clean_value($str);
    	$str = str_replace("'","`",$str);
    	$str = ereg_replace("[' ']+"," ",$str);
    	$str = ereg_replace("^ ","",$str);	//at the beginning
    	$str = ereg_replace(" $","",$str);	//at the end
    	$str = str_replace("$","&#036;",$str);
    	$str = addslashes($str);
        $str = preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $str );
        $str = nl2br($str);
    	return $str;
    }

	//chages html code into a js typed string
    function jsReturn($str)
    {

 		$ar=explode("\n",$str);

		$text="";
		foreach($ar as $value)
		   $text .="document.write('".str_replace("'","\'",trim($value))."');\n";

    	return $text;
    }



  function unhtmlspecialchars( $string )
{
  $string = str_replace ( '&amp;', '&', $string );
  $string = str_replace ( '&#039;', '\'', $string );
  $string = str_replace ( '&#39;', '\'', $string );
  $string = str_replace ( '&quot;', '"', $string );
  $string = str_replace ( '&lt;', '<', $string );
  $string = str_replace ( '&gt;', '>', $string );
  $string = str_replace ( '?', 's', $string );

  return $string;
}

function unhtmlspecialchars_rom( $string )
{

  $string = str_replace ( '&#x000BA;', 's', $string );

  return $string;
}

// changes back
//zoveel &lt;br /&gt;&lt;br /&gt;sss"
//to
//zoveel <br /><br />sss
function unhtmlentities ($string) {
		   $trans_tbl =get_html_translation_table (HTML_ENTITIES );
		   $trans_tbl =array_flip ($trans_tbl );
   return strtr ($string ,$trans_tbl );
}


	function formatDate($msqldate,$date_type)
	{
	  if ($msqldate=="0000-00-00 00:00:00") return $new_date;
		else
		{
			$timestamp = @strtotime($msqldate);
			/*$year = substr($msqldate,0,4)."<br>";
			$month = substr($msqldate,5,2)."<br>";
			$day = substr($msqldate,8,2)."<br>";
			$timestamp = mktime(0,0,0,$month, $day, $year);*/

		  setlocale(LC_TIME, strtolower($LANG)."_".strtoupper($LANG));
		  if ($date_type=="Y-m-d") $new_date = strftime("%Y-%m-%d",$timestamp );
			else if ($date_type=="y-n-d") $new_date = strftime("%y-%m-%d",$timestamp ); // ?????
				else  if ($date_type=="y-m-d") $new_date = strftime("%y-%m-%d" ,$timestamp );
				 else if ($date_type=="n/d/y") $new_date = strftime("%m/%d/%y",$timestamp ); // ????
					else if ($date_type=="d F Y") $new_date = strftime("%d %B %Y",$timestamp ); //%d %B %G
						else if ($date_type=="d M y") $new_date = strftime("%d %b %y",$timestamp );
							else if ($date_type=="M d,Y") $new_date = strftime("%b %d, %Y",$timestamp );
								else if ($date_type=="d-M-y") $new_date = strftime("%d-%b-%y",$timestamp );
									else if ($date_type=="dMy") $new_date = strftime("%d %b %y",$timestamp );
									 else if ($date_type=="l, F j, Y - h:i A") $new_date = strftime("%A, %B %d, %Y - %I:%M %p",$timestamp );                         else if ($date_type=="l, F j, Y - H:i") $new_date = strftime("%A, %B %d, %Y - %H:%M",$timestamp );

		//error_log($msqldate." ".$timestamp." ".$date_type)		;
	  	}
	  return $new_date;
	}


}
?>
