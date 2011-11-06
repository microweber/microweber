<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
# Author: Vincent JAULIN
# Copyright: Keyphrene.com 2008 @ all rights reserved

// function to parse the http auth header
function http_digest_parse($txt)
{
	// protect against missing data
	$needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
	$data = array();

	preg_match_all('@(\w+)=([\'"]?)([%a-zA-Z0-9=./\_-]+)\2@', $txt, $matches, PREG_SET_ORDER);

	foreach ($matches as $m) {
		$data[$m[1]] = $m[3];
		unset($needed_parts[$m[1]]);
	}

	return $needed_parts ? false : $data;
}

function AuthenticationDigestHTTP($realm, $users, $phpcgi=0) {
	if (empty($_SERVER['PHP_AUTH_DIGEST']) && empty($_SERVER['REDIRECT_REMOTE_USER'])){
		header('HTTP/1.1 401 Unauthorized');
		header('WWW-Authenticate: Digest realm="'.$realm.'" qop="auth" nonce="'.uniqid(rand(), true).'" opaque="'.md5($realm).'"');
		die('401 Unauthorized');
	}
	// analyze the PHP_AUTH_DIGEST variable
	$auth = $_SERVER['PHP_AUTH_DIGEST'];
	if ($phpcgi == 1) {
		$auth = $_SERVER['REDIRECT_REMOTE_USER'];
	}
	$data = http_digest_parse($auth);
	if (!array_key_exists($data['username'], $users)) {
		header('HTTP/1.1 401 Unauthorized');
		die('401 Unauthorized');
	}

	// generate the valid response
	$A1 = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
	$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
	$valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

	if ($data['response'] != $valid_response) {
		header('HTTP/1.1 401 Unauthorized');
		die('401 Unauthorized');
	}
	return TRUE;
}

function AuthenticationBasicHTTP($realm, $users, $phpcgi=0) {

	if (empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['REDIRECT_REMOTE_USER'])) {
		header('WWW-Authenticate: Basic realm="'.$realm.'"');
		header('HTTP/1.0 401 Unauthorized');
		die('401 Unauthorized');
	}

	$user = $_SERVER['PHP_AUTH_USER'];
	if ($phpcgi == 1) {
		$matches = explode(' ', $_SERVER['REDIRECT_REMOTE_USER']);
		list($name, $password) = explode(':', base64_decode($matches[1]));
		$_SERVER['PHP_AUTH_USER'] = $user = strip_tags($name);
		$_SERVER['PHP_AUTH_PW']    = strip_tags($password);
	}

	if (array_key_exists($user, $users) && $users[$user] == extEncodePassword($_SERVER['PHP_AUTH_PW']) ){
		activate_user($user, extEncodePassword($_SERVER['PHP_AUTH_PW']));
		return TRUE;
	}

	header('WWW-Authenticate: Basic realm="'.$realm.'"');
	header('HTTP/1.0 401 Unauthorized');
	die('401 Unauthorized');
	return FALSE;
}
?>