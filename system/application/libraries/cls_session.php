<?php
/*
############################################################
SESSION			$Name:  $
Revision		$Revision: 1.1 $
Author			$Author: zelteto $
Created 03/01/03        $Date: 2007/04/06 10:36:23 $
Copyright (c) 1998-2007 GraFX (webmaster@grafxsoftware.com)
Scripts Home:           http://www.grafxsoftware.com
############################################################
*/
class MYSession {
	var $MY_SESSION_ARRAY = array();
	var $session_state = 0;
	function MYSession ()
	{
		// Use $HTTP_SESSION_VARS with PHP 4.0.6 or less
		 $session_state = session_start ();
		 if(!$session_state)
			return 0;
		 else
		 {
			if (!empty($_SESSION))
			{
				//$this->MY_SESSION_ARRAY =$this->array_copy($_SESSION);
				$this->MY_SESSION_ARRAY = $_SESSION;
			}
			else
			{
				global $HTTP_SESSION_VARS;
				if (!empty($HTTP_SESSION_VARS))
				{
					$this->MY_SESSION_ARRAY = $HTTP_SESSION_VARS;
				}
			}
		}
	}// function Mysession
	//*********************
	function get($name="")
	{
		if(empty($name))
	  		return 0;
		else
		{
			if(empty($this->MY_SESSION_ARRAY) || empty($this->MY_SESSION_ARRAY[$name]))
				return 0;
			else
			   return $this->MY_SESSION_ARRAY[$name];
		}
	}// function get
	//************************
	function set($name,$value)
	{
		if(empty($name))
			return 0;
		else
		{
			$this->MY_SESSION_ARRAY[$name]=$value;
			if(version_compare("4.0.6", phpversion(), ">"))
			{
				global $HTTP_SESSION_VARS;
				$HTTP_SESSION_VARS[$name]=$value;
			}
			else
			  $_SESSION[$name]=$value;
		}
	}// function set
	//******************
	function del($name)
	{
		if(empty($name) || empty($this->MY_SESSION_ARRAY[$name]))
			return 0;
		else
		{
			unset($this->MY_SESSION_ARRAY[$name]);
			if(version_compare("4.0.6", phpversion(), ">"))
			{
				global $HTTP_SESSION_VARS;
				unset($HTTP_SESSION_VARS[$name]);
			}
			else
				unset($_SESSION[$name]);
		}
	}// function del
	//****************
	function del_all()
	{
		$this->MY_SESSION_ARRAY[$name]=array();
		if(version_compare("4.0.6", phpversion(), ">"))
		{
			global $HTTP_SESSION_VARS;
			$HTTP_SESSION_VARS[$name]=array();
		}
		else
			$_SESSION[$name]=array();
	}// function del_all
	//*************************
	function array_copy($array)
	{
		$newarray= array();
		foreach ($array as $key => $value)
		{
			$newarray[$key]=$value;
		}
		return  $newarray;
	}// function array_copy
}// end class
?>