<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * This is a PHP library that handles calling reCAPTCHA.
 *    - Documentation and latest version
 *          http://recaptcha.net/plugins/php/
 *    - Get a reCAPTCHA API Key
 *          http://recaptcha.net/api/getkey
 *    - Discussion group
 *          http://groups.google.com/group/recaptcha
 *
 * Copyright (c) 2007 reCAPTCHA -- http://recaptcha.net
 * AUTHORS:
 *   Mike Crawford
 *   Ben Maurer
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
* Recaptcha modified to integrate easily with Code Igniter
*
* @package     CodeIgniter
* @subpackage  Libraries
* @category    Captcha
* @author      Jon Trelfa <jtrelfa@gmail.com>
*/





class Recaptcha 
{
  
  var $_rConfig;
  var $_CI;
  var $_error;
  
  function __construct() 
  {
		$this->_CI =& get_instance();
    $this->_CI->config->load('recaptcha');
    $this->_CI->lang->load('recaptcha');
    
    $this->_rConfig = $this->_CI->config->item('recaptcha');
    
   // log_message('info',$this->_CI->lang->line('recaptcha_class_initialized'));
	}
	
	/**
    * Calls an HTTP POST function to verify if the user's guess was correct
    * @param string $privkey
    * @param string $remoteip
    * @param string $challenge
    * @param string $response
    * @param array $extra_params an array of extra variables to post to the server
    * @return ReCaptchaResponse
    */
  function check_answer ($remoteip, $challenge, $response, $extra_params = array()) 
  {
  	
   $ip = $_SERVER["REMOTE_ADDR"];
   $remoteip = $ip;
  	//var_dump($remoteip, $challenge, $response);
   // log_message('debug','Recaptcha::check_answer('.$remoteip.','.$challenge.','.$response.','.print_r($extra_params,TRUE).')');
  	if ($this->_rConfig['private'] == '') 
  	{
  		//log_message('error',$this->_CI->lang->line('recaptcha_no_private_key'));
  		return FALSE;
  	}
  
  	if ($remoteip == null || $remoteip == '') 
  	{
  		//log_message('error',$this->_CI->lang->line('recaptcha_no_remoteip'));
  		return FALSE;
  	}

    //discard spam submissions
    if ($challenge == null || strlen($challenge) == 0 || $response == null || strlen($response) == 0) 
    {
      $this->_error = 'incorrect-captcha-sol';
      return FALSE;
    }

    $response = $this->_http_post(
      $this->_rConfig['RECAPTCHA_VERIFY_SERVER'],
      "/verify",
      array (
        'privatekey' => $this->_rConfig['private'],
        'remoteip' => $remoteip,
        'challenge' => $challenge,
        'response' => $response
      ) + $extra_params
    );
    //log_message('debug','Recaptcha::_http_post response:'.print_r($response,TRUE));
    $answers = explode ("\n", $response[1]);

    if (trim($answers[0]) == 'true') 
    {
      return TRUE;
    } 
    else 
    {
      $this->_error = $answers[1];
      return FALSE;
    }
  }
	
	/**
   * Gets the challenge HTML (javascript and non-javascript version).
   * This is called from the browser, and the resulting reCAPTCHA HTML widget
   * is embedded within the HTML form it was called from.
   * @param string $pubkey A public key for reCAPTCHA
   * @param string $error The error given by reCAPTCHA (optional, default is null)
   * @param boolean $use_ssl Should the request be made over ssl? (optional, default is false)
  
   * @return string - The HTML to be embedded in the user's form.
   */
  function get_html ($lang = 'en',$use_ssl = false) 
  {
  	//log_message('debug','Recaptcha::get_html('.$use_ssl.')');
  	if ($this->_rConfig['public'] == '') 
  	{
     // log_message('error',$this->_CI->lang->line('recaptcha_no_private_key'));
      return $this->_CI->lang->line('recaptcha_html_error');
  	}
  	
  	if ($use_ssl) 
  	{
  	  $server = $this->_rConfig['RECAPTCHA_API_SECURE_SERVER'];
  	} 
  	else 
  	{
  	  $server = $this->_rConfig['RECAPTCHA_API_SERVER'];
  	}

    $errorpart = '';
    if ($this->_error !== '') 
    {
       $errorpart = "&amp;error=" . $this->_error;
    }
    $html_data = array(
      'server' => $server,
      'key' => $this->_rConfig['public'],
      'theme' => $this->_rConfig['theme'],
      'lang' => $lang,
      //appends The error display for the reCaptcha to the url
      'errorpart' => $errorpart
    );
    //load a view - more configurable than embedding HTML in the library
    
    return $this->_CI->load->view('recaptcha',$html_data,TRUE); 
  }
  
  /**
   * gets a URL where the user can sign up for reCAPTCHA. If your application
   * has a configuration page where you enter a key, you should provide a link
   * using this function.
   * @param string $domain The domain where the page is hosted
   * @param string $appname The name of your application
   */
  function get_signup_url ($domain = null, $appname = null) 
  {
  	return "http://recaptcha.net/api/getkey?".$this->_qsencode(array ('domain' => $domain, 'app' => $appname));
  }

	
	/**
   * Encodes the given data into a query string format
   * @param $data - array of string elements to be encoded
   * @return string - encoded request
   */
  function _qsencode($data) 
  {
    //log_message('debug',"Recaptcha::_qsencode(\n".print_r($data,TRUE)."\n)");
    //http_build_query() = PHP 5 ONLY!
    return http_build_query($data);
  }

  /**
   * Submits an HTTP POST to a reCAPTCHA server
   * @param string $host
   * @param string $path
   * @param array $data
   * @param int port
   * @return array response
   */
  function _http_post($host, $path, $data, $port = 80) 
  {
   // log_message('debug','Recaptcha::http_post('.$host.','.$path.','.print_r($data,TRUE).','.$port.')');
    
    $req = $this->_qsencode($data);
    $http_request = implode('',array(
      "POST $path HTTP/1.0\r\n",
      "Host: $host\r\n",
      "Content-Type: application/x-www-form-urlencoded;\r\n",
      "Content-Length:".strlen($req)."\r\n",
      "User-Agent: reCAPTCHA/PHP\r\n",
      "\r\n",
      $req));
    $response = '';
    if( false == ( $fs = @fsockopen($host, $port, $errno, $errstr, 10) ) ) 
    {
      //log_message('error',$this->_CI->lang->line('recaptcha_socket_fail'));
    }
    
    fwrite($fs, $http_request);
    while (!feof($fs)) 
    {
      $response .= fgets($fs, 1160); // One TCP-IP packet
    }
    fclose($fs);
    $response = explode("\r\n\r\n", $response, 2);
    
    return $response;
  }
}